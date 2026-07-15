<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin \Illuminate\Database\Eloquent\Builder
 *
 * @property string $title
 * @property string $body
 * @property string $category
 * @property int|null $folder_id
 */
class Note extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'body', 'category', 'folder_id'];

    public function folder(): BelongsTo
    {
        return $this->belongsTo(Folder::class);
    }
}
