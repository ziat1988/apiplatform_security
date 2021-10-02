<?php
namespace App\Tests\Functional;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;

class CheeseListingResourceTest extends ApiTestCase
{
    public function testCreateCheeseListing(){

        $client = self::createClient();
        $client->request('POST', '/api/cheeses',[
            //'headers'=> ['Content-Type'=>'text/html'],
            //'json'=>[]
        ]);
        $this->assertResponseStatusCodeSame(401);

    }
}
