<?php

namespace TDD\Test;

require dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php';
require './src/Formatter.php';

use PHPUnit\Framework\TestCase;

use TDD\Formatter;

class FormatterTest extends TestCase {

    public function setUp() : void {
        $this->formatter = new Formatter();
    }

    public function tearDown() : void {
        unset($this->formatter);
    }

    /**
     * @dataProvider provideCurrencyAmount 
    */
    public function testCurrencyAmount($amount, $expected, $msg) {
        $output = $this->formatter->currencyAmount($amount);
        $this->assertSame($expected, $output, $msg);
    }

    public function provideCurrencyAmount() {
        return [
            [1, 1.00, '1 should be transformed to 1.00'],
            [1.11, 1.11, '1 should be transformed to 1.00'],
            [1.111, 1.11, '1 should be transformed to 1.00'],
            [1.12, 1.12, '1 should be transformed to 1.00'],
        ];
    }
}