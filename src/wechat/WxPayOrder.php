<?php

/**
 * 离骚 微信订单类
 * @version 1.0 @ 2018-02-05
 * @see https://github.com/yuanxin32323
 */

namespace LisaoPayment\WxPay;

class WxPayOrder {

    private $config;

    public function __construct(WxPayConfig $config) {

        $this->config = $config;
    }

    /*
     * 创建订单
     */

    public function create_order() {
        $url = 'https://api.mch.weixin.qq.com/pay/unifiedorder';

        //throw new WxPayException("错误测试");
    }

}
