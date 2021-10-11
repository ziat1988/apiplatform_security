<?php

namespace App\Resolver;

use ApiPlatform\Core\GraphQl\Resolver\QueryCollectionResolverInterface;
use App\Entity\User;

final class UserCollectionResolver implements QueryCollectionResolverInterface
{
    /**
     * @param iterable<User> $collection
     *
     */
    public function __invoke(iterable $collection, array $context): iterable
    {
        // Query arguments are in $context['args'].

        dump($collection);
        die();
        foreach ($collection as $user) {
            // Do something with the book.
        }

        return $collection;
    }
}