<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CompetenceControllerControllerTest extends WebTestCase
{
    public function testAddcompetence()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/new');
    }

}
