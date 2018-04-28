<?php

namespace App\Service\Adapter;

interface ExchangeRateProviderInterface {
    public function parseExchangeRates():array;
    public function saveExchangeRates():string;
}