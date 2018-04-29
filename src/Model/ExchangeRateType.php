<?php
/**
 * Created by PhpStorm.
 * User: kagan
 * Date: 28.04.2018
 * Time: 21:58
 */

namespace App\Model;

class ExchangeRateType {
    public const USDTRY = 1;
    public const EURTRY = 2;
    public const GBPTRY = 3;

    private static $exchangeRateTypes = [
        self::USDTRY => 'USDTRY',
        self::EURTRY => 'EURTRY',
        self::GBPTRY => 'GBPTRY'
    ];

    /**
     * @return array
     */
    public static function getExchangeRateTypes(): array
    {
        return array_keys(self::$exchangeRateTypes);
    }

    public static function getTitle($type):string
    {
        if (array_key_exists($type, self::$exchangeRateTypes)){
            return self::$exchangeRateTypes[$type];
        }

        return 'Unknown Type';
    }
}