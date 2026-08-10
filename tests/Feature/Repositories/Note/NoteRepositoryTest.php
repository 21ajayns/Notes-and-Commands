<?php
declare(strict_types=1);

namespace Tests\Feature\Repositories\Note;

use App\Constants\Note\NoteCategoryEnum;
use App\DataTransferObjects\Note\NoteCreateDto;
use App\Repositories\Note\NoteRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NoteRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function testCreatePersistsANoteWithOfficeCategory(): void
    {
        $dto = new NoteCreateDto(
            'Standup notes',
            'Discussed roadmap for Q3',
            NoteCategoryEnum::OFFICE()
        );

        $note = (new NoteRepository())->create($dto);

        $this->assertDatabaseHas('notes', [
            'id' => $note->getAttribute('id'),
            'title' => 'Standup notes',
            'content' => 'Discussed roadmap for Q3',
            'category' => 'office',
        ]);
    }

    public function testCreatePersistsANoteWithPersonalCategory(): void
    {
        $dto = new NoteCreateDto(
            'Grocery list',
            'Milk, eggs, bread',
            NoteCategoryEnum::PERSONAL()
        );

        $note = (new NoteRepository())->create($dto);

        $this->assertSame('personal', $note->getAttribute('category'));

        $this->assertDatabaseHas('notes', [
            'id' => $note->getAttribute('id'),
            'category' => 'personal',
        ]);
    }
}
