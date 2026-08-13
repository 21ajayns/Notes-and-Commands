<?php
declare(strict_types=1);

namespace Tests\Feature\Repositories\Note;

use App\Constants\CategoryEnum;
use App\DataTransferObjects\Folder\FolderCreateDto;
use App\DataTransferObjects\Note\NoteCreateDto;
use App\Repositories\Folder\FolderRepository;
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
            CategoryEnum::OFFICE()
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
            CategoryEnum::PERSONAL()
        );

        $note = (new NoteRepository())->create($dto);

        $this->assertSame('personal', $note->getAttribute('category'));

        $this->assertDatabaseHas('notes', [
            'id' => $note->getAttribute('id'),
            'category' => 'personal',
        ]);
    }

    public function testCreatePersistsANoteWithoutAFolder(): void
    {
        $dto = new NoteCreateDto(
            'Standup notes',
            'Discussed roadmap for Q3',
            CategoryEnum::OFFICE()
        );

        $note = (new NoteRepository())->create($dto);

        $this->assertNull($note->getAttribute('folder_id'));
    }

    public function testCreatePersistsANoteLinkedToAFolder(): void
    {
        $folder = (new FolderRepository())->create(new FolderCreateDto('Work', CategoryEnum::OFFICE()));

        $dto = new NoteCreateDto(
            'Standup notes',
            'Discussed roadmap for Q3',
            CategoryEnum::OFFICE(),
            $folder->getAttribute('id')
        );

        $note = (new NoteRepository())->create($dto);

        $this->assertSame($folder->getAttribute('id'), $note->getAttribute('folder_id'));

        $this->assertDatabaseHas('notes', [
            'id' => $note->getAttribute('id'),
            'folder_id' => $folder->getAttribute('id'),
        ]);
    }

    public function testAllReturnsTopLevelNotesWhenNoFolderIdGiven(): void
    {
        $repository = new NoteRepository();

        $repository->create(new NoteCreateDto('Standup notes', 'Discussed roadmap for Q3', CategoryEnum::OFFICE()));
        $repository->create(new NoteCreateDto('Grocery list', 'Milk, eggs, bread', CategoryEnum::PERSONAL()));

        $notes = $repository->all();

        $this->assertCount(2, $notes);
        $this->assertSame(['Standup notes', 'Grocery list'], $notes->pluck('title')->all());
    }

    public function testAllReturnsOnlyNotesUnderTheGivenFolderId(): void
    {
        $folder = (new FolderRepository())->create(new FolderCreateDto('Work', CategoryEnum::OFFICE()));

        $repository = new NoteRepository();
        $inFolder = $repository->create(new NoteCreateDto('Standup notes', 'Discussed roadmap for Q3', CategoryEnum::OFFICE(), $folder->getAttribute('id')));
        $repository->create(new NoteCreateDto('Grocery list', 'Milk, eggs, bread', CategoryEnum::PERSONAL()));

        $notes = $repository->all($folder->getAttribute('id'));

        $this->assertCount(1, $notes);
        $this->assertSame($inFolder->getAttribute('id'), $notes->first()->getAttribute('id'));
    }

    public function testAllReturnsAnEmptyCollectionWhenNoNotesExist(): void
    {
        $notes = (new NoteRepository())->all();

        $this->assertCount(0, $notes);
    }
}
