<?php
declare(strict_types=1);

namespace Tests\Feature\Http\Folder;

use App\Constants\CategoryEnum;
use App\DataTransferObjects\Folder\FolderCreateDto;
use App\Repositories\Folder\FolderRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FolderUpdateControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testItUpdatesTheFolderName(): void
    {
        $folder = (new FolderRepository())->create(new FolderCreateDto('Work', CategoryEnum::OFFICE()));

        $response = $this->putJson("/api/folders/{$folder->getAttribute('id')}", [
            'name' => 'Work (renamed)',
        ]);

        $response->assertOk();
        $response->assertJsonPath('name', 'Work (renamed)');

        $this->assertDatabaseHas('folders', [
            'id' => $folder->getAttribute('id'),
            'name' => 'Work (renamed)',
        ]);
    }

    public function testItRejectsAMissingName(): void
    {
        $folder = (new FolderRepository())->create(new FolderCreateDto('Work', CategoryEnum::OFFICE()));

        $response = $this->putJson("/api/folders/{$folder->getAttribute('id')}", []);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors(['name']);
    }

    public function testItReturnsNotFoundForANonExistentFolder(): void
    {
        $response = $this->putJson('/api/folders/00000000-0000-0000-0000-000000000000', [
            'name' => 'Name',
        ]);

        $response->assertNotFound();
    }
}
