<?php

namespace Ez\Ui\Tests;

use Ez\Ui\ArrayList as UiArrayList;

/**
 * Class ArrayListTest
 *
 * @package Ez\Ui\Tests
 * @author Derek Li
 */
class ArrayListTest extends \PHPUnit_Framework_TestCase
{
    public function testArrayListCreation()
    {
        $array = array(
            'a' => 'this is a',
            'b' => array(
                'ba' => 'this is ba',
                'bb' => array(
                    'bba' => 'this is bba',
                ),
                'bc'
            ),
            'c'
        );
        $uiArrayList = new UiArrayList($array);
        print_r($uiArrayList.PHP_EOL);
        $this->assertInstanceOf('\Ez\Ui\ArrayList', $uiArrayList, 'new UiArrayList() should return an instance of \Ez\Ui\ArrayList');
    }
}

