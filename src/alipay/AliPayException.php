<?php

/*
 * 错误调试
 */

namespace LisaoPayment\AliPay;

class AliPayException extends \Exception {

    private $sub_msg; //错误信息
    private $sub_code; //业务错误代码
    private $error_code; //网关返回码

    public function __construct(string $error_code = '', string $message = "", int $code = 0) {
        parent::__construct($message);
        $this->sub_msg = $message;
        $this->sub_code = $error_code;
        $this->error_code = $code;
    }

    /**
     * 获取业务错误信息
     * @return type
     */
    public function get_sub_msg() {
        return $this->sub_msg;
    }

    /**
     * 获取业务错误代码
     * @return type
     */
    public function get_sub_code() {
        return $this->sub_code;
    }

    /**
     * 获取网关返回码
     * @return type
     */
    public function get_error_code() {
        return $this->error_code;
    }

}
