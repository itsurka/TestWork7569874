<?php

namespace App\Entity;

use App\Repository\CarBrandModelRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CarBrandModelRepository::class)
 */
class CarBrandModel
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $title;

    /**
     * @ORM\ManyToOne(targetEntity=CarBrand::class, inversedBy="carBrandModels")
     * @ORM\JoinColumn(nullable=false)
     */
    private $car_brand;

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

    public function getCarBrand(): ?CarBrand
    {
        return $this->car_brand;
    }

    public function setCarBrand(?CarBrand $car_brand): self
    {
        $this->car_brand = $car_brand;

        return $this;
    }
}
