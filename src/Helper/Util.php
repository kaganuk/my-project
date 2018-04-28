<?php
/**
 * Created by PhpStorm.
 * User: kagan
 * Date: 28.04.2018
 * Time: 20:44
 */

namespace App\Helper;

class Util {
    public static function makeRequest($url, $jsonResponse = false)
    {
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($curl);

        curl_close($curl);
        if ($jsonResponse){
            $result = json_decode($result);
        }

        return $result;
    }
}