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
    case PERM_ADMIN = 'Administrátor';
    case RULE_EMAIL = 'Emailová adresa není platná';
}

