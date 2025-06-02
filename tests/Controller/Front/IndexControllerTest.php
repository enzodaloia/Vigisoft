<?php

namespace App\Tests\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Repository\UserRepository;

final class IndexControllerTest extends WebTestCase
{
    public function testIndex(): void
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);

        $testUser = $userRepository->findOneByEmail('test@example.com');

        $client->loginUser($testUser);

        $client->request('GET', '/Front/Dashboard');

        self::assertResponseIsSuccessful();
    }
}
