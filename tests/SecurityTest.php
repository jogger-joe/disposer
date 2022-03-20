<?php

namespace App\Tests;

use App\DataFixtures\FurnitureAndServiceFixtures;
use App\DataFixtures\HousingFixtures;
use App\DataFixtures\SupporterFixtures;
use App\DataFixtures\UserFixtures;
use App\Repository\UserRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Router;

class SecurityTest extends WebTestCase
{
    const EXCLUDED_ROUTES = [
        'app_login',
        'app_logout',
        'app_public_index',
        'app_register_supporter'
    ];

    /**
     * @return array
     */
    public function userDataProvider(): array
    {
        $guestAccess = ['app_dashboard_index', 'app_profile_edit'];
        $supporterAccess = array_merge($guestAccess,
            ['app_housing_maintained', 'app_housing_maintained_edit']);
        $userAccess = array_merge($supporterAccess,
            ['app_furniture_index', 'app_housing_index', 'app_service_index', 'app_supporter_accepted', 'app_supporter_unaccepted']);
        $adminSupporterAccess = array_merge($userAccess,
            ['app_supporter_edit', 'app_supporter_activate', 'app_supporter_remove', 'app_supporter_new']);
        $adminServiceAccess = array_merge($userAccess,
            ['app_service_edit', 'app_service_new', 'app_service_remove']);
        $adminFurnitureAccess = array_merge($userAccess,
            ['app_furniture_edit', 'app_furniture_new', 'app_furniture_remove']);
        $adminHousingAccess = array_merge($userAccess,
            ['app_housing_edit', 'app_housing_new', 'app_housing_remove']);
        $createUserAccess = ['app_user_register'];
        $editUserAccess = ['app_user_edit', 'app_user_remove'];
        $adminAccess = array_merge($adminSupporterAccess, $adminServiceAccess, $adminFurnitureAccess, $adminHousingAccess, $createUserAccess,
            ['app_user_index']);
        $adminUserAccess = array_merge($editUserAccess, $createUserAccess);
        $superAdminAccess = array_merge($adminAccess, $adminUserAccess);

        return [
            'super-admin@dev.de' => ['super-admin@dev.de', $superAdminAccess],
            'admin@dev.de' => ['admin@dev.de', $adminAccess],
            'admin-user@dev.de' => ['admin-user@dev.de', $adminUserAccess],
            'edit-user@dev.de' => ['edit-user@dev.de', $editUserAccess],
            'create-user@dev.de' => ['create-user@dev.de', $createUserAccess],
            'admin-housing@dev.de' => ['admin-housing@dev.de', $adminHousingAccess],
            'admin-furniture@dev.de' => ['admin-furniture@dev.de', $adminFurnitureAccess],
            'admin-service@dev.de' => ['admin-service@dev.de', $adminServiceAccess],
            'admin-supporter@dev.de' => ['admin-supporter@dev.de', $adminSupporterAccess],
            'supporter@dev.de' => ['supporter@dev.de', $supporterAccess],
            'guest@dev.de' => ['guest@dev.de', $guestAccess],
            'no user' => ['', []],
        ];
    }

    /**
     * @dataProvider userDataProvider
     *
     * @param $userMail
     * @param array $accessibleRoutes
     *
     * @return void
     */
    public function testAccessControl($userMail, array $accessibleRoutes): void
    {
        $client = static::createClient();

        /** @var $router Router */
        $router = static::getContainer()->get('router');

        $doctrine = static::getContainer()->get('doctrine');
        $entityManager = $doctrine->getManager();

        print 'seeding data' . PHP_EOL;
        $userPasswordHasherMock = $this->getMockBuilder(UserPasswordHasherInterface::class)->setMethods(['hashPassword'])->getMock();
        $userPasswordHasherMock->method('hashPassword')->willReturn('');
        (new UserFixtures($userPasswordHasherMock))->load($entityManager);
        (new FurnitureAndServiceFixtures())->load($entityManager);
        (new HousingFixtures())->load($entityManager);
        (new SupporterFixtures())->load($entityManager);

        /** @var $collection RouteCollection */
        $collection = $router->getRouteCollection();
        $allRoutes = $collection->all();

        print "start test for user $userMail".PHP_EOL;

        if (!empty($userMail)) {
            $userRepository = static::getContainer()->get(UserRepository::class);
            $testUser = $userRepository->findOneByEmail($userMail);
            $client->loginUser($testUser);
        }
        foreach ($allRoutes as $name => $route) {
            print "check $name: ";
            if (in_array($name, self::EXCLUDED_ROUTES)) {
                print 'skipped'.PHP_EOL;
                continue;
            }
            /** @var $route Route */
            $method = count($route->getMethods()) > 0 ? $route->getMethods()[0] : 'GET';
            $uri = str_replace('{id}', 1, $route->getPath());
            try {
                $client->request($method, $uri);
                if (in_array($name, $accessibleRoutes)) {
                    $this->assertResponseIsSuccessful();
                } else {
                    $this->assertResponseStatusCodeSame(403);
                }
            } catch (Exception $ex) {
                print get_class($ex);
                $this->assertTrue(!in_array($name, $accessibleRoutes));
            }
            print PHP_EOL;
        }

    }
}
