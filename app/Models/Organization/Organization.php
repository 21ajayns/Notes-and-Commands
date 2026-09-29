<?php
declare(strict_types=1);

namespace App\Models\Organization;

use App\AbstractModel;
use App\Models\Command\Command;
use App\Models\Command\CommandFolder;
use App\Models\Folder\Folder;
use App\Models\Note\Note;
use App\Models\Task\Task;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin \Illuminate\Database\Eloquent\Builder
 *
 * @property string $name
 */
final class Organization extends AbstractModel
{
    protected $table = 'organizations';

    protected $fillable = [
        'name',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function folders(): HasMany
    {
        return $this->hasMany(Folder::class);
    }

    public function notes(): HasMany
    {
        return $this->hasMany(Note::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function commandFolders(): HasMany
    {
        return $this->hasMany(CommandFolder::class);
    }

    public function commands(): HasMany
    {
        return $this->hasMany(Command::class);
    }

    public function toArray(): array
    {
        return $this->serialise([
            'id' => $this->getAttribute('id'),
            'name' => $this->getAttribute('name'),
        ]);
    }
}
