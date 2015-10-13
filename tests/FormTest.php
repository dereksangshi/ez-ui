<?php

namespace Ez\Ui\Tests;

use Ez\Ui\Form as UiForm;

/**
 * Class FormTest
 *
 * @package Ez\Ui\Tests
 * @author Derek Li
 */
class FormTest extends \PHPUnit_Framework_TestCase
{
    public function testFormCreation()
    {
        $uiForm = new UiForm();
        $uiForm
            ->attr('id', 'login')
            ->addPair(function (UiForm\Pair $pair) {
                $pair
                    ->addLabel('User Name')
                    ->addInput('text', 'username', 'derek');
            })
            ->addPair(function (UiForm\Pair $pair) {
                $pair
                    ->addLabel('Password')
                    ->addInput('password', 'password', 'balance08');
            })
            ->addPair(function (UiForm\Pair $pair) {
                $pair
                    ->addLabel('Country')
                    ->addSelect(
                        'country',
                        function ($select) {
                            $select
                                ->addOption('AU', 'Australia')
                                ->addOption('CHN', 'China', true)
                                ->addOption('NZ', 'New Zealand');
                        }
                    );
            })
            ->addResetButton()
            ->addSubmitButton();
        $this->assertInstanceOf('\Ez\Ui\Form', $uiForm, 'new UiForm() should return an instance of \Ez\Ui\Form');
    }
}

