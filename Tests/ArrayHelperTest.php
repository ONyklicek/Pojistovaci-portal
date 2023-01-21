<?php
/**
 * Class Application
 *
 * @autor   Ondrej Nyklicek <ondrejnykicek@icloud.com>
 * @project www
 * @IDE     PhpStorm
 */

namespace App\Core;

use App\Core\ArrayHelper;
use PHPUnit\Framework\TestCase;

class ArrayHelperTest extends TestCase
{
    public function testArrayPrefixed()
    {
        $arrFinal = ['test' => ['a', 'b', 'c']];
        $arr = ['a', 'b', 'c'];

        $this->assertEquals($arrFinal, ArrayHelper::arrayPrefixed('test', $arr));
    }


    public function testArrayPrefixed2()
    {
        $arrFinal = ['test' => ['a' => ['a', '1'], 'b', 'c']];
        $arr = ['a' => ['a', '1'], 'b', 'c'];


        $this->assertEquals($arrFinal, ArrayHelper::arrayPrefixed('test', $arr));
    }
}
