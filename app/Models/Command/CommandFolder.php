<?php
declare(strict_types=1);

namespace App\Models\Command;

use App\AbstractModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin \Illuminate\Database\Eloquent\Builder
 *
 * @property string $name
 * @property string $category
 * @property string|null $command_folder_id
 */
final class CommandFolder extends AbstractModel
{
    protected $table = 'command_folders';

    protected $fillable = [
        'name',
        'category',
        'command_folder_id',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'command_folder_id');
    }

    public function toArray(): array
    {
        return $this->serialise([
            'id' => $this->getAttribute('id'),
            'name' => $this->getAttribute('name'),
            'category' => $this->getAttribute('category'),
            'folder_id' => $this->getAttribute('command_folder_id'),
        ]);
    }
}
