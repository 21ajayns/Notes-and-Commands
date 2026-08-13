<?php
declare(strict_types=1);

namespace Tests\Feature\Http\Note;

use App\Constants\CategoryEnum;
use App\DataTransferObjects\Folder\FolderCreateDto;
use App\Repositories\Folder\FolderRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NoteCreateControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testItCreatesANoteWithoutAFolder(): void
    {
        $response = $this->postJson('/api/notes', [
            'title' => 'Standup notes',
            'content' => 'Discussed roadmap for Q3',
            'category' => 'office',
        ]);

        $response->assertCreated();
        $response->assertJsonPath('title', 'Standup notes');
        $response->assertJsonPath('category', 'office');
        $response->assertJsonPath('folder_id', null);

        $this->assertDatabaseHas('notes', [
            'title' => 'Standup notes',
            'category' => 'office',
            'folder_id' => null,
        ]);
    }

    public function testItCreatesANoteLinkedToAFolder(): void
    {
        $folder = (new FolderRepository())->create(new FolderCreateDto('Work', CategoryEnum::OFFICE()));

        $response = $this->postJson('/api/notes', [
            'title' => 'Standup notes',
            'content' => 'Discussed roadmap for Q3',
            'category' => 'office',
            'folder_id' => $folder->getAttribute('id'),
        ]);

        $response->assertCreated();
        $response->assertJsonPath('folder_id', $folder->getAttribute('id'));

        $this->assertDatabaseHas('notes', [
            'title' => 'Standup notes',
            'folder_id' => $folder->getAttribute('id'),
        ]);
    }

    public function testItRejectsAMissingTitle(): void
    {
        $response = $this->postJson('/api/notes', [
            'content' => 'Discussed roadmap for Q3',
            'category' => 'office',
        ]);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors(['title']);
    }

    public function testItRejectsAnInvalidCategory(): void
    {
        $response = $this->postJson('/api/notes', [
            'title' => 'Standup notes',
            'content' => 'Discussed roadmap for Q3',
            'category' => 'not-a-real-category',
        ]);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors(['category']);
    }

    public function testItRejectsANonExistentFolder(): void
    {
        $response = $this->postJson('/api/notes', [
            'title' => 'Standup notes',
            'content' => 'Discussed roadmap for Q3',
            'category' => 'office',
            'folder_id' => '00000000-0000-0000-0000-000000000000',
        ]);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors(['folder_id']);
    }
}