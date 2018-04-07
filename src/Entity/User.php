<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(
 *     fields={"username"},
 *     errorPath="username",
 *     message="既に登録されています"
 * )
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", unique=true)
     * @Assert\Regex(
     *     pattern="/^is\d{6}$/",
     *     message="学籍番号が正しくありません"
     * )
     */
    private $username;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @var string
     */
    private $plainPassword;


    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $confirmToken;

    /**
     * @var bool
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $isEnable;

    /**
     * @var array
     * @ORM\Column(type="array")
     */
    private $roles;

    public function __construct()
    {
        $this->setRoles(['ROLE_USER']);
        $this->setIsEnable(false);
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * @param string $username
     * @return  $this
     */
    public function setUsername(string $username): User
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return  $this
     */
    public function setPassword(string $password): User
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return string
     */
    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    /**
     * @param string $plainPassword
     * @return $this
     */
    public function setPlainPassword(string $plainPassword): User
    {
        $this->plainPassword = $plainPassword;
        return $this;
    }

    /**
     * @return string
     */
    public function getConfirmToken(): ?string
    {
        return $this->confirmToken;
    }

    /**
     * @param string|null $confirmToken
     * @return $this
     */
    public function setConfirmToken($confirmToken): User
    {
        $this->confirmToken = $confirmToken;
        return $this;
    }

    /**
     * @return bool
     */
    public function isEnable(): bool
    {
        return $this->isEnable;
    }

    /**
     * @param bool $isEnable
     * @return $this
     */
    public function setIsEnable(bool $isEnable): User
    {
        $this->isEnable = $isEnable;
        return $this;
    }

    /**
     * @return string
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @param string|null $salt
     * @return  $this
     */
    public function setSalt($salt): User
    {
        $this->salt = $salt;
        return $this;
    }

    /**
     * @return array
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * @param array $roles
     * @return  $this
     */
    public function setRoles(array $roles): User
    {
        $this->roles = $roles;
        return $this;
    }

    public function addRole($role)
    {
        $this->roles[] = $role;
    }

    public function eraseCredentials()
    {
        return null;
    }

    public function getEmail()
    {
        return $this->getUsername() . "@cis.aichi-pu.ac.jp";
    }

    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
            $this->confirmToken,
            $this->isEnable,
            $this->roles,
        ));
    }

    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
            $this->confirmToken,
            $this->isEnable,
            $this->roles,
            ) = unserialize($serialized);
    }
}
