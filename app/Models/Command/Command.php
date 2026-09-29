<?php
declare(strict_types=1);

namespace App\Models\Command;

use App\AbstractModel;
use App\Models\Organization\Organization;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function toArray(): array
    {
        return $this->serialise([
            'id' => $this->getAttribute('id'),
            'organization_id' => $this->getAttribute('organization_id'),
            'title' => $this->getAttribute('title'),
            'rows' => $this->getAttribute('rows'),
            'category' => $this->getAttribute('category'),
            'folder_id' => $this->getAttribute('command_folder_id'),
        ]);
    }
}
