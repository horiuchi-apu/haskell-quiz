<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AdminUserRepository")
 * @ORM\HasLifecycleCallbacks
 */
class AdminUser implements UserInterface
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
     * @var array
     * @ORM\Column(type="array")
     */
    private $roles;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    private $modified;

    public function __construct()
    {
        $this->setRoles(['ROLE_ADMIN']);
        $this->setCreated(new \DateTime());
        $this->setModified(new \DateTime());
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
    public function setUsername(string $username): AdminUser
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
    public function setPassword(string $password): AdminUser
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
    public function setPlainPassword(string $plainPassword): AdminUser
    {
        $this->plainPassword = $plainPassword;
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
    public function setRoles(array $roles): AdminUser
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

    /**
     * @return \DateTime
     */
    public function getCreated(): \DateTime
    {
        return $this->created;
    }

    /**
     * @param \DateTime $created
     * @return  $this
     */
    public function setCreated(\DateTime $created): AdminUser
    {
        $this->created = $created;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getModified(): \DateTime
    {
        return $this->modified;
    }

    /**
     * @param \DateTime $modified
     * @return  $this
     */
    public function setModified(\DateTime $modified): AdminUser
    {
        $this->modified = $modified;
        return $this;
    }

    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
            $this->roles,
        ));
    }

    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
            $this->roles,
            ) = unserialize($serialized);
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function updateModifiedDatetime() {
        $this->setModified(new \DateTime());
    }
}
