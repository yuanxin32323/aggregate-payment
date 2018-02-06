<?php

namespace LisaoPayment\ConfigBase;

/**
 * 统一下单接口类
 */
interface UnifiedOrderConfig {

    public function set();

    public function get();

    public function sign();
}
