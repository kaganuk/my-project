<?php

namespace App\Service\Adapter;

interface ExchangeRateProviderInterface {
    public function parseExchangeRates():string;
    public function saveExchangeRates():string;
}