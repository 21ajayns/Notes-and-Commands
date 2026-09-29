<?php
declare(strict_types=1);

namespace App\Models\Task;

use App\AbstractModel;
use App\Models\Organization\Organization;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin \Illuminate\Database\Eloquent\Builder
 *
 * @property string $title
 * @property string $category
 * @property string $status
 */
final class Task extends AbstractModel
{
    protected $table = 'tasks';

    protected $fillable = [
        'title',
        'category',
        'status',
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
            'category' => $this->getAttribute('category'),
            'status' => $this->getAttribute('status'),
        ]);
    }
}
