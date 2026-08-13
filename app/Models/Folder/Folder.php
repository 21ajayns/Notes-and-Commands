<?php
declare(strict_types=1);

namespace App\Models\Folder;

use App\AbstractModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin \Illuminate\Database\Eloquent\Builder
 *
 * @property string $name
 * @property string $category
 * @property string|null $folder_id
 */
final class Folder extends AbstractModel
{
    protected $table = 'folders';

    protected $fillable = [
        'name',
        'category',
        'folder_id',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'folder_id');
    }

    public function toArray(): array
    {
        return $this->serialise([
            'id' => $this->getAttribute('id'),
            'name' => $this->getAttribute('name'),
            'category' => $this->getAttribute('category'),
            'folder_id' => $this->getAttribute('folder_id'),
        ]);
    }
}
