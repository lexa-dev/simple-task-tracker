<?php

namespace App\Enum;

use DateTimeImmutable;

enum TaskStatus: string
{
    case NEW = 'new';
    case IN_PROGRESS = 'in_progress';
    case DONE = 'done';
}