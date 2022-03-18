<?php

namespace App\DataFixtures;

use App\Entity\Service;
use App\Entity\Supporter;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class SupporterFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $supporterToCreate = [
            'Max Mustermann',
            'Monika Mustermann',
            'Alfred Mustermann',
            'Olga Mustermann',
            'Rudi Mustermann',
            'Manfred'
        ];

        $services = new ArrayCollection();
        foreach (FurnitureAndServiceFixtures::SERVICES as $refName) {
            $service = $this->getReference($refName);
            if ($service instanceof Service) {
                $services->add($service);
            }
        }
        $servicesCount = $services->count();
        foreach ($supporterToCreate as $supporterName) {
            $supporter = new Supporter();
            $supporter->setName($supporterName);
            $supporter->setContact("Email oder Telefonnummer von $supporterName");
            $supporter->setInformation("Beispielinformationen zum User $supporterName");
            $supporter->setStatus(rand(0,1));
            for ($i = 1; $i <= 5; $i++) {
                $supporter->addAvailableService($services->get(rand(0, $servicesCount-1)));
            }
            $manager->persist($supporter);
            $services[] = $supporter;
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            FurnitureAndServiceFixtures::class];
    }
}
