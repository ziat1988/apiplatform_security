<?php

namespace App\Tests\Functional;

use App\Test\CustomApiTestCase;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;

class UserResourceTest extends CustomApiTestCase
{
    use ReloadDatabaseTrait;

    public function testCreateUser()
    {
        $client = self::createClient();

        $client->request('POST', '/api/users', [
            'json' => [
                'email' => 'bin@yahoo.com',
                'username' => 'cheeseplease',
                'password' => 'bin'
            ]
        ]);
        $this->assertResponseStatusCodeSame(201);

        $this->logIn($client, 'bin@yahoo.com', 'bin');
    }


    public function testUpdateUser(){
        $client = self::createClient();
        $user = $this->createUserAndLogIn($client, 'yomi@example.com', 'foo');

        // test put


        $client->request('PUT', '/api/users/'.$user->getId(), [
            'json' => [
                'username' => 'new',
            ]
        ]);
        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            'username' => 'new'
        ]);
    }

    public function testGetUser()
    {
        $client = self::createClient();
        $user = $this->createUserAndLogIn($client, 'cheeseplease@example.com', 'foo');

        $user->setPhoneNumber('0760157255');
        $em = $this->getEntityManager();
        $em->flush();

        $client->request('GET', '/api/users/'.$user->getId());
        $this->assertJsonContains([
            'username' => 'cheeseplease'
        ]);

        $data = $client->getResponse()->toArray(); // decode JSON to array
        $this->assertArrayNotHasKey('phoneNumber', $data);

    }
}