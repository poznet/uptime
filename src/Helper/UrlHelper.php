<?php
/**
 * Created by PhpStorm.
 * User: pozyc
 * Date: 30.09.2018
 * Time: 19:42
 */

namespace App\Helper;


class UrlHelper
{

    public static function clearUrl($url){
        if (substr($url, 0, 7) == 'http://') {
            $url = substr($url, 7);
            return $url;
        }
        if (substr($url, 0, 8) == 'https://') {
            $url = substr($url, 8);
            return $url;
        }
        return $url;
    }

}