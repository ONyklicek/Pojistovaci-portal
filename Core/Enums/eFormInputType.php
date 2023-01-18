<?php
/**
 * Class Application
 *
 * @autor   Ondrej Nyklicek <ondrejnykicek@icloud.com>
 * @project Portal
 * @IDE     PhpStorm
 */

namespace App\Core\Enums;

enum eFormInputType: string
{
    const INPUT_TYPE_BUTTON = 'button';
    const INPUT_TYPE_CHECKBOX = 'checkbox';
    const INPUT_TYPE_COLOR = 'color';
    const INPUT_TYPE_DATE = 'date';
    const INPUT_TYPE_DATETIME_LOCAL = 'datetime-local';
    const INPUT_TYPE_EMAIL = 'email';
    const INPUT_TYPE_FILE = 'file';
    const INPUT_TYPE_HIDDEN = 'hiden';
    const INPUT_TYPE_IMAGE = 'image';
    const INPUT_TYPE_MONTH = 'month';
    const INPUT_TYPE_NUMBER = 'number';
    const INPUT_TYPE_PASSWORD = 'password';
    const INPUT_TYPE_RADIO = 'radio';
    const INPUT_TYPE_RANGE = 'range';
    const INPUT_TYPE_RESET = 'reset';
    const INPUT_TYPE_SEARCH = 'search';
    const INPUT_TYPE_SUBMIT = 'submit';
    const INPUT_TYPE_TEL = 'tel';
    const INPUT_TYPE_TEXT = 'text';
    const INPUT_TYPE_TIME = 'time';
    const INPUT_TYPE_URL = 'url';
    const INPUT_TYPE_WEEK = 'week';

    public static function getAllValues(): array
    {
        return array_column(eFormInputType::cases(), 'value');
    }

}
