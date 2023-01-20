<?php
/**
 * Class Application
 *
 * @autor   Ondrej Nyklicek <ondrejnykicek@icloud.com>
 * @project Portal
 * @IDE     PhpStorm
 */

namespace App\Core\Messages;

enum eMsgUser: string
{
    //Auth message
    case MSG_LOGOUT = 'Byl jste úspěšně odhlášen.';
    case MSG_LOGIN = 'Byl jste úspěšně přihlášen.';
    case MSG_REGISTER = 'Byl jste úspěšně zaregistrován';

    //Error user

    case ERR_PASS_EMPTY = 'Heslo nesmí být prázdné.';
    case ERR_PASS_NOT_MATCH = 'Hesla nesouhlasí.';
    case ERR_INVALID_FORMAT_EMAIL = 'Neplatný formát emailové adresy';
    case ERR_INVALID_FORMAT_PHONE = 'Neplatný formát telefoního čísla.';


    case ERR_INVALID_EMAIL = 'Neplatná emailová adresa';
    case ERR_INVALID_LOGIN = 'Neplatný email, nebo telefoní číslo.';
    case ERR_INVALID_PASS = 'Neplatné heslo';

}

