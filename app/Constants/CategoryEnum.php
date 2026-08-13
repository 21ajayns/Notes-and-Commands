<?php
declare(strict_types=1);

namespace App\Constants;

use MyCLabs\Enum\Enum;

/**
 * @method static \App\Constants\CategoryEnum OFFICE()
 * @method static \App\Constants\CategoryEnum PERSONAL()
 */
class CategoryEnum extends Enum
{
    private const OFFICE = 'office';

    private const PERSONAL = 'personal';
}
