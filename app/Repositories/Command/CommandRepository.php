<?php
declare(strict_types=1);

namespace App\Repositories\Command;

use App\DataTransferObjects\Command\CommandCreateDto;
use App\DataTransferObjects\Command\CommandUpdateDto;
use App\Models\Command\Command;
use App\Repositories\Interfaces\Command\CommandRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class CommandRepository implements CommandRepositoryInterface
{
    public function create(CommandCreateDto $createDto): Command
    {
        $command = new Command();
        $command->setAttribute('title', $createDto->getTitle());
        $command->setAttribute('rows', $createDto->getRows());
        $command->setAttribute('category', $createDto->getCategory()->getValue());
        $command->setAttribute('command_folder_id', $createDto->getFolderId());

        $command->save();

        return $command;
    }

    public function all(?string $folderId = null, ?string $category = null): Collection
    {
        return Command::query()
            ->where('command_folder_id', $folderId)
            ->when($category !== null, fn ($query) => $query->where('category', $category))
            ->get();
    }

    public function find(string $id): Command
    {
        return Command::query()->findOrFail($id);
    }

    public function update(string $id, CommandUpdateDto $updateDto): Command
    {
        $command = Command::query()->findOrFail($id);

        $command->setAttribute('title', $updateDto->getTitle());
        $command->setAttribute('rows', $updateDto->getRows());

        $command->save();

        return $command;
    }

    public function delete(string $id): void
    {
        Command::query()->findOrFail($id)->delete();
    }
}
