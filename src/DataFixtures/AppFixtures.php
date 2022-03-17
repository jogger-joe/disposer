<?php

namespace App\DataFixtures;

use App\Entity\Furniture;
use App\Entity\Housing;
use App\Entity\Service;
use App\Entity\Supporter;
use App\Entity\User;
use App\Service\FurnitureTypeResolver;
use App\Service\HousingStatusResolver;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    /**
     * @var UserPasswordHasherInterface
     */
    private $userPasswordHasher;

    public function __construct (UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // develop user
        $developUser = new User();
        $developUser->setName('development user');
        $developUser->setEmail('dev@dev.de');
        $developUser->setPassword($this->userPasswordHasher->hashPassword($developUser, 'develop'));
        $developUser->setRoles(['ROLE_SUPER_ADMIN']);
        $manager->persist($developUser);

        // seed some furnitures
        foreach (FurnitureTypeResolver::FURNITURE_TYPE_MAP as $key => $label) {
            $furnituresToCreate = rand(2, 5);
            while ($furnituresToCreate > 0) {
                $furniture = new Furniture();
                $furniture->setType($key);
                $furniture->setTitle("testfurniture $key");
                $manager->persist($furniture);
                $furnituresToCreate --;
            }
        }

        // seed some services
        $servicesToCreate = rand(5, 10);
        while ($servicesToCreate > 0) {
            $service = new Service();
            $service->setTitle("testservice");
            $manager->persist($service);
            $servicesToCreate --;
        }

        // seed some services
        $supporterToCreate = rand(5, 10);
        while ($supporterToCreate > 0) {
            $supporter = new Supporter();
            $supporter->setName("testsupporter");
            $supporter->setContact("test@test.de");
            $supporter->setInformation("supporter informations");
            $supporter->setStatus(rand(1,2));
            $manager->persist($supporter);
            $supporterToCreate --;
        }

        foreach (HousingStatusResolver::HOUSING_STATUS_MAP as $key => $label) {
            $housingsToCreate = rand(2, 10);
            while ($housingsToCreate > 0) {
                $housing = new Housing();
                $housing->setStatus($key);
                $housing->setDescription("description for housing");
                $housing->setTitle("testhousing");
                $manager->persist($housing);
                $housingsToCreate --;
            }
        }

        $manager->flush();
    }
}
