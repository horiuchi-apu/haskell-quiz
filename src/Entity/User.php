<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(
 *     fields={"username"},
 *     errorPath="username",
 *     message="既に登録されています"
 * )
 */
class User implements AdvancedUserInterface
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
     * @var \DateTime|null
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $confirmTokenLimit;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="App\Entity\Answer", mappedBy="user")
     */
    private $answers;

    /**
     * @var bool
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $isEnabled;

    public function isAccountNonExpired()
    {
        return true;
    }

    public function isAccountNonLocked()
    {
        return true;
    }

    public function isCredentialsNonExpired()
    {
        return true;
    }

    /**
     * @var array
     * @ORM\Column(type="array")
     */
    private $roles;

    public function __construct()
    {
        $this->setRoles(['ROLE_USER']);
        $this->setIsEnabled(false);
        $this->answers = new ArrayCollection();
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
     * @return \DateTime|null
     */
    public function getConfirmTokenLimit(): ?\DateTime
    {
        return $this->confirmTokenLimit;
    }

    /**
     * @param \DateTime|null $confirmTokenLimit
     * @return $this
     */
    public function setConfirmTokenLimit(?\DateTime $confirmTokenLimit): User
    {
        $this->confirmTokenLimit = $confirmTokenLimit;
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getAnswers(): ArrayCollection
    {
        return $this->answers;
    }

    /**
     * @param ArrayCollection $answers
     * @return $this
     */
    public function setAnswers(ArrayCollection $answers): User
    {
        $this->answers = $answers;
        return $this;
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->isEnabled;
    }

    /**
     * @param bool $isEnabled
     * @return $this
     */
    public function setIsEnabled(bool $isEnabled): User
    {
        $this->isEnabled = $isEnabled;
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
            $this->confirmTokenLimit,
            $this->isEnabled,
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
            $this->confirmTokenLimit,
            $this->isEnabled,
            $this->roles,
            ) = unserialize($serialized);
    }
}
