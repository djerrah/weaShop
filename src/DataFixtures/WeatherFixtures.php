<?php

namespace App\DataFixtures;

use App\Entity\Weather;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class WeatherFixtures extends Fixture implements OrderedFixtureInterface
{
    private array $weathers = [
        [
            'name' => 'hot',
        ],
        [
            'name' => 'cold',
        ],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach ($this->weathers as $weatherData) {
            $weather = new Weather($weatherData['name']);

            $manager->persist($weather);
            $this->addReference($weatherData['name'], $weather);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 1;
    }
}
