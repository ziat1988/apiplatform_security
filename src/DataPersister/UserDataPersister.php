<?php
namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserDataPersister implements DataPersisterInterface
{
    private $em;
    private $userPasswordHasher;

    public function __construct(EntityManagerInterface $em, UserPasswordHasherInterface $userPasswordHasher){

        $this->em = $em;
        $this->userPasswordHasher = $userPasswordHasher;
    }


    public function supports($data): bool
    {

        return $data instanceof User;
    }

    /**
     * @param User $data
     */
    public function persist($data)
    {
        // cho ca update
        if($data->getPlainPassword()){
            $data->setPassword($this->userPasswordHasher->hashPassword($data,$data->getPlainPassword()));
            $data->eraseCredentials();
        }

        $this->em->persist($data);
        $this->em->flush();
    }

    public function remove($data)
    {

        $this->em->remove($data);
        $this->em->flush();
    }
}
