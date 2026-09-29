<?php
declare(strict_types=1);

namespace App\Http\Controllers\CommandFolder;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\CommandFolder\CommandFolderRepositoryInterface;
use Illuminate\Http\Response;

class CommandFolderDeleteController extends Controller
{
    public function __construct(
        private readonly CommandFolderRepositoryInterface $commandFolderRepository
    ) {
    }

    public function __invoke(string $commandFolder): Response
    {
        $this->commandFolderRepository->delete($this->organizationId(), $commandFolder);

        return response()->noContent();
    }
}
