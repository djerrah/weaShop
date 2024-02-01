<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/v1')]
class WeatherProductsController extends AbstractController
{
    #[Route('/weather/products', name: 'app_weather_products', methods: 'GET')]
    public function getProducts(): JsonResponse
    {
        $data = ['message' => 'Welcome to your API!'];

        return $this->json($data);
    }
}
