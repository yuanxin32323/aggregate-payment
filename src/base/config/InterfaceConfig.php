<?php

namespace LisaoPayment\ConfigBase;

interface InterfaceConfig {

    public function set($option, $value);

    public function get($option);

    public function get_all();
}
