<?php

namespace App\Entity;

use App\Repository\ServiceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=ServiceRepository::class)
 * @UniqueEntity(fields={"title"}, message="Eine Dienstleistung mit der Bezeichnung existiert bereits.")
 */
class Service extends BaseEntity
{
    /**
     * @ORM\Column(type="text")
     *
     * @var string
     */
    private $title;

    /**
     * @ORM\ManyToMany(targetEntity=Housing::class, mappedBy="missingServices")
     *
     * @var Collection
     */
    private $housings;

    /**
     * @ORM\ManyToMany(targetEntity=Supporter::class, mappedBy="availableServices")
     *
     * @var Collection
     */
    private $supporter;

    public function __construct()
    {
        $this->housings = new ArrayCollection();
        $this->supporter = new ArrayCollection();
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
     * @return Collection|Housing[]
     */
    public function getHousings(): Collection
    {
        return $this->housings;
    }

    /**
     * @param Collection $housings
     * @return Collection
     */
    public function setHousings(Collection $housings): Collection
    {
        $this->housings->clear();
        foreach ($housings as $housing) {
            $this->addHousing($housing);
        }
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
     * @return Collection|Housing[]
     */
    public function getSupporter(): Collection
    {
        return $this->supporter;
    }

    /**
     * @param Collection $supporter
     * @return Collection
     */
    public function setSupporter(Collection $supporter): Collection
    {
        $this->supporter->clear();
        foreach ($supporter as $currentSupporter) {
            $this->addSupporter($currentSupporter);
        }
        return $this->supporter;
    }

    public function addSupporter(Supporter $supporter): self
    {
        if (!$this->supporter->contains($supporter)) {
            $this->supporter->add($supporter);
        }
        return $this;
    }

    public function removeSupporter(Supporter $supporter): self
    {
        $this->supporter->removeElement($supporter);
        return $this;
    }

    public function getMissingAmount(): int
    {
        return $this->housings->filter(function (Housing $housing) {
            return $housing->getStatus() >= 0;
        })->count();
    }
}
