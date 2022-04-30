<?php

namespace App\Entity;

use App\Repository\HousingFileRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=HousingFileRepository::class)
 */
class HousingFile extends BaseEntity
{
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $filename;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @var Housing | null
     * @ORM\ManyToOne(targetEntity=Housing::class, inversedBy="comments", fetch="EAGER")
     */
    private $housing;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFilename(): ?string
    {
        return $this->filename;
    }

    public function setFilename(string $filename): self
    {
        $this->filename = $filename;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return Housing|null
     */
    public function getHousing(): ?Housing
    {
        return $this->housing;
    }

    /**
     * @param Housing|null $housing
     */
    public function setHousing(?Housing $housing): void
    {
        $this->housing = $housing;
    }
}
