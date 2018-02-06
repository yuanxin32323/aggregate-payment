<?php
/*
 * 错误调试
 */

namespace LisaoPayment\WxPay;

class WxPayException extends \Exception {

    public function errorMessage() {
        return $this->getMessage();
    }

}
