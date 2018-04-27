<?php
/**
 * Created by PhpStorm.
 * User: kagan
 * Date: 27.04.2018
 * Time: 20:47
 */

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;

class LuckyController
{
    public function number()
    {
        $number = mt_rand(0, 100);

        return new Response(
            '<html><body>Lucky number: '.$number.'</body></html>'
        );
    }
}