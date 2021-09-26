<?php
namespace App\Controller;

use ApiPlatform\Core\Api\IriConverterInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login", methods={"POST"})
     */
    public function login (IriConverterInterface $iriConverter)
    {
        // check trong truong hop client khong gui JSON. json_login se khong duoc thong qua ma di thang den doan nay.
        if(! $this->isGranted('IS_AUTHENTICATED_FULLY')){
            return $this->json([
                'error' =>' Invalid request. Check json '
            ],400);
        }

        return new Response(null, 204, [
            'Location' => $iriConverter->getIriFromItem($this->getUser())
        ]);
    }

    /**
     * @Route("/logout",name="app_logout")
     */
    public function logout(){ // Method nay chi can ton tai. Con cac doan code ben trong se khong duoc reached den.
        throw new \Exception('should not be reached');
    }


}