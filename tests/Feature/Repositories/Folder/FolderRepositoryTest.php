<?php
declare(strict_types=1);

namespace Tests\Feature\Repositories\Folder;

use App\Constants\CategoryEnum;
use App\DataTransferObjects\Folder\FolderCreateDto;
use App\Repositories\Folder\FolderRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FolderRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function testCreatePersistsATopLevelFolder(): void
    {
        $dto = new FolderCreateDto('Work', CategoryEnum::OFFICE());

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
        $dto = new FolderCreateDto('Personal', CategoryEnum::PERSONAL());

        $folder = (new FolderRepository())->create($dto);

        $this->assertSame('personal', $folder->getAttribute('category'));

        $this->assertDatabaseHas('folders', [
            'id' => $folder->getAttribute('id'),
            'category' => 'personal',
        ]);
    }

    public function testCreatePersistsANestedFolderWithParent(): void
    {
        $parent = (new FolderRepository())->create(new FolderCreateDto('Work', CategoryEnum::OFFICE()));

        $dto = new FolderCreateDto('Projects', CategoryEnum::OFFICE(), $parent->getAttribute('id'));

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

        $repository->create(new FolderCreateDto('Work', CategoryEnum::OFFICE()));
        $repository->create(new FolderCreateDto('Personal', CategoryEnum::PERSONAL()));

        $folders = $repository->all();

        $this->assertCount(2, $folders);
        $this->assertSame(['Work', 'Personal'], $folders->pluck('name')->all());
    }

    public function testAllReturnsOnlyFoldersUnderTheGivenFolderId(): void
    {
        $repository = new FolderRepository();

        $parent = $repository->create(new FolderCreateDto('Work', CategoryEnum::OFFICE()));
        $child = $repository->create(new FolderCreateDto('Projects', CategoryEnum::OFFICE(), $parent->getAttribute('id')));
        $repository->create(new FolderCreateDto('Personal', CategoryEnum::PERSONAL()));

        $folders = $repository->all($parent->getAttribute('id'));

        $this->assertCount(1, $folders);
        $this->assertSame($child->getAttribute('id'), $folders->first()->getAttribute('id'));
    }

    public function testAllReturnsAnEmptyCollectionWhenNoFoldersExist(): void
    {
        $folders = (new FolderRepository())->all();

        $this->assertCount(0, $folders);
    }

    public function testAllFiltersByCategoryWhenGiven(): void
    {
        $repository = new FolderRepository();

        $work = $repository->create(new FolderCreateDto('Work', CategoryEnum::OFFICE()));
        $repository->create(new FolderCreateDto('Personal', CategoryEnum::PERSONAL()));

        $folders = $repository->all(null, 'office');

        $this->assertCount(1, $folders);
        $this->assertSame($work->getAttribute('id'), $folders->first()->getAttribute('id'));
    }
}
