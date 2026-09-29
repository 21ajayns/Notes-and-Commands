<?php
declare(strict_types=1);

namespace App\Repositories\Command;

use App\DataTransferObjects\Command\CommandCreateDto;
use App\DataTransferObjects\Command\CommandUpdateDto;
use App\Models\Command\Command;
use App\Repositories\Interfaces\Command\CommandRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class CommandRepository implements CommandRepositoryInterface
{
    public function create(CommandCreateDto $createDto): Command
    {
        $command = new Command();
        $command->setAttribute('organization_id', $createDto->getOrganizationId());
        $command->setAttribute('title', $createDto->getTitle());
        $command->setAttribute('rows', $createDto->getRows());
        $command->setAttribute('category', $createDto->getCategory()->getValue());
        $command->setAttribute('command_folder_id', $createDto->getFolderId());

        $command->save();

        return $command;
    }

    public function all(string $organizationId, ?string $folderId = null, ?string $category = null): Collection
    {
        return $this->forOrganization($organizationId)
            ->where('command_folder_id', $folderId)
            ->when($category !== null, fn ($query) => $query->where('category', $category))
            ->get();
    }

    public function find(string $organizationId, string $id): Command
    {
        return $this->forOrganization($organizationId)->findOrFail($id);
    }

    public function update(string $organizationId, string $id, CommandUpdateDto $updateDto): Command
    {
        $command = $this->forOrganization($organizationId)->findOrFail($id);

        $command->setAttribute('title', $updateDto->getTitle());
        $command->setAttribute('rows', $updateDto->getRows());

        $command->save();

        return $command;
    }

    public function delete(string $organizationId, string $id): void
    {
        $this->forOrganization($organizationId)->findOrFail($id)->delete();
    }

    private function forOrganization(string $organizationId): Builder
    {
        return Command::query()->where('organization_id', $organizationId);
    }
}
