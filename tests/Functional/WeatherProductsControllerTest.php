<?php

namespace App\Tests\Functional;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class WeatherProductsControllerTest extends WebTestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function testGetProducts()
    {
        $client = $this->createClient();

        $client->request('GET', '/api/v1/weather/products');
        $this->assertResponseIsSuccessful();
        $response = $client->getResponse()->getContent();
        $this->assertJson($response);
    }
}
