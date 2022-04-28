<?php

namespace App\Entity;

use App\Repository\HousingRepository;
use App\Service\HousingStatusResolver;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=HousingRepository::class)
 */
class Housing extends BaseEntity
{
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
    private $missingFurnitures;

    /**
     * @var User | null
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="maintainedHousings", fetch="EAGER")
     */
    private $maintainer;

    /**
     * @ORM\ManyToMany(targetEntity=Service::class, inversedBy="housings", cascade={"persist"})
     *
     * @var Collection
     */
    private $missingServices;

    /**
     * @ORM\ManyToMany(targetEntity=Tag::class, inversedBy="housings", cascade={"persist"})
     *
     * @var Collection
     */
    private $tags;

    /**
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    private $status;

    /**
     * @var Collection
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="housing")
     */
    private $comments;

    public function __construct()
    {
        $this->missingFurnitures = new ArrayCollection();
        $this->missingServices = new ArrayCollection();
        $this->tags = new ArrayCollection();
        $this->comments = new ArrayCollection();
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
    public function getMissingFurnitures(): Collection
    {
        return $this->missingFurnitures;
    }

    public function addMissingFurniture(Furniture $furniture): self
    {
        if (!$this->missingFurnitures->contains($furniture)) {
            $this->missingFurnitures->add($furniture);
            $furniture->addHousing($this);
        }
        return $this;
    }

    public function removeMissingFurniture(Furniture $furniture): self
    {
        $this->missingFurnitures->removeElement($furniture);
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
     * @return Collection|Tag[]
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
            $tag->addHousing($this);
        }
        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        $this->tags->removeElement($tag);
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
     * @return string
     */
    public function getStatusLabel(): string
    {
        return HousingStatusResolver::getHousingStatusLabel($this->status);
    }

    /**
     * @param int $status
     */
    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    /**
     * @return User|null
     */
    public function getMaintainer(): ?User
    {
        return $this->maintainer;
    }

    /**
     * @param User|null $maintainer
     */
    public function setMaintainer(?User $maintainer): void
    {
        $this->maintainer = $maintainer;
    }

    /**
     * @return Collection
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    /**
     * @param Collection $comments
     */
    public function setComments(Collection $comments): void
    {
        $this->comments = $comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setHousing($this);
        }
        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        $this->comments->removeElement($comment);
        return $this;
    }
}
