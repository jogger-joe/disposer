<?php

namespace App\Entity;

use App\Repository\FurnitureRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FurnitureRepository::class)
 */
class Furniture
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     *
     * @var string
     */
    private $title;

    /**
     * @ORM\Column(type="boolean")
     *
     * @var bool
     */
    private $default = false;

    /**
     * @ORM\ManyToMany(targetEntity=Housing::class, inversedBy="furnitures")
     *
     * @var Collection
     */
    private $housings;

    public function __construct()
    {
        $this->housings = new ArrayCollection();
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
     * @return bool
     */
    public function isDefault(): bool
    {
        return $this->default;
    }

    /**
     * @param bool $default
     */
    public function setDefault(bool $default): void
    {
        $this->default = $default;
    }

    /**
     * @return Collection|Housing[]
     */
    public function getHousings(): Collection
    {
        return $this->housings;
    }

    public function addHousing(Housing $housing): self
    {
        if (!$this->housings->contains($housing)) {
            $this->housings[] = $housing;
        }
        return $this;
    }

    public function removeHousing(Housing $housing): self
    {
        $this->housings->removeElement($housing);
        return $this;
    }
}
