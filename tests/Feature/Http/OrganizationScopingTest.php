<?php
declare(strict_types=1);

namespace Tests\Feature\Http;

use App\Constants\CategoryEnum;
use App\DataTransferObjects\Command\CommandCreateDto;
use App\DataTransferObjects\CommandFolder\CommandFolderCreateDto;
use App\DataTransferObjects\Folder\FolderCreateDto;
use App\DataTransferObjects\Note\NoteCreateDto;
use App\DataTransferObjects\Task\TaskCreateDto;
use App\Models\Organization\Organization;
use App\Repositories\Command\CommandRepository;
use App\Repositories\CommandFolder\CommandFolderRepository;
use App\Repositories\Folder\FolderRepository;
use App\Repositories\Note\NoteRepository;
use App\Repositories\Task\TaskRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrganizationScopingTest extends TestCase
{
    use RefreshDatabase;

    private Organization $otherOrganization;

    protected function setUp(): void
    {
        parent::setUp();

        $this->otherOrganization = Organization::query()->create(['name' => 'Other Organization']);
    }

    public function testEveryEndpointRequiresASignedInUser(): void
    {
        $this->getJson('/api/notes')->assertUnauthorized();
        $this->getJson('/api/folders')->assertUnauthorized();
        $this->getJson('/api/tasks')->assertUnauthorized();
        $this->getJson('/api/commands')->assertUnauthorized();
        $this->getJson('/api/command-folders')->assertUnauthorized();
        $this->postJson('/api/tasks', ['title' => 'Sneaky', 'category' => 'office'])->assertUnauthorized();

        $this->assertDatabaseCount('tasks', 0);
    }

    public function testListsOnlyReturnTheSignedInUsersOrganization(): void
    {
        $this->signIn();
        $other = $this->otherOrganization->getId();

        (new NoteRepository())->create(new NoteCreateDto($this->organizationId(), 'Mine', 'Body', CategoryEnum::OFFICE()));
        (new NoteRepository())->create(new NoteCreateDto($other, 'Theirs', 'Body', CategoryEnum::OFFICE()));
        (new FolderRepository())->create(new FolderCreateDto($this->organizationId(), 'My folder', CategoryEnum::OFFICE()));
        (new FolderRepository())->create(new FolderCreateDto($other, 'Their folder', CategoryEnum::OFFICE()));
        (new TaskRepository())->create(new TaskCreateDto($this->organizationId(), 'My task', CategoryEnum::OFFICE()));
        (new TaskRepository())->create(new TaskCreateDto($other, 'Their task', CategoryEnum::OFFICE()));
        (new CommandRepository())->create(new CommandCreateDto($this->organizationId(), 'My set', [], CategoryEnum::OFFICE()));
        (new CommandRepository())->create(new CommandCreateDto($other, 'Their set', [], CategoryEnum::OFFICE()));
        (new CommandFolderRepository())->create(new CommandFolderCreateDto($this->organizationId(), 'My command folder', CategoryEnum::OFFICE()));
        (new CommandFolderRepository())->create(new CommandFolderCreateDto($other, 'Their command folder', CategoryEnum::OFFICE()));

        $this->getJson('/api/notes')->assertOk()->assertJsonCount(1)->assertJsonPath('0.title', 'Mine');
        $this->getJson('/api/folders')->assertOk()->assertJsonCount(1)->assertJsonPath('0.name', 'My folder');
        $this->getJson('/api/tasks')->assertOk()->assertJsonCount(1)->assertJsonPath('0.title', 'My task');
        $this->getJson('/api/commands')->assertOk()->assertJsonCount(1)->assertJsonPath('0.title', 'My set');
        $this->getJson('/api/command-folders')->assertOk()->assertJsonCount(1)->assertJsonPath('0.name', 'My command folder');
    }

    public function testRecordsCreatedThroughTheApiBelongToTheUsersOrganization(): void
    {
        $this->signIn();

        $this->postJson('/api/tasks', ['title' => 'Plan release', 'category' => 'office'])->assertCreated();
        $this->postJson('/api/notes', ['title' => 'Idea', 'content' => 'Body', 'category' => 'office'])->assertCreated();

        $this->assertDatabaseHas('tasks', ['title' => 'Plan release', 'organization_id' => $this->organizationId()]);
        $this->assertDatabaseHas('notes', ['title' => 'Idea', 'organization_id' => $this->organizationId()]);
    }

    public function testAnotherOrganizationsRecordsCannotBeReadChangedOrDeleted(): void
    {
        $this->signIn();
        $other = $this->otherOrganization->getId();

        $note = (new NoteRepository())->create(new NoteCreateDto($other, 'Theirs', 'Secret', CategoryEnum::OFFICE()));
        $task = (new TaskRepository())->create(new TaskCreateDto($other, 'Their task', CategoryEnum::OFFICE()));
        $command = (new CommandRepository())->create(new CommandCreateDto($other, 'Their set', [], CategoryEnum::OFFICE()));
        $folder = (new FolderRepository())->create(new FolderCreateDto($other, 'Their folder', CategoryEnum::OFFICE()));

        $this->getJson("/api/notes/{$note->getId()}")->assertNotFound();
        $this->putJson("/api/notes/{$note->getId()}", ['title' => 'Mine now', 'content' => 'x'])->assertNotFound();
        $this->deleteJson("/api/notes/{$note->getId()}")->assertNotFound();
        $this->putJson("/api/tasks/{$task->getId()}", ['status' => 'completed'])->assertNotFound();
        $this->deleteJson("/api/tasks/{$task->getId()}")->assertNotFound();
        $this->getJson("/api/commands/{$command->getId()}")->assertNotFound();
        $this->deleteJson("/api/folders/{$folder->getId()}")->assertNotFound();

        $this->assertDatabaseHas('notes', ['id' => $note->getId(), 'title' => 'Theirs']);
        $this->assertDatabaseHas('tasks', ['id' => $task->getId(), 'status' => 'active']);
        $this->assertDatabaseHas('folders', ['id' => $folder->getId()]);
    }

    public function testClearingTasksOnlyRemovesTheUsersOrganization(): void
    {
        $this->signIn();

        (new TaskRepository())->create(new TaskCreateDto($this->organizationId(), 'Mine', CategoryEnum::OFFICE()));
        $theirs = (new TaskRepository())->create(new TaskCreateDto($this->otherOrganization->getId(), 'Theirs', CategoryEnum::OFFICE()));

        $this->deleteJson('/api/tasks/clear?category=office')->assertNoContent();

        $this->assertDatabaseCount('tasks', 1);
        $this->assertDatabaseHas('tasks', ['id' => $theirs->getId()]);
    }

    public function testIdsThatAreNotUuidsAreRejectedCleanly(): void
    {
        $this->signIn();

        $this->getJson('/api/notes/not-a-uuid')->assertNotFound();
        $this->deleteJson('/api/tasks/123')->assertNotFound();
        $this->getJson('/api/notes?folder_id=not-a-uuid')
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['folder_id']);
    }

    public function testANoteCannotBeFiledInAnotherOrganizationsFolder(): void
    {
        $this->signIn();

        $theirFolder = (new FolderRepository())->create(
            new FolderCreateDto($this->otherOrganization->getId(), 'Their folder', CategoryEnum::OFFICE())
        );

        $response = $this->postJson('/api/notes', [
            'title' => 'Sneaky',
            'content' => 'Body',
            'category' => 'office',
            'folder_id' => $theirFolder->getId(),
        ]);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors(['folder_id']);
    }
}
