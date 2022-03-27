<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Router;

class SecurityTest extends WebTestCase
{
    const PUBLIC_ROUTES = [
        'app_login',
        'app_logout',
        'app_public_index',
        'app_register_supporter',
        'api_required_furniture',
        'api_required_service',
        'app_register_result'
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
     * @return array
     */
    public function routeDataProvider(): array
    {
        /** @var $router Router */
        $router = static::getContainer()->get('router');

        /** @var $collection RouteCollection */
        $collection = $router->getRouteCollection();
        $allRoutes = $collection->all();
        $testData = [];
        foreach ($allRoutes as $name => $route) {
            $testData[$name] = [$name, $route];
        }
        return $testData;
    }

    /**
     * @dataProvider routeDataProvider
     *
     * @param string $routeName
     * @param Route $route
     *
     * @return void
     */
    public function testThatAllNonPublicRoutesAreNotAvailableWithoutLogin(string $routeName, Route $route): void
    {
        $client = static::createClient();

        if (in_array($routeName, self::PUBLIC_ROUTES)) {
            $this->markTestSkipped('public route');
        }

        $uri = str_replace('{id}', 1, $route->getPath());
        foreach ($route->getMethods() as $method) {
            $client->request($method, $uri);
            $this->assertResponseStatusCodeSame(302);
        }

    }
}
