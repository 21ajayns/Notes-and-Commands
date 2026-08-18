<?php
declare(strict_types=1);

namespace Tests\Feature\Http\Folder;

use App\Constants\CategoryEnum;
use App\DataTransferObjects\Folder\FolderCreateDto;
use App\Repositories\Folder\FolderRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FolderDeleteControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testItDeletesTheFolder(): void
    {
        $folder = (new FolderRepository())->create(new FolderCreateDto('Work', CategoryEnum::OFFICE()));

        $response = $this->deleteJson("/api/folders/{$folder->getAttribute('id')}");

        $response->assertNoContent();

        $this->assertDatabaseMissing('folders', [
            'id' => $folder->getAttribute('id'),
        ]);
    }

    public function testItReturnsNotFoundForANonExistentFolder(): void
    {
        $response = $this->deleteJson('/api/folders/00000000-0000-0000-0000-000000000000');

        $response->assertNotFound();
    }
}
