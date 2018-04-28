<?php
/**
 * Created by PhpStorm.
 * User: kagan
 * Date: 27.04.2018
 * Time: 20:47
 */

namespace App\Controller;

use App\Service\ExchangeRateService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ExchangeRateController
{
    /**
     * @Route("/", name="Exchange_rate_list")
     * @param \App\Service\ExchangeRateService $exchangeRateService
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function list(ExchangeRateService $exchangeRateService):Response
    {
        $lastExchangeRates = $exchangeRateService->getLastExchangeRates();

        return new Response(
            '<html><body>bla bla</body></html>'
        );
    }
}