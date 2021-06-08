<?php

namespace App\Entity;

use App\Repository\StockRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\StockRepository", repositoryClass=StockRepository::class)
 */
class Stock
{
    private const PUBLISHED = 1;
    private const DRAFT = 0;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=500)
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $image;

    /**
     * @ORM\Column(type="datetime")
     */

    private $date_start;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_end;

    /**
     * @ORM\Column(type="datetime")
     */
    private $create_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $update_at;

    /**
     * @ORM\OneToMany(targetEntity=Child::class, mappedBy="stock")
     */
    private $children;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $rules;

    /**
     * @ORM\OneToMany(targetEntity=PhotoReport::class, mappedBy="stock")
     */
    private $photoReports;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $thanks;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_published;

    public function __construct()
    {
        $this->children = new ArrayCollection();
        $this->photoReports = new ArrayCollection();
    }

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage(string $image)
    {
        $this->image = $image;

        return $this;
    }

    public function getDateStart(): ?\DateTimeInterface
    {
        return $this->date_start;
    }

    public function setDateStart(\DateTimeInterface $date_start): self
    {
        $this->date_start = $date_start;

        return $this;
    }

    public function getDateEnd(): ?\DateTimeInterface
    {
        return $this->date_end;
    }

    public function setDateEnd(\DateTimeInterface $date_end): self
    {
        $this->date_end = $date_end;

        return $this;
    }

    public function getCreateAt(): ?\DateTimeInterface
    {
        return $this->create_at;
    }

    public function setCreateAt(\DateTimeInterface $create_at): self
    {
        $this->create_at = $create_at;

        return $this;
    }

    public function setCreateAtValue()
    {
        $this->create_at = (new \DateTime())->modify('+7 hour');
    }

    public function getUpdateAt(): ?\DateTimeInterface
    {
        return $this->update_at;
    }

    public function setUpdateAt(\DateTimeInterface $update_at): self
    {
        $this->update_at = $update_at;

        return $this;
    }

    public function setUpdateAtValue()
    {
        $this->update_at = (new \DateTime())->modify('+7 hour');
    }

    /**
     * @return Collection|Child[]
     */
    public function getChildren(): Collection
    {
        return $this->children;
    }

    public function addChild(Child $child): self
    {
        if (!$this->children->contains($child)) {
            $this->children[] = $child;
            $child->setStock($this);
        }

        return $this;
    }

    public function removeChild(Child $child): self
    {
        if ($this->children->contains($child)) {
            $this->children->removeElement($child);
            // set the owning side to null (unless already changed)
            if ($child->getStock() === $this) {
                $child->setStock(null);
            }
        }

        return $this;
    }

    public function getRules(): ?string
    {
        return $this->rules;
    }

    public function setRules(?string $rules): self
    {
        $this->rules = $rules;

        return $this;
    }

    /**
     * @return Collection|PhotoReport[]
     */
    public function getPhotoReports(): Collection
    {
        return $this->photoReports;
    }

    public function addPhotoReport(PhotoReport $photoReport): self
    {
        if (!$this->photoReports->contains($photoReport)) {
            $this->photoReports[] = $photoReport;
            $photoReport->setStock($this);
        }

        return $this;
    }

    public function removePhotoReport(PhotoReport $photoReport): self
    {
        if ($this->photoReports->contains($photoReport)) {
            $this->photoReports->removeElement($photoReport);
            // set the owning side to null (unless already changed)
            if ($photoReport->getStock() === $this) {
                $photoReport->setStock(null);
            }
        }

        return $this;
    }

    public function getThanks(): ?string
    {
        return $this->thanks;
    }

    public function setThanks(?string $thanks): self
    {
        $this->thanks = $thanks;

        return $this;
    }

    public function getIsPublished(): ?bool
    {
        return $this->is_published;
    }

    public function setIsPublished()
    {
        $this->is_published = self::PUBLISHED;
    }

    public function setIsDraft()
    {
        $this->is_published = self::DRAFT;
    }

}
