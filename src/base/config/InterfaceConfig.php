<?php

namespace LisaoPayment\ConfigBase;

interface InterfaceConfig {

    public function set($option, $value);

    public function get($option);

    public function get_url($sandbox);

    public function get_all();

    public function set_all($param);
}
