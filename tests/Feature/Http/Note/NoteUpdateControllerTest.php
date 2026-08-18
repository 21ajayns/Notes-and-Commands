<?php
declare(strict_types=1);

namespace Tests\Feature\Http\Note;

use App\Constants\CategoryEnum;
use App\DataTransferObjects\Note\NoteCreateDto;
use App\Repositories\Note\NoteRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NoteUpdateControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testItUpdatesTheNoteTitleAndContent(): void
    {
        $note = (new NoteRepository())->create(new NoteCreateDto('Standup notes', 'Discussed roadmap for Q3', CategoryEnum::OFFICE()));

        $response = $this->putJson("/api/notes/{$note->getAttribute('id')}", [
            'title' => 'Standup notes (revised)',
            'content' => 'Discussed roadmap for Q4',
        ]);

        $response->assertOk();
        $response->assertJsonPath('title', 'Standup notes (revised)');
        $response->assertJsonPath('content', 'Discussed roadmap for Q4');

        $this->assertDatabaseHas('notes', [
            'id' => $note->getAttribute('id'),
            'title' => 'Standup notes (revised)',
            'content' => 'Discussed roadmap for Q4',
        ]);
    }

    public function testItRejectsAMissingTitle(): void
    {
        $note = (new NoteRepository())->create(new NoteCreateDto('Standup notes', 'Discussed roadmap for Q3', CategoryEnum::OFFICE()));

        $response = $this->putJson("/api/notes/{$note->getAttribute('id')}", [
            'content' => 'Discussed roadmap for Q4',
        ]);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors(['title']);
    }

    public function testItReturnsNotFoundForANonExistentNote(): void
    {
        $response = $this->putJson('/api/notes/00000000-0000-0000-0000-000000000000', [
            'title' => 'Title',
            'content' => 'Content',
        ]);

        $response->assertNotFound();
    }
}
