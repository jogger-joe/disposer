<?php

namespace App\Entity;

use App\Repository\FurnitureRepository;
use App\Service\FurnitureTypeResolver;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=FurnitureRepository::class)
 * @UniqueEntity(fields={"title", "type"}, message="Ein Gegenstand mit der Bezeichnung und dem Typ existiert bereits.")
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
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    private $type = 0;

    /**
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    private $amount = 0;

    /**
     * @ORM\ManyToMany(targetEntity=Housing::class, mappedBy="missingFurnitures")
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
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getTypeLabel(): string
    {
        return FurnitureTypeResolver::getFurnitureTypeLabel($this->type);
    }

    /**
     * @param int $type
     */
    public function setType(int $type): void
    {
        $this->type = $type;
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
            $this->housings->add($housing);
        }
        return $this;
    }

    public function removeHousing(Housing $housing): self
    {
        $this->housings->removeElement($housing);
        return $this;
    }

    /**
     * @return int
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

    /**
     * @param int $amount
     */
    public function setAmount(int $amount): void
    {
        $this->amount = $amount;
    }

    public function getMissingAmount(): int
    {
        return $this->housings->filter(function (Housing $housing) {
            return $housing->getStatus() >= 0;
        })->count();
    }
}
