<?php

namespace App\Serializer\Normalizer;

use App\Entity\User;
use Symfony\Component\Security\Core\Security;

use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class UserNormalizer implements ContextAwareNormalizerInterface, NormalizerAwareInterface
{

    private $security;
    private const ALREADY_CALLED = 'USER_NORMALIZER_ALREADY_CALLED';
    use NormalizerAwareTrait;

    public function __construct(Security $security)
    {
        $this->security = $security;

    }
    private function userIsOwner(User $user) :bool{

        /** @var User|null $authenticatedUser */
        $authenticatedUser = $this->security->getUser();
        if (!$authenticatedUser) {
            return false;
        }

        return $authenticatedUser === $user;

    }

    /**
     * @param User $object
     */
    public function normalize($object, $format = null, array $context = []): array
    {
        // check current user == $object(User entity before serialized)
        if($this->userIsOwner($object)){
            $context['groups'][] = 'owner:read';
        }

        $context[self::ALREADY_CALLED] = true;

        $data = $this->normalizer->normalize($object, $format, $context);

        return $data;
    }

    public function supportsNormalization($data, $format = null,array $context = []): bool
    {
        // avoid recursion: only call once per object
        if (isset($context[self::ALREADY_CALLED])) {
            return false;
        }
        return $data instanceof \App\Entity\User;
    }

}
