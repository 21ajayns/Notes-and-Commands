<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Illuminate\Database\Eloquent\Builder
 *
 * @property string $title
 * @property string $body
 * @property string $category
 */
class Note extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'body', 'category'];
}
