<?php

namespace TDD\Test;

require dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php';
require './src/Receipt.php';

use PHPUnit\Framework\TestCase;

use TDD\Receipt;

class ReceiptTest extends TestCase {
    public function setUp() : void {
        $this->formatter = $this->getMockBuilder('TDD\Formatter') //Decouple Code with TDD
            ->setMethods(['currencyAmount'])
            ->getMock();
        $this->formatter->expects($this->any())
            ->method('currencyAmount')
            ->with($this->anything())
            ->will($this->returnArgument(0));
        $this->receipt = new Receipt($this->formatter);
    }

    public function tearDown() : void {
        unset($this->receipt);
    }

    public function provideSubTotal() {
        return [
            'total24' =>[[1, 2, 3, 8, 10], 24], // TO run test => vendor/bin/phpunit --filter='testTotal@total24'
            [[1, 2, 3, 0], 6],
            [[1, 2], 3],
        ];
    }

    /**
     * Should be commented on the same format for the data provider
    */

    /**
     * @dataProvider provideSubTotal
    */
    public function testSubTotal($items, $expected) {
        $coupon = null;
        $output = $this->receipt->subTotal($items, $coupon);
        $this->assertEquals($expected, $output, "When summing the total should equal $expected");
    }

    //Dummy object
    public function testSubTotalWithCoupon() {
        $input = [0, 5, 8, 2];
        $coupon = 0.20;
        $output = $this->receipt->subTotal($input, $coupon);
        $this->assertEquals(12, $output, 'When summing the total should equal 13');
    }

    public function testTotalException() {
        $input = [0, 5, 8, 2];
        $coupon = 1.20;
        $this->expectException('BadMethodCallException');
        $this->receipt->subTotal($input, $coupon);
    }

    ///Stub
    public function testPostTaxTotal() {
        $receipt = $this->getMockBuilder('TDD\Receipt')
                    ->setMethods(['subTotal', 'tax'])
                    ->setConstructorArgs([$this->formatter])
                    ->getMock();
        $receipt->method('subTotal')
            ->will($this->returnValue(10.00));
        $receipt->method('tax')
            ->will($this->returnValue(1.00));
        
        $result = $receipt->postTaxTotal([], null);

        $this->assertEquals(11.00, $result);
    }

    ///Mock
    public function testPostTaxTotalMock() {
        $items = [1, 2, 5, 8];
        $coupon = null;
        $tax = 0.20;
        $receipt = $this->getMockBuilder('TDD\Receipt')
                    ->setMethods(['subTotal', 'tax'])
                    ->setConstructorArgs([$this->formatter])
                    ->getMock();

        //Doesnot test the total function, only return the value by mocking
        $receipt->expects($this->once())
            ->method('subTotal')
            ->with([], null)
            ->will($this->returnValue(15.00));

        $receipt->expects($this->once())
            ->method('tax')
            ->with(15.00)
            ->will($this->returnValue(2.00));
        
        $result = $receipt->postTaxTotal([], null);

        $this->assertEquals(17.00, $result);
    }

    public function testTax() {
        $inputAmount = 100.00;
        $this->receipt->tax = 0.10;
        $output = $this->receipt->tax($inputAmount);
        $this->assertEquals(10.00, $output, 'The tax calculation should equal 10.00');
    }
}
