<?php
declare(strict_types=1);

namespace App\Http\Controllers\Folder;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\Folder\FolderRepositoryInterface;
use Illuminate\Http\Response;

class FolderDeleteController extends Controller
{
    public function __construct(
        private readonly FolderRepositoryInterface $folderRepository
    ) {
    }

    public function __invoke(string $folder): Response
    {
        $this->folderRepository->delete($folder);

        return response()->noContent();
    }
}
