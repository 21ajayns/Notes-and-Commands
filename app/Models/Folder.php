<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin \Illuminate\Database\Eloquent\Builder
 *
 * @property string $name
 * @property string|null $image
 */
class Folder extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'image'];

    public function notes(): HasMany
    {
        return $this->hasMany(Note::class);
    }
}
