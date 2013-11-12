<?php

namespace TC\CoreBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PricingControllerControllerTest extends WebTestCase
{
    public function testSoon()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');
    }

}
