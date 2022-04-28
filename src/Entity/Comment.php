<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommentRepository::class)
 */
class Comment extends BaseEntity
{
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    private $text;

    /**
     * @var Housing | null
     * @ORM\ManyToOne(targetEntity=Housing::class, inversedBy="comments", fetch="EAGER")
     */
    private $housing;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

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
