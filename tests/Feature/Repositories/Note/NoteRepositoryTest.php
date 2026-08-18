<?php
declare(strict_types=1);

namespace Tests\Feature\Repositories\Note;

use App\Constants\CategoryEnum;
use App\DataTransferObjects\Folder\FolderCreateDto;
use App\DataTransferObjects\Note\NoteCreateDto;
use App\DataTransferObjects\Note\NoteUpdateDto;
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

    public function testAllFiltersByCategoryWhenGiven(): void
    {
        $repository = new NoteRepository();

        $office = $repository->create(new NoteCreateDto('Standup notes', 'Discussed roadmap for Q3', CategoryEnum::OFFICE()));
        $repository->create(new NoteCreateDto('Grocery list', 'Milk, eggs, bread', CategoryEnum::PERSONAL()));

        $notes = $repository->all(null, 'office');

        $this->assertCount(1, $notes);
        $this->assertSame($office->getAttribute('id'), $notes->first()->getAttribute('id'));
    }

    public function testFindReturnsTheMatchingNote(): void
    {
        $repository = new NoteRepository();

        $note = $repository->create(new NoteCreateDto('Standup notes', 'Discussed roadmap for Q3', CategoryEnum::OFFICE()));

        $found = $repository->find($note->getAttribute('id'));

        $this->assertSame($note->getAttribute('id'), $found->getAttribute('id'));
        $this->assertSame('Standup notes', $found->getAttribute('title'));
    }

    public function testFindThrowsWhenTheNoteDoesNotExist(): void
    {
        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);

        (new NoteRepository())->find('00000000-0000-0000-0000-000000000000');
    }

    public function testUpdatePersistsTheNewTitleAndContent(): void
    {
        $repository = new NoteRepository();

        $note = $repository->create(new NoteCreateDto('Standup notes', 'Discussed roadmap for Q3', CategoryEnum::OFFICE()));

        $updated = $repository->update($note->getAttribute('id'), new NoteUpdateDto('Standup notes (revised)', 'Discussed roadmap for Q4'));

        $this->assertSame('Standup notes (revised)', $updated->getAttribute('title'));
        $this->assertSame('Discussed roadmap for Q4', $updated->getAttribute('content'));

        $this->assertDatabaseHas('notes', [
            'id' => $note->getAttribute('id'),
            'title' => 'Standup notes (revised)',
            'content' => 'Discussed roadmap for Q4',
        ]);
    }

    public function testUpdateThrowsWhenTheNoteDoesNotExist(): void
    {
        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);

        (new NoteRepository())->update('00000000-0000-0000-0000-000000000000', new NoteUpdateDto('Title', 'Content'));
    }

    public function testDeleteRemovesTheNote(): void
    {
        $repository = new NoteRepository();

        $note = $repository->create(new NoteCreateDto('Standup notes', 'Discussed roadmap for Q3', CategoryEnum::OFFICE()));

        $repository->delete($note->getAttribute('id'));

        $this->assertDatabaseMissing('notes', [
            'id' => $note->getAttribute('id'),
        ]);
    }

    public function testDeleteThrowsWhenTheNoteDoesNotExist(): void
    {
        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);

        (new NoteRepository())->delete('00000000-0000-0000-0000-000000000000');
    }
}
