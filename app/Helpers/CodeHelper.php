<?php
if (!function_exists('generateCode')) {
    function generateCode($code, $number="000001") {
        $slash = explode("-", $number);
        $dot = explode("-", $number);

        if (date('Y') == @$slash[1]) {
            $number = sprintf("%'.06d", ($dot[3] + 1));
        } else {
            $number = "000001";
        }
        
        return $code.'-'.date('Y').'-'.date('m').'-'.$number;
    }
}