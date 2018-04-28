<?php
/**
 * Created by PhpStorm.
 * User: kagan
 * Date: 28.04.2018
 * Time: 11:59
 */

interface ExchangeRateProviderInterface {
    public function returnRequestUrl():string;
    public function parseExchangeRates($response):array;
    public function saveExchangeRates():string;
}