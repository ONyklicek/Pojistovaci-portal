<?php
/**
 * Class Application
 *
 * @autor   Ondrej Nyklicek <ondrejnykicek@icloud.com>
 * @project Portal
 * @IDE     PhpStorm
 */

namespace App\Core\Enums;

enum eFormPattern: string
{
    const PATTERN_EMAIL = '[a-z0-9._-]+@[a-z0-9.-]+\.[a-z]{2,4}$';
}
