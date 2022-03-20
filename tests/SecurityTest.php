<?php

namespace App\Tests;

use App\Repository\UserRepository;
use ErrorException;
use Exception;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Router;

class SecurityTest extends WebTestCase
{
    /**
     * @return array
     */
    public function userDataProvider(): array
    {
        return [
            'no user' => ['', ['app_public_index', 'app_register_supporter']],
            'guest@dev.de' => ['guest@dev.de', ['app_dashboard_index', 'app_user_password_reset']],
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

        /** @var $collection RouteCollection */
        $collection = $router->getRouteCollection();
        $allRoutes = $collection->all();

        if (!empty($userMail)) {
            $userRepository = static::getContainer()->get(UserRepository::class);
            $testUser = $userRepository->findOneByEmail($userMail);
            $client->loginUser($testUser);
        }

        foreach ($allRoutes as $name => $route) {
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
                if (in_array($name, $accessibleRoutes)) {
                    $this->assertResponseIsSuccessful();
                } else {
                    $this->assertResponseStatusCodeSame(403);
                }
            }
        }

    }
}
