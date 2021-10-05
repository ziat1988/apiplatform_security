<?php
namespace App\Tests\Functional;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\CheeseListing;
use App\Entity\User;
use App\Test\CustomApiTestCase;
use Doctrine\ORM\EntityManagerInterface;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;

class CheeseListingResourceTest extends CustomApiTestCase
{
    use ReloadDatabaseTrait; // reset DATABASE each test
    public function testCreateCheeseListing(){

        $client = self::createClient();
        $client->request('POST', '/api/cheeses',[
            'headers'=> ['Content-Type'=>'text/html'],
            //'json'=>[]
        ]);
        $this->assertResponseStatusCodeSame(401);

        $this->createUserAndLogIn($client,'tom@yahoo.com','tom');

        $client->request('POST', '/api/cheeses',[
            'headers'=> ['Content-Type'=>'application/json'],
            //'json'=>[]

        ]);
        $this->assertResponseStatusCodeSame(400);  // invalid empty data
    }

    public function testUpdateCheeseListing()
    {
        $client = self::createClient();
        $user1 = $this->createUser('user1@example.com', 'foo');
        $user2 = $this->createUser('user2@example.com', 'foo');

        $cheeseListing = new CheeseListing('Block of cheddar 2');
        $cheeseListing->setOwner($user1);
        $cheeseListing->setPrice(1000);
        $cheeseListing->setDescription('mmmm');

        $em = $this->getEntityManager();
        $em->persist($cheeseListing);
        $em->flush();

        // log user
        $this->logIn($client, 'user1@example.com', 'foo');

        // operation
        $client->request('PUT', '/api/cheeses/'.$cheeseListing->getId(), [
            'json' => ['title' => 'here jus']
        ]);

        $this->assertResponseStatusCodeSame(200);

        // user 2 test
        $this->logIn($client, 'user2@example.com', 'foo');
        $client->request('PUT', '/api/cheeses/'.$cheeseListing->getId(), [
            'json' => ['title' => 'updated', 'owner'=>'/api/users'.$user2->getId()]
        ]);

        $this->assertResponseStatusCodeSame(200, 'only author can updated');


    }
}
