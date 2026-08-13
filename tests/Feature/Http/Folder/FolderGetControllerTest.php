<?php
declare(strict_types=1);

namespace Tests\Feature\Http\Folder;

use App\Constants\CategoryEnum;
use App\DataTransferObjects\Folder\FolderCreateDto;
use App\Repositories\Folder\FolderRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FolderGetControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testItReturnsTopLevelFoldersWhenNoFolderIdGiven(): void
    {
        $repository = new FolderRepository();
        $repository->create(new FolderCreateDto('Work', CategoryEnum::OFFICE()));
        $repository->create(new FolderCreateDto('Personal', CategoryEnum::PERSONAL()));

        $response = $this->getJson('/api/folders');

        $response->assertOk();
        $response->assertJsonCount(2);
    }

    public function testItReturnsOnlyFoldersUnderTheGivenFolderId(): void
    {
        $repository = new FolderRepository();
        $parent = $repository->create(new FolderCreateDto('Work', CategoryEnum::OFFICE()));
        $child = $repository->create(new FolderCreateDto('Projects', CategoryEnum::OFFICE(), $parent->getAttribute('id')));
        $repository->create(new FolderCreateDto('Personal', CategoryEnum::PERSONAL()));

        $response = $this->getJson('/api/folders?folder_id=' . $parent->getAttribute('id'));

        $response->assertOk();
        $response->assertJsonCount(1);
        $response->assertJsonPath('0.id', $child->getAttribute('id'));
    }

    public function testItRejectsANonExistentFolderId(): void
    {
        $response = $this->getJson('/api/folders?folder_id=00000000-0000-0000-0000-000000000000');

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors(['folder_id']);
    }
}
