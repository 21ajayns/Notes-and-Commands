<?php
declare(strict_types=1);

namespace Tests\Feature\Http\Note;

use App\Constants\CategoryEnum;
use App\DataTransferObjects\Note\NoteCreateDto;
use App\Repositories\Note\NoteRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NoteDeleteControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testItDeletesTheNote(): void
    {
        $note = (new NoteRepository())->create(new NoteCreateDto('Standup notes', 'Discussed roadmap for Q3', CategoryEnum::OFFICE()));

        $response = $this->deleteJson("/api/notes/{$note->getAttribute('id')}");

        $response->assertNoContent();

        $this->assertDatabaseMissing('notes', [
            'id' => $note->getAttribute('id'),
        ]);
    }

    public function testItReturnsNotFoundForANonExistentNote(): void
    {
        $response = $this->deleteJson('/api/notes/00000000-0000-0000-0000-000000000000');

        $response->assertNotFound();
    }
}
