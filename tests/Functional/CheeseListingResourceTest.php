<?php
namespace App\Tests\Functional;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
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
            //'headers'=> ['Content-Type'=>'text/html'],
            //'json'=>[]


        ]);
        $this->assertResponseStatusCodeSame(401);

        $this->createUser('tom@yahoo.com','tom');

        $this->logIn($client, 'tom@yahoo.com','tom');


    }
}
