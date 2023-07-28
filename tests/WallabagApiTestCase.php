<?php

namespace App\Tests;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Model\UserManager;
use FOS\UserBundle\Security\LoginManager;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

abstract class WallabagApiTestCase extends WebTestCase
{
    /**
     * @var Client
     */
    protected $client = null;

    /**
     * @var UserInterface
     */
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = $this->createAuthorizedClient();
    }

    /**
     * @return Client
     */
    protected function createAuthorizedClient()
    {
        $client = static::createClient();
        $container = $client->getContainer();

        /** @var UserManager $userManager */
        $userManager = $container->get('fos_user.user_manager.test');
        /** @var LoginManager $loginManager */
        $loginManager = $container->get('fos_user.security.login_manager.test');
        $firewallName = $container->getParameter('fos_user.firewall_name');

        $this->user = $userManager->findUserBy(['username' => 'admin']);
        $loginManager->logInUser($firewallName, $this->user);

        // save the login token into the session and put it in a cookie
        $container->get(SessionInterface::class)->set('_security_' . $firewallName, serialize($container->get(TokenStorageInterface::class)->getToken()));
        $container->get(SessionInterface::class)->save();

        $session = $container->get(SessionInterface::class);
        $client->getCookieJar()->set(new Cookie($session->getName(), $session->getId()));

        return $client;
    }

    /**
     * Return the ID for the user admin.
     * Used because on heavy testing we don't want to re-create the database on each run.
     * Which means "admin" user won't have id 1 all the time.
     *
     * @param string $username
     *
     * @return int
     */
    protected function getUserId($username = 'admin')
    {
        return $this->client
            ->getContainer()
            ->get(EntityManagerInterface::class)
            ->getRepository(User::class)
            ->findOneByUserName($username)
            ->getId();
    }
}
