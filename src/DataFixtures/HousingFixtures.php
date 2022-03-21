<?php

namespace App\DataFixtures;

use App\Entity\Furniture;
use App\Entity\Housing;
use App\Entity\Service;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class HousingFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $titlesToCreate = [
            'Musterstraße 1',
            'Musterstraße 2',
            'Musterstraße 3',
            'Teststraße 11',
            'Teststraße 12',
        ];
        $descriptionsToCreate = [
            'Etage 1 Links, 3 Raumwohnung',
            'Etage 2 Rechts, 2 Raumwohnung',
            'Etage 3, 3 Raumwohnung',
            'Etage 4 Mitte, 4 Raumwohnung',
        ];

        $services = new ArrayCollection();
        foreach (FurnitureAndServiceFixtures::SERVICES as $refName) {
            $service = $this->getReference($refName);
            if ($service instanceof Service) {
                $services->add($service);
            }
        }

        $furnitures = new ArrayCollection();
        foreach (FurnitureAndServiceFixtures::FURNITURES as $refName) {
            $furniture = $this->getReference($refName);
            if ($furniture instanceof Furniture) {
                $furnitures->add($furniture);
            }
        }
        $users = new ArrayCollection();
        foreach (UserFixtures::USERS as $refName) {
            $user = $this->getReference($refName);
            if ($user instanceof User) {
                $users->add($user);
            }
        }

        $servicesCount = $services->count();
        $furnitureCount = $furnitures->count();
        $usersCount = $users->count();

        foreach ($titlesToCreate as $title) {
            foreach ($descriptionsToCreate as $description) {
                $housing = new Housing();
                $housing->setTitle($title);
                $housing->setDescription($description);
                if (rand(0, 1)) {
                    $housing->setMaintainer($users->get(rand(0, $usersCount-1)));
                }
                $housing->setStatus(rand(0, 3));
                for ($i = 1; $i <= 10; $i++) {
                    $housing->addMissingFurniture($furnitures->get(rand(0, $furnitureCount-1)));
                }
                for ($i = 1; $i <= 5; $i++) {
                    $housing->addMissingService($services->get(rand(0, $servicesCount-1)));
                }
                $manager->persist($housing);
            }
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            FurnitureAndServiceFixtures::class,
            UserFixtures::class];
    }
}
