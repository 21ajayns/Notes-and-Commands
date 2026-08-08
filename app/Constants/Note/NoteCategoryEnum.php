<?php
declare(strict_types=1);

namespace App\Constants\Note;

use MyCLabs\Enum\Enum;

/**
 * @method static \App\Constants\Note\NoteCategoryEnum OFFICE()
 * @method static \App\Constants\Note\NoteCategoryEnum PERSONAL()
 */
class NoteCategoryEnum extends Enum
{
    private const OFFICE = 'office';

    private const PERSONAL = 'personal';
}
