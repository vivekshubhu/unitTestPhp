<?php
namespace TDD;

use BadMethodCallException;

class Receipt {
    public function __construct($formatter)
    {
        $this->formatter = $formatter;
    }
    
    public function subTotal(array $items = [], $coupon) {
        if($coupon > 1)
            throw new BadMethodCallException('Coupon should be less than or equal to 1.00');

        $sum = array_sum($items);

        if(!is_null($coupon)) {
            return $sum - ($sum * $coupon);
        }

        return $sum;
    }

    public function tax($amount) {
        return $this->formatter->currencyAmount($amount * $this->tax);
    }

    public function postTaxTotal($items, $coupon) {
        $subTotal = $this->subTotal($items, $coupon);
        return $subTotal + $this->tax($subTotal);
    }

}