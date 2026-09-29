<?php
declare(strict_types=1);

namespace App\Models\Note;

use App\AbstractModel;
use App\Models\Organization\Organization;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin \Illuminate\Database\Eloquent\Builder
 *
 * @property string $title
 * @property string $content
 * @property string $category
 * @property string|null $folder_id
 */
final class Note extends AbstractModel
{
    protected $table = 'notes';

    protected $fillable = [
        'title',
        'content',
        'category',
        'folder_id',
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
            'content' => $this->getAttribute('content'),
            'category' => $this->getAttribute('category'),
            'folder_id' => $this->getAttribute('folder_id'),
        ]);
    }
}
