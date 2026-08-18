<?php
declare(strict_types=1);

namespace Tests\Feature\Http\Note;

use App\Constants\CategoryEnum;
use App\DataTransferObjects\Note\NoteCreateDto;
use App\Repositories\Note\NoteRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NoteShowControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testItReturnsTheNote(): void
    {
        $note = (new NoteRepository())->create(new NoteCreateDto('Standup notes', 'Discussed roadmap for Q3', CategoryEnum::OFFICE()));

        $response = $this->getJson("/api/notes/{$note->getAttribute('id')}");

        $response->assertOk();
        $response->assertJsonPath('id', $note->getAttribute('id'));
        $response->assertJsonPath('title', 'Standup notes');
        $response->assertJsonPath('content', 'Discussed roadmap for Q3');
    }

    public function testItReturnsNotFoundForANonExistentNote(): void
    {
        $response = $this->getJson('/api/notes/00000000-0000-0000-0000-000000000000');

        $response->assertNotFound();
    }
}
