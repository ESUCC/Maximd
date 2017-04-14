<?php
/**
 * Created by PhpStorm.
 * User: jlavere
 * Date: 12/6/13
 * Time: 1:03 PM
 */
class App_Encoding_Encode
{
    /**
     * @param $var
     * @return string
     */
    public static function forceUtf8($var) {
        if(is_string($var)) {
            if (preg_match('%^(?:
                  [\x09\x0A\x0D\x20-\x7E]            # ASCII
                | [\xC2-\xDF][\x80-\xBF]             # non-overlong 2-byte
                | \xE0[\xA0-\xBF][\x80-\xBF]         # excluding overlongs
                | [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}  # straight 3-byte
                | \xED[\x80-\x9F][\x80-\xBF]         # excluding surrogates
                | \xF0[\x90-\xBF][\x80-\xBF]{2}      # planes 1-3
                | [\xF1-\xF3][\x80-\xBF]{3}          # planes 4-15
                | \xF4[\x80-\x8F][\x80-\xBF]{2}      # plane 16
            )*$%xs', $var))
                return $var;
            else
                return iconv('CP1252', 'UTF-8', $var);
        } elseif(is_array($var)) {
            /**
             * array
             */
            foreach($var as $key => $value) {
                $var[$key] = self::forceUtf8($value);
            }
            return $var;
        } else {
            return $var;
        }
    }
}