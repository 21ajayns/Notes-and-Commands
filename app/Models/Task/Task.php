<?php
declare(strict_types=1);

namespace App\Models\Task;

use App\AbstractModel;

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

    public function toArray(): array
    {
        return $this->serialise([
            'id' => $this->getAttribute('id'),
            'title' => $this->getAttribute('title'),
            'category' => $this->getAttribute('category'),
            'status' => $this->getAttribute('status'),
        ]);
    }
}
