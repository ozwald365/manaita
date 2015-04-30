<?php

namespace Manaita\Lib;

require_once('phpQuery-onefile.php');
require_once('google.php');
//require_once('log.php');

//use \Manaita\Lib\Util\Log;

class Search {
    // For Google
    const GGL_URL_FORMAT     = 'https://www.google.co.jp/search?q=%s+inurl:%s&start=%s';
    const GGL_TARGET_COOKPAD = 'cookpad';
    const GGL_INURL_COOKPAD  = 'cookpad.com/recipe/';

    private function __construct() {} // this class is static.

    public static function search_google($keyword, $page_num, $target_site) {
        
        $start_num = self::pagenum2startnum($page_num);

        $inurl = '';
        if ($target_site == self::GGL_TARGET_COOKPAD) {
            $inurl = self::GGL_INURL_COOKPAD;
        } else {
            // 増えたら追加
        }

        $url = sprintf(self::GGL_URL_FORMAT, $keyword, $inurl, $start_num);
        // googleはsjis…?
        $body = mb_convert_encoding(self::call_curl($url), 'UTF-8', 'SJIS');

        $google_result = new Google();
        $google_result->parse($body);

        return $google_result;
    }

    private static function pagenum2startnum($page_num) {
        $start_num = $page_num * 10;
        return ($start_num == 0) ? 1 : $start_num;
    }

    private static function call_curl($url) {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $body = curl_exec($ch);
        curl_close($ch);

        return $body;
    }
}


?>
