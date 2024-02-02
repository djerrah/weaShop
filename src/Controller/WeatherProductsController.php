<?php

namespace App\Controller;

use App\DataFixtures\ProductFixtures;
use App\Repository\ProductRepository;
use App\Spi\WeatherSpi;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/v1')]
class WeatherProductsController extends AbstractController
{
    #[Route('/weather/products', name: 'app_weather_products', methods: 'GET')]
    public function getProducts(ProductRepository $repository, WeatherSpi $weatherSpi): JsonResponse
    {
        $weather = $weatherSpi->getWeather('Paris');
        $products = $repository->findByWeather($weather['is']);

        $data = [
            'products' => $products,
            'weather' => $weather,
        ];

        return $this->json($data);
    }
}
