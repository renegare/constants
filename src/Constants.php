<?php

namespace Renegare\Constants;

use Symfony\Component\Yaml\Yaml;

class Constants {

    public static function compile() {
        $sources = func_get_args();
        $constants = [];

        foreach($sources as $value) {
            if(is_array($value)) {
                $constants[] = $value;
            } else {
                if(file_exists($value)) {
                    $config = Yaml::parse(file_get_contents($value));
                    if(is_array($config)) {
                        $constants[] = $config;
                    }
                }
            }
        }

        if(count($constants) > 0) {
            $constants = call_user_func_array('array_merge', $constants);
        }

        return $constants;
    }
}
