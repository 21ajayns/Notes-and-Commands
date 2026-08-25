<?php
declare(strict_types=1);

namespace App\Http\Controllers\Command;

use App\Constants\CategoryEnum;
use App\DataTransferObjects\Command\CommandCreateDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Command\CommandCreateRequest;
use App\Repositories\Interfaces\Command\CommandRepositoryInterface;
use Illuminate\Http\JsonResponse;

class CommandCreateController extends Controller
{
    public function __construct(
        private readonly CommandRepositoryInterface $commandRepository
    ) {
    }

    public function __invoke(CommandCreateRequest $request): JsonResponse
    {
        $dto = new CommandCreateDto(
            $request->validated('title'),
            $request->validated('rows'),
            new CategoryEnum($request->validated('category')),
            $request->validated('folder_id')
        );

        $command = $this->commandRepository->create($dto);

        return response()->json($command, 201);
    }
}
