<?php
declare(strict_types=1);

namespace Tests\Feature\Repositories\Folder;

use App\DataTransferObjects\Folder\FolderCreateDto;
use App\Repositories\Folder\FolderRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FolderRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function testCreatePersistsATopLevelFolder(): void
    {
        $dto = new FolderCreateDto('Work');

        $folder = (new FolderRepository())->create($dto);

        $this->assertNull($folder->getAttribute('folder_id'));

        $this->assertDatabaseHas('folders', [
            'id' => $folder->getAttribute('id'),
            'name' => 'Work',
            'folder_id' => null,
        ]);
    }

    public function testCreatePersistsANestedFolderWithParent(): void
    {
        $parent = (new FolderRepository())->create(new FolderCreateDto('Work'));

        $dto = new FolderCreateDto('Projects', $parent->getAttribute('id'));

        $child = (new FolderRepository())->create($dto);

        $this->assertSame($parent->getAttribute('id'), $child->getAttribute('folder_id'));

        $this->assertDatabaseHas('folders', [
            'id' => $child->getAttribute('id'),
            'name' => 'Projects',
            'folder_id' => $parent->getAttribute('id'),
        ]);

        $this->assertSame($parent->getAttribute('id'), $child->parent->getAttribute('id'));
    }
}