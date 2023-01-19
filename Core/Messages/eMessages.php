<?php
/**
 * Class Application
 *
 * @autor   Ondrej Nyklicek <ondrejnykicek@icloud.com>
 * @project Portal
 * @IDE     PhpStorm
 */

namespace App\Core\Messages;

enum eUserPerm: string
{
    case INVALID_EMAIL = 'Emailová adresa není platná';
}

