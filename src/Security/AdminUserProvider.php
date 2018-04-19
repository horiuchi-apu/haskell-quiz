<?php
/**
 * Created by PhpStorm.
 * User: Horiuchi
 * Date: 2018/03/14
 * Time: 2:21
 */

namespace App\Security;


use App\Entity\AdminUser;
use App\Repository\AdminUserRepository;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class AdminUserProvider implements UserProviderInterface
{
    private $repository;

    public function __construct(AdminUserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function loadUserByUsername($username)
    {
        $user =  $this->repository->findOneBy(['username' => $username]);

        if (!$user) {
            throw new UsernameNotFoundException("username: {$username} not found.");
        }

        return $user;
    }

    public function refreshUser(UserInterface $user)
    {
        $class = get_class($user);

        if (!$this->supportsClass($class)) {
            throw new UnsupportedUserException("class: {$class} not supported.");
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass($class)
    {
        return $class == AdminUser::class;
    }

}