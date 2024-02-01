<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture implements DependentFixtureInterface
{
    private array $products = [
        [
            'name' => 'Tshirt bleu',
            'price' => '20.00',
            'weathers' => [
               'hot',
                'cold',
            ],
        ],
        [
            'name' => 'Tshirt rouge',
            'price' => '20.00',
            'weathers' => [
                'hot',
                'cold',
            ],
        ],
        [
            'name' => 'Pull',
            'price' => '20.00',
            'weathers' => [
                'cold',
            ],
        ],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach ($this->products as $productData) {
            $product = new Product($productData['name'], $productData['price']);

            foreach ($productData['weathers'] as $weather) {
                $product->addWeather($this->getReference($weather));
            }

            $manager->persist($product);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [WeatherFixtures::class];
    }
}
