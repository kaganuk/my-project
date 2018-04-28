<?php
/**
 * Created by PhpStorm.
 * User: kagan
 * Date: 27.04.2018
 * Time: 20:47
 */

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ExchangeRateController
{
    /**
     * @Route("/", name="Exchange_rate_list")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function list():Response
    {
        $number = mt_rand(0, 100);

        return new Response(
            '<html><body>Lucky number: '.$number.'</body></html>'
        );
    }
}