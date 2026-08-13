<?php
declare(strict_types=1);

namespace Tests\Feature\Http\Folder;

use App\Repositories\Folder\FolderRepository;
use App\DataTransferObjects\Folder\FolderCreateDto;
use App\Constants\CategoryEnum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FolderCreateControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testItCreatesATopLevelFolder(): void
    {
        $response = $this->postJson('/api/folders', [
            'name' => 'Work',
            'category' => 'office',
        ]);

        $response->assertCreated();
        $response->assertJsonPath('name', 'Work');
        $response->assertJsonPath('category', 'office');
        $response->assertJsonPath('folder_id', null);

        $this->assertDatabaseHas('folders', [
            'name' => 'Work',
            'category' => 'office',
            'folder_id' => null,
        ]);
    }

    public function testItCreatesANestedFolder(): void
    {
        $parent = (new FolderRepository())->create(new FolderCreateDto('Work', CategoryEnum::OFFICE()));

        $response = $this->postJson('/api/folders', [
            'name' => 'Projects',
            'category' => 'office',
            'folder_id' => $parent->getAttribute('id'),
        ]);

        $response->assertCreated();
        $response->assertJsonPath('folder_id', $parent->getAttribute('id'));

        $this->assertDatabaseHas('folders', [
            'name' => 'Projects',
            'folder_id' => $parent->getAttribute('id'),
        ]);
    }

    public function testItRejectsAMissingName(): void
    {
        $response = $this->postJson('/api/folders', [
            'category' => 'office',
        ]);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors(['name']);
    }

    public function testItRejectsAnInvalidCategory(): void
    {
        $response = $this->postJson('/api/folders', [
            'name' => 'Work',
            'category' => 'not-a-real-category',
        ]);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors(['category']);
    }

    public function testItRejectsANonExistentParentFolder(): void
    {
        $response = $this->postJson('/api/folders', [
            'name' => 'Projects',
            'category' => 'office',
            'folder_id' => '00000000-0000-0000-0000-000000000000',
        ]);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors(['folder_id']);
    }
}