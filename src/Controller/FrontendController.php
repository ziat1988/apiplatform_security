<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Controller to render a basic "homepage".
 */
class FrontendController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function homepage(SerializerInterface $serializer)
    {
        /*
        // test add roles user
        $em = $this->getDoctrine()->getManager();


        $user = $this->getUser();
        $user->setRoles(['ROLE_ADMIN']);

        $em->flush();
        die();
        */
        return $this->render('frontend/homepage.html.twig',[
            'user'=>$serializer->serialize($this->getUser(),'jsonld')
        ]);
    }
}
