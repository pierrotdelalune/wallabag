<?php

namespace App\Helper;

use App\Entity\User;
use Pagerfanta\Adapter\AdapterInterface;
use Pagerfanta\Pagerfanta;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class PreparePagerForEntries
{
    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @param User $user If user isn't logged in, we can force it (like for feed)
     *
     * @return Pagerfanta|null
     */
    public function prepare(AdapterInterface $adapter, User $user = null)
    {
        if (null === $user) {
            $user = $this->tokenStorage->getToken() ? $this->tokenStorage->getToken()->getUser() : null;
        }

        if (!$user instanceof User) {
            return;
        }

        $entries = new Pagerfanta($adapter);
        $entries->setMaxPerPage($user->getConfig()->getItemsPerPage());

        return $entries;
    }
}
