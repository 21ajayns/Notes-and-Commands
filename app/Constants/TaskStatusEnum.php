<?php
declare(strict_types=1);

namespace App\Constants;

use MyCLabs\Enum\Enum;

/**
 * @method static \App\Constants\TaskStatusEnum ACTIVE()
 * @method static \App\Constants\TaskStatusEnum UPCOMING()
 * @method static \App\Constants\TaskStatusEnum COMPLETED()
 */
class TaskStatusEnum extends Enum
{
    private const ACTIVE = 'active';

    private const UPCOMING = 'upcoming';

    private const COMPLETED = 'completed';
}
