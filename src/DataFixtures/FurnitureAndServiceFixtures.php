<?php

namespace App\DataFixtures;

use App\Entity\Furniture;
use App\Entity\Service;
use App\Service\FurnitureTypeResolver;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class FurnitureAndServiceFixtures extends Fixture
{
    public const SERVICES = [
        'service0',
        'service1',
        'service2',
        'service3',
        'service4',
        'service5'];
    public const FURNITURES = [
        'furniture0',
        'furniture1',
        'furniture2',
        'furniture3',
        'furniture4',
        'furniture5',
        'furniture6',
        'furniture7',
        'furniture8',
        'furniture9',
        'furniture10',
        'furniture11',
        'furniture12',
        'furniture13',
        'furniture14',
        'furniture15',
        'furniture16',
        'furniture17',
        'furniture18',
        'furniture19',
        'furniture20',
        'furniture21',
        'furniture22',
        'furniture23',
        'furniture24',
        'furniture25',
        'furniture26',
        'furniture27',
        'furniture28',
        'furniture29',
        'furniture30',
        'furniture31',
        'furniture32',
        'furniture33',
        'furniture34'];

    public function load(ObjectManager $manager): void
    {
        // seed some furnitures
        $furnitureIndex = 0;
        foreach (FurnitureTypeResolver::FURNITURE_TYPE_MAP as $key => $label) {
            $furnituresToCreate = [
                'Deckenlampe',
                'Tisch',
                'Stuhl'
            ];
            if (in_array($key, [4, 5, 6])) {
                $furnituresToCreate[] = 'Bett';
                $furnituresToCreate[] = 'Schrank';
            }
            if (in_array($key, [1])) {
                $furnituresToCreate[] = 'Waschmaschine';
                $furnituresToCreate[] = 'Hygieneartikel';
                $furnituresToCreate[] = 'Spiegel';
                $furnituresToCreate[] = 'Spiegelschrank';
            }
            if (in_array($key, [2])) {
                $furnituresToCreate[] = 'Herd';
                $furnituresToCreate[] = 'Ofen';
                $furnituresToCreate[] = 'Küchenzeile';
                $furnituresToCreate[] = 'Waschbecken';
            }
            foreach ($furnituresToCreate as $furnitureName) {
                $furniture = new Furniture();
                $furniture->setType($key);
                $furniture->setTitle($furnitureName);
                $manager->persist($furniture);
                $this->addReference("furniture$furnitureIndex", $furniture);
                $furnitureIndex ++;
            }
        }

        // seed some services
        $servicesToCreate = [
            'Malern',
            'Schränke aufbauen',
            'Boden verlegen',
            'Personentransport',
            'Elektrogeräte anschließen',
            'Übersetzen'
        ];
        foreach ($servicesToCreate as $furnitureIndex => $serviceName) {
            $service = new Service();
            $service->setTitle($serviceName);
            $manager->persist($service);
            $this->addReference("service$furnitureIndex", $service);
        }

        $manager->flush();
    }
}
