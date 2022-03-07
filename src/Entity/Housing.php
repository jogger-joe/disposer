<?php

namespace App\Entity;

use App\Repository\HousingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=HousingRepository::class)
 */
class Housing
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
    private $title;

    /**
     * @ORM\Column(type="text")
     *
     * @var string
     */
    private $description;

    /**
     * @ORM\ManyToMany(targetEntity=Furniture::class, inversedBy="housings", cascade={"persist"})
     *
     * @var Collection
     */
    private $furnitures;

    /**
     * @ORM\ManyToMany(targetEntity=Service::class, inversedBy="housings", cascade={"persist"})
     *
     * @var Collection
     */
    private $missingServices;

    /**
     * @var Collection
     */
    private $missingDefaultFurnitures;

    /**
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    private $status;

    public function __construct()
    {
        $this->furnitures = new ArrayCollection();
        $this->missingServices = new ArrayCollection();
        $this->missingDefaultFurnitures = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return Collection|Furniture[]
     */
    public function getFurnitures(): Collection
    {
        return $this->furnitures;
    }

    public function addFurniture(Furniture $furniture): self
    {
        if (!$this->furnitures->contains($furniture)) {
            $this->furnitures->add($furniture);
            $furniture->addHousing($this);
        }
        return $this;
    }

    public function removeFurniture(Furniture $furniture): self
    {
        $this->furnitures->removeElement($furniture);
        return $this;
    }

    /**
     * @return Collection|Service[]
     */
    public function getMissingServices(): Collection
    {
        return $this->missingServices;
    }

    public function addMissingService(Service $service): self
    {
        if (!$this->missingServices->contains($service)) {
            $this->missingServices->add($service);
            $service->addHousing($this);
        }
        return $this;
    }

    public function removeMissingService(Service $service): self
    {
        $this->missingServices->removeElement($service);
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

    public function addMissingDefaultFurniture($defaultFurniture)
    {
        if (!$this->missingDefaultFurnitures instanceof ArrayCollection) {
            $this->missingDefaultFurnitures = new ArrayCollection();
        }
        $this->missingDefaultFurnitures->add($defaultFurniture);
    }

    public function getMissingDefaultFurnitures()
    {
        return $this->missingDefaultFurnitures;
    }
}
