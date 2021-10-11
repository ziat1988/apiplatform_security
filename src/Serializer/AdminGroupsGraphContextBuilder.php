<?php

namespace App\Serializer;

use ApiPlatform\Core\GraphQl\Serializer\SerializerContextBuilderInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

final class AdminGroupsGraphContextBuilder implements SerializerContextBuilderInterface
{
    private $decorated;
    private $authorizationChecker;

    public function __construct(SerializerContextBuilderInterface $decorated, AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->decorated = $decorated;
        $this->authorizationChecker = $authorizationChecker;
    }

    public function create(string $resourceClass, string $operationName, array $resolverContext, bool $normalization): array
    {

        $context = $this->decorated->create($resourceClass, $operationName, $resolverContext, $normalization);
        $isAdmin = $this->authorizationChecker->isGranted('ROLE_ADMIN');

        if (isset($context['groups']) && !$isAdmin) {
            if($normalization){
                if (($key = array_search('user:read', $context['groups'])) !== FALSE) {
                    unset($context['groups'][$key]);
                }
            }

           //
            // $context['groups'][] = $normalization ? 'admin:read' : 'admin:write';
        }


        return $context;
    }
}
