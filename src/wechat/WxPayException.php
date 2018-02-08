<?php

/*
 * 错误调试
 */

namespace LisaoPayment\WxPay;

class WxPayException extends \Exception {

    private $msg; //错误信息
    private $code; //错误代码

    public function __construct(string $code = '', string $message = "") {
        parent::__construct($message);
        $this->msg = $message;
        $this->code = $code;
    }

    public function get_error_msg() {
        return $this->msg;
    }

    public function get_error_code() {
        return $this->code;
    }

}
