<?php
declare(strict_types=1);

namespace App\Models\Command;

use App\AbstractModel;

/**
 * @mixin \Illuminate\Database\Eloquent\Builder
 *
 * @property string $title
 * @property mixed[] $rows
 * @property string $category
 * @property string|null $command_folder_id
 */
final class Command extends AbstractModel
{
    protected $table = 'commands';

    protected $fillable = [
        'title',
        'rows',
        'category',
        'command_folder_id',
    ];

    protected $casts = [
        'rows' => 'array',
    ];

    public function toArray(): array
    {
        return $this->serialise([
            'id' => $this->getAttribute('id'),
            'title' => $this->getAttribute('title'),
            'rows' => $this->getAttribute('rows'),
            'category' => $this->getAttribute('category'),
            'folder_id' => $this->getAttribute('command_folder_id'),
        ]);
    }
}
