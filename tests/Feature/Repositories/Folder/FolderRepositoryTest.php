<?php
declare(strict_types=1);

namespace Tests\Feature\Repositories\Folder;

use App\Constants\CategoryEnum;
use App\DataTransferObjects\Folder\FolderCreateDto;
use App\DataTransferObjects\Folder\FolderUpdateDto;
use App\DataTransferObjects\Note\NoteCreateDto;
use App\Repositories\Folder\FolderRepository;
use App\Repositories\Note\NoteRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FolderRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function testCreatePersistsATopLevelFolder(): void
    {
        $dto = new FolderCreateDto($this->organizationId(), 'Work', CategoryEnum::OFFICE());

        $folder = (new FolderRepository())->create($dto);

        $this->assertNull($folder->getAttribute('folder_id'));

        $this->assertDatabaseHas('folders', [
            'id' => $folder->getAttribute('id'),
            'name' => 'Work',
            'category' => 'office',
            'folder_id' => null,
        ]);
    }

    public function testCreatePersistsAFolderWithPersonalCategory(): void
    {
        $dto = new FolderCreateDto($this->organizationId(), 'Personal', CategoryEnum::PERSONAL());

        $folder = (new FolderRepository())->create($dto);

        $this->assertSame('personal', $folder->getAttribute('category'));

        $this->assertDatabaseHas('folders', [
            'id' => $folder->getAttribute('id'),
            'category' => 'personal',
        ]);
    }

    public function testCreatePersistsANestedFolderWithParent(): void
    {
        $parent = (new FolderRepository())->create(new FolderCreateDto($this->organizationId(), 'Work', CategoryEnum::OFFICE()));

        $dto = new FolderCreateDto($this->organizationId(), 'Projects', CategoryEnum::OFFICE(), $parent->getAttribute('id'));

        $child = (new FolderRepository())->create($dto);

        $this->assertSame($parent->getAttribute('id'), $child->getAttribute('folder_id'));

        $this->assertDatabaseHas('folders', [
            'id' => $child->getAttribute('id'),
            'name' => 'Projects',
            'folder_id' => $parent->getAttribute('id'),
        ]);

        $this->assertSame($parent->getAttribute('id'), $child->parent->getAttribute('id'));
    }

    public function testAllReturnsTopLevelFoldersWhenNoFolderIdGiven(): void
    {
        $repository = new FolderRepository();

        $repository->create(new FolderCreateDto($this->organizationId(), 'Work', CategoryEnum::OFFICE()));
        $repository->create(new FolderCreateDto($this->organizationId(), 'Personal', CategoryEnum::PERSONAL()));

        $folders = $repository->all($this->organizationId());

        $this->assertCount(2, $folders);
        $this->assertSame(['Work', 'Personal'], $folders->pluck('name')->all());
    }

    public function testAllReturnsOnlyFoldersUnderTheGivenFolderId(): void
    {
        $repository = new FolderRepository();

        $parent = $repository->create(new FolderCreateDto($this->organizationId(), 'Work', CategoryEnum::OFFICE()));
        $child = $repository->create(new FolderCreateDto($this->organizationId(), 'Projects', CategoryEnum::OFFICE(), $parent->getAttribute('id')));
        $repository->create(new FolderCreateDto($this->organizationId(), 'Personal', CategoryEnum::PERSONAL()));

        $folders = $repository->all($this->organizationId(), $parent->getAttribute('id'));

        $this->assertCount(1, $folders);
        $this->assertSame($child->getAttribute('id'), $folders->first()->getAttribute('id'));
    }

    public function testAllReturnsAnEmptyCollectionWhenNoFoldersExist(): void
    {
        $folders = (new FolderRepository())->all($this->organizationId());

        $this->assertCount(0, $folders);
    }

    public function testAllFiltersByCategoryWhenGiven(): void
    {
        $repository = new FolderRepository();

        $work = $repository->create(new FolderCreateDto($this->organizationId(), 'Work', CategoryEnum::OFFICE()));
        $repository->create(new FolderCreateDto($this->organizationId(), 'Personal', CategoryEnum::PERSONAL()));

        $folders = $repository->all($this->organizationId(), null, 'office');

        $this->assertCount(1, $folders);
        $this->assertSame($work->getAttribute('id'), $folders->first()->getAttribute('id'));
    }

    public function testUpdatePersistsTheNewName(): void
    {
        $repository = new FolderRepository();

        $folder = $repository->create(new FolderCreateDto($this->organizationId(), 'Work', CategoryEnum::OFFICE()));

        $updated = $repository->update($this->organizationId(), $folder->getAttribute('id'), new FolderUpdateDto('Work (renamed)'));

        $this->assertSame('Work (renamed)', $updated->getAttribute('name'));

        $this->assertDatabaseHas('folders', [
            'id' => $folder->getAttribute('id'),
            'name' => 'Work (renamed)',
        ]);
    }

    public function testUpdateThrowsWhenTheFolderDoesNotExist(): void
    {
        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);

        (new FolderRepository())->update($this->organizationId(), '00000000-0000-0000-0000-000000000000', new FolderUpdateDto('Name'));
    }

    public function testDeleteRemovesTheFolder(): void
    {
        $repository = new FolderRepository();

        $folder = $repository->create(new FolderCreateDto($this->organizationId(), 'Work', CategoryEnum::OFFICE()));

        $repository->delete($this->organizationId(), $folder->getAttribute('id'));

        $this->assertDatabaseMissing('folders', [
            'id' => $folder->getAttribute('id'),
        ]);
    }

    public function testDeleteThrowsWhenTheFolderDoesNotExist(): void
    {
        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);

        (new FolderRepository())->delete($this->organizationId(), '00000000-0000-0000-0000-000000000000');
    }

    public function testDeletingAFolderCascadesToItsChildFolders(): void
    {
        $repository = new FolderRepository();

        $parent = $repository->create(new FolderCreateDto($this->organizationId(), 'Work', CategoryEnum::OFFICE()));
        $child = $repository->create(new FolderCreateDto($this->organizationId(), 'Projects', CategoryEnum::OFFICE(), $parent->getAttribute('id')));
        $grandchild = $repository->create(new FolderCreateDto($this->organizationId(), 'Sprint 1', CategoryEnum::OFFICE(), $child->getAttribute('id')));

        $repository->delete($this->organizationId(), $parent->getAttribute('id'));

        $this->assertDatabaseMissing('folders', ['id' => $child->getAttribute('id')]);
        $this->assertDatabaseMissing('folders', ['id' => $grandchild->getAttribute('id')]);
    }

    public function testDeletingAFolderCascadesToNotesInsideIt(): void
    {
        $folderRepository = new FolderRepository();
        $noteRepository = new NoteRepository();

        $folder = $folderRepository->create(new FolderCreateDto($this->organizationId(), 'Work', CategoryEnum::OFFICE()));
        $note = $noteRepository->create(new NoteCreateDto($this->organizationId(), 'Standup notes', 'Discussed roadmap for Q3', CategoryEnum::OFFICE(), $folder->getAttribute('id')));

        $folderRepository->delete($this->organizationId(), $folder->getAttribute('id'));

        $this->assertDatabaseMissing('notes', ['id' => $note->getAttribute('id')]);
    }

    public function testDeletingAFolderCascadesToNotesInsideNestedChildFolders(): void
    {
        $folderRepository = new FolderRepository();
        $noteRepository = new NoteRepository();

        $parent = $folderRepository->create(new FolderCreateDto($this->organizationId(), 'Work', CategoryEnum::OFFICE()));
        $child = $folderRepository->create(new FolderCreateDto($this->organizationId(), 'Projects', CategoryEnum::OFFICE(), $parent->getAttribute('id')));
        $note = $noteRepository->create(new NoteCreateDto($this->organizationId(), 'Standup notes', 'Discussed roadmap for Q3', CategoryEnum::OFFICE(), $child->getAttribute('id')));

        $folderRepository->delete($this->organizationId(), $parent->getAttribute('id'));

        $this->assertDatabaseMissing('notes', ['id' => $note->getAttribute('id')]);
    }
}
