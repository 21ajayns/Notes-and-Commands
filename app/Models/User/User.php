<?php
declare(strict_types=1);

namespace App\Models\User;

use App\Constants\UtcDateTimeInterface;
use App\Models\Organization\Organization;
use Database\Factories\UserFactory;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * Extends Laravel's Authenticatable rather than AbstractModel so it can log in,
 * so the uuid key settings from AbstractModel are repeated here.
 *
 * @mixin \Illuminate\Database\Eloquent\Builder
 *
 * @property string $organization_id
 * @property string $name
 * @property string $email
 */
final class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use Uuid;

    protected $table = 'users';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'organization_id',
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function getId(): string
    {
        return $this->getAttribute('id');
    }

    public function getOrganizationId(): string
    {
        return $this->getAttribute('organization_id');
    }

    public function toArray(): array
    {
        $data = [
            'id' => $this->getAttribute('id'),
            'organization_id' => $this->getAttribute('organization_id'),
            'name' => $this->getAttribute('name'),
            'email' => $this->getAttribute('email'),
            'created_at' => $this->getAttribute('created_at')?->format(UtcDateTimeInterface::FORMAT_ZULU),
            'updated_at' => $this->getAttribute('updated_at')?->format(UtcDateTimeInterface::FORMAT_ZULU),
        ];

        \ksort($data);

        return $data;
    }

    protected static function newFactory(): UserFactory
    {
        return UserFactory::new();
    }
}
