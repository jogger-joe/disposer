<?php

namespace App\Entity;

use App\Repository\SupporterRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SupporterRepository::class)
 */
class Supporter
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     *
     * @var string
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     *
     * @var string
     */
    private $contact;

    /**
     * @ORM\Column(type="text")
     *
     * @var string
     */
    private $information;

    /**
     * @ORM\ManyToMany(targetEntity=Service::class, inversedBy="supporter", cascade={"persist"})
     *
     * @var Collection
     */
    private $availableServices;

    /**
     * @ORM\Column(type="integer", options={"default": 1})
     *
     * @var int
     */
    private $status;

    public function __construct()
    {
        $this->availableServices = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getContact(): string
    {
        return $this->contact;
    }

    /**
     * @param string $contact
     */
    public function setContact(string $contact): void
    {
        $this->contact = $contact;
    }

    /**
     * @return string
     */
    public function getInformation(): string
    {
        return $this->information;
    }

    /**
     * @param string $information
     */
    public function setInformation(string $information): void
    {
        $this->information = $information;
    }

    /**
     * @return Collection|Service[]
     */
    public function getAvailableServices(): Collection
    {
        return $this->availableServices;
    }

    public function addAvailableService(Service $service): self
    {
        if (!$this->availableServices->contains($service)) {
            $this->availableServices->add($service);
            $service->addSupporter($this);
        }
        return $this;
    }

    public function removeAvailableService(Service $service): self
    {
        $this->availableServices->removeElement($service);
        return $this;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @param int $status
     */
    public function setStatus(int $status): void
    {
        $this->status = $status;
    }
}
