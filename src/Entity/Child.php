<?php

namespace App\Entity;

use App\Repository\ChildRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ChildRepository::class)
 */
class Child
{
    private const GIFTED = 1;
    private const NOTGIFTED = 0;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Stock::class, inversedBy="children")
     * @ORM\JoinColumn(nullable=false)
     */
    private $stock;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $information;

    /**
     * @ORM\Column(type="integer")
     */
    private $serial_number;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $institution_name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $group_name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $reservation_nickname;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_gifted;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStock(): ?Stock
    {
        return $this->stock;
    }

    public function setStock(?Stock $stock): self
    {
        $this->stock = $stock;

        return $this;
    }

    public function getInformation(): ?string
    {
        return $this->information;
    }

    public function setInformation(string $information): self
    {
        $this->information = $information;

        return $this;
    }

    public function getSerialNumber(): ?int
    {
        return $this->serial_number;
    }

    public function setSerialNumber(int $serial_number): self
    {
        $this->serial_number = $serial_number;

        return $this;
    }

    public function getInstitutionName(): ?string
    {
        return $this->institution_name;
    }

    public function setInstitutionName(string $institution_name): self
    {
        $this->institution_name = $institution_name;

        return $this;
    }

    public function getGroupName(): ?string
    {
        return $this->group_name;
    }

    public function setGroupName(?string $group_name): self
    {
        $this->group_name = $group_name;

        return $this;
    }

    public function getReservationNickname(): ?string
    {
        return $this->reservation_nickname;
    }

    public function setReservationNickname(?string $reservation_nickname): self
    {
        $this->reservation_nickname = $reservation_nickname;

        return $this;
    }

    public function getIsGifted(): ?bool
    {
        return $this->is_gifted;
    }

    public function setIsGifted()
    {
        $this->is_gifted = self::GIFTED;
    }

    public function setIsNotGifted()
    {
        $this->is_gifted = self::NOTGIFTED;
    }
}
