<?php

namespace App\Entity;

use App\Repository\UserRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 * @UniqueEntity(fields={"email"}, message="EMail ist schon vergeben.")
 */
class User extends BaseEntity implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Column(type="string", length=180)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @var DateTime|null
     *
     * @ORM\Column(name="lastLogin", type="datetime", nullable=true)
     */
    private $lastLogin;

    /**
     * @var Collection
     * @ORM\OneToMany(targetEntity=Housing::class, mappedBy="maintainer")
     */
    private $maintainedHousings;

    public function __construct()
    {
        $this->maintainedHousings = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string)$this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        if (empty($roles)) {
            $roles[] = 'ROLE_GUEST';
        }

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getUsername(): string
    {
        return $this->email;
    }

    /**
     * @return Collection
     */
    public function getMaintainedHousings(): Collection
    {
        return $this->maintainedHousings;
    }

    /**
     * @param Collection $maintainedHousings
     */
    public function setMaintainedHousings(Collection $maintainedHousings): void
    {
        $this->maintainedHousings = $maintainedHousings;
    }

    public function addMaintainedHousings(Housing $housing): self
    {
        if (!$this->maintainedHousings->contains($housing)) {
            $this->maintainedHousings->add($housing);
            $housing->setMaintainer($this);
        }
        return $this;
    }

    public function removeMaintainedHousings(Housing $housing): self
    {
        $this->maintainedHousings->removeElement($housing);
        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getLastLogin(): ?DateTime
    {
        return $this->lastLogin;
    }

    /**
     */
    public function setLastLogin(): void
    {
        $this->lastLogin = new DateTime();
    }

    /**
     */
    public function isSuperAdmin(): bool
    {
        return in_array('ROLE_SUPER_ADMIN', $this->getRoles());
    }
}
