<?php

namespace LisaoPayment\ConfigBase;

interface InterfaceConfig {

    public function set(string $option, $value);

    public function get(string $option);


    public function get_all();

    public function set_all(array $param);
}
