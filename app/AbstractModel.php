<?php
declare(strict_types=1);

namespace App;

use App\Constants\UtcDateTimeInterface;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Model;
use DateTime;

/**
 * Abstract model that extends eloquent model.
 */
abstract class AbstractModel extends Model
{
    use Uuid;

    /**
     * Type of the primary id.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * As using uuid it should not auto increment.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * Format date time to zulu format
     *
     * @param \DateTime|null|string $dateTime
     *
     * @return string|null
     */
    protected function formatDate($dateTime, ?string $format = null): ?string
    {
        if (\is_string($dateTime) === true) {
            try {
                $dateTime = new DateTime($dateTime);
            } catch (\Exception $exception) {
                return null;
            }
        }

        $format = $format ?? UtcDateTimeInterface::FORMAT_ZULU;

        return $dateTime === null ? null : $dateTime->format($format);
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->getAttribute('id');
    }

    /**
     * Model toArray() call this parent method.
     *
     * @param mixed[] $data Data arrays to merge and sort
     *
     * @return mixed[]
     */
    protected function serialise(array $data): array
    {
        $buildArray = \array_merge($data, [
            'created_at' => $this->formatDate($this->getAttribute('created_at')),
            'updated_at' => $this->formatDate($this->getAttribute('updated_at'))
        ]);

        \ksort($buildArray);

        return $buildArray;
    }

    /**
     * Format date time to zulu format
     *
     * @param \DateTime|null|string $dateTime
     *
     * @return string|null
     */
    public function convertDate($dateTime): ?string
    {
        if (\is_string($dateTime) === true) {
            $dateTime = new DateTime($dateTime);
        }

        return $dateTime === null ? null : $dateTime->format('Y-m-d');
    }
}
