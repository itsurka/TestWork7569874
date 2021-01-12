<?php

namespace App\Entity;

use App\Repository\CarRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\PrePersist;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CarRepository::class)
 */
class Car
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=200)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=2500, nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=City::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $city;

    /**
     * @ORM\ManyToOne(targetEntity=CarBrand::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $brand;

    /**
     * @ORM\ManyToOne(targetEntity=CarBrandModel::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $brand_model;

    /**
     * @ORM\Column(type="smallint")
     * @Assert\Range(
     *     minPropertyPath="prodYearMin",
     *     maxPropertyPath="prodYearMax"
     * )
     */
    private $prod_year;

    /**
     * @var int
     */
    private $prodYearMin;

    /**
     * @var int
     */
    private $prodYearMax;

    /**
     * @ORM\Column(type="smallint")
     */
    private $body_type;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $seats;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $fuel;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $engine_capacity;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $gearbox_type;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $wheel_drive;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\Range(
     *     min="0",
     *     max="999999"
     * )
     */
    private $odometer;

    /**
     * @ORM\Column(type="smallint")
     */
    private $status;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updated_at;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     */
    private $owner;

    /**
     * @ORM\OneToMany(targetEntity=CarImage::class, mappedBy="car", orphanRemoval=true)
     */
    private $images;

    public function __construct()
    {
        $year = (int)(new \DateTime())->format('Y');
        $this->prod_year_min = $year - 70;
        $this->prod_year_max = $year + 1;
        $this->images = new ArrayCollection();
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

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCity(): ?City
    {
        return $this->city;
    }

    public function setCity(City $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getBrand(): ?CarBrand
    {
        return $this->brand;
    }

    public function setBrand(CarBrand $brand): self
    {
        $this->brand = $brand;

        return $this;
    }

    public function getBrandModel(): ?CarBrandModel
    {
        return $this->brand_model;
    }

    public function setBrandModel(CarBrandModel $brand_model): self
    {
        $this->brand_model = $brand_model;

        return $this;
    }

    public function getProdYear(): ?int
    {
        return $this->prod_year;
    }

    public function setProdYear(int $prod_year): self
    {
        $this->prod_year = $prod_year;

        return $this;
    }

    public function getBodyType(): ?int
    {
        return $this->body_type;
    }

    public function setBodyType(int $body_type): self
    {
        $this->body_type = $body_type;

        return $this;
    }

    public function getSeats(): ?int
    {
        return $this->seats;
    }

    public function setSeats(?int $seats): self
    {
        $this->seats = $seats;

        return $this;
    }

    public function getFuel(): ?int
    {
        return $this->fuel;
    }

    public function setFuel(?int $fuel): self
    {
        $this->fuel = $fuel;

        return $this;
    }

    public function getEngineCapacity(): ?int
    {
        return $this->engine_capacity;
    }

    public function setEngineCapacity(?int $engine_capacity): self
    {
        $this->engine_capacity = $engine_capacity;

        return $this;
    }

    public function getGearboxType(): ?int
    {
        return $this->gearbox_type;
    }

    public function setGearboxType(?int $gearbox_type): self
    {
        $this->gearbox_type = $gearbox_type;

        return $this;
    }

    public function getWheelDrive(): ?int
    {
        return $this->wheel_drive;
    }

    public function setWheelDrive(?int $wheel_drive): self
    {
        $this->wheel_drive = $wheel_drive;

        return $this;
    }

    public function getOdometer(): ?int
    {
        return $this->odometer;
    }

    public function setOdometer(?int $odometer): self
    {
        $this->odometer = $odometer;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * @return int
     */
    public function getProdYearMax(): int
    {
        return $this->prod_year_max;
    }

    /**
     * @param int $prod_year_max
     */
    public function setProdYearMax(int $prod_year_max): void
    {
        $this->prod_year_max = $prod_year_max;
    }

    /**
     * @return int
     */
    public function getProdYearMin(): int
    {
        return $this->prod_year_min;
    }

    /**
     * @param int $prod_year_min
     */
    public function setProdYearMin(int $prod_year_min): void
    {
        $this->prod_year_min = $prod_year_min;
    }

    /**
     * @return Collection|CarImage[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(CarImage $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setCar($this);
        }

        return $this;
    }

    public function removeImage(CarImage $image): self
    {
        if ($this->images->contains($image)) {
            $this->images->removeElement($image);
            // set the owning side to null (unless already changed)
            if ($image->getCar() === $this) {
                $image->setCar(null);
            }
        }

        return $this;
    }
}
