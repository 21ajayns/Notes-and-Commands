<?php
declare(strict_types=1);

namespace Tests\Feature\Http\Note;

use App\Constants\CategoryEnum;
use App\DataTransferObjects\Folder\FolderCreateDto;
use App\DataTransferObjects\Note\NoteCreateDto;
use App\Repositories\Folder\FolderRepository;
use App\Repositories\Note\NoteRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NoteGetControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->signIn();
    }

    public function testItReturnsTopLevelNotesWhenNoFolderIdGiven(): void
    {
        $repository = new NoteRepository();
        $repository->create(new NoteCreateDto($this->organizationId(), 'Standup notes', 'Discussed roadmap for Q3', CategoryEnum::OFFICE()));
        $repository->create(new NoteCreateDto($this->organizationId(), 'Grocery list', 'Milk, eggs, bread', CategoryEnum::PERSONAL()));

        $response = $this->getJson('/api/notes');

        $response->assertOk();
        $response->assertJsonCount(2);
    }

    public function testItReturnsOnlyNotesUnderTheGivenFolderId(): void
    {
        $folder = (new FolderRepository())->create(new FolderCreateDto($this->organizationId(), 'Work', CategoryEnum::OFFICE()));

        $repository = new NoteRepository();
        $inFolder = $repository->create(new NoteCreateDto($this->organizationId(), 'Standup notes', 'Discussed roadmap for Q3', CategoryEnum::OFFICE(), $folder->getAttribute('id')));
        $repository->create(new NoteCreateDto($this->organizationId(), 'Grocery list', 'Milk, eggs, bread', CategoryEnum::PERSONAL()));

        $response = $this->getJson('/api/notes?folder_id=' . $folder->getAttribute('id'));

        $response->assertOk();
        $response->assertJsonCount(1);
        $response->assertJsonPath('0.id', $inFolder->getAttribute('id'));
    }

    public function testItRejectsANonExistentFolderId(): void
    {
        $response = $this->getJson('/api/notes?folder_id=00000000-0000-0000-0000-000000000000');

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors(['folder_id']);
    }
}
