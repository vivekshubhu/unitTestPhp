<?php
namespace TDD;

class Formatter {
    public function currencyAmount($amount) {
        return round($amount, 2);
    }
}