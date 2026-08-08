<?php
declare(strict_types=1);

namespace App\Models\Note;

use App\AbstractModel;

/**
 * @mixin \Illuminate\Database\Eloquent\Builder
 *
 * @property string $title
 * @property string $content
 * @property string $category
 */
final class Note extends AbstractModel
{
    protected $table = 'notes';

    protected $fillable = [
        'title',
        'content',
        'category',
    ];

    public function toArray(): array
    {
        return $this->serialise([
            'id' => $this->getAttribute('id'),
            'title' => $this->getAttribute('title'),
            'content' => $this->getAttribute('content'),
            'category' => $this->getAttribute('category'),
        ]);
    }
}
