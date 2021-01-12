<?php

namespace App\Entity;

use App\Repository\CarBrandRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CarBrandRepository::class)
 */
class CarBrand
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
     * @ORM\OneToMany(targetEntity=CarBrand::class, mappedBy="carModel", orphanRemoval=true)
     */
    private $car_make_id;

    /**
     * @ORM\OneToMany(targetEntity=CarBrandModel::class, mappedBy="car_brand", orphanRemoval=true)
     */
    private $carBrandModels;

    public function __construct()
    {
        $this->car_make_id = new ArrayCollection();
        $this->carBrandModels = new ArrayCollection();
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

    /**
     * @return Collection|CarBrand[]
     */
    public function getCarBrandId(): Collection
    {
        return $this->car_make_id;
    }

    public function addCarBrandId(CarBrand $carMakeId): self
    {
        if (!$this->car_make_id->contains($carMakeId)) {
            $this->car_make_id[] = $carMakeId;
            $carMakeId->setCarModel($this);
        }

        return $this;
    }

    public function removeCarBrandId(CarBrand $carMakeId): self
    {
        if ($this->car_make_id->contains($carMakeId)) {
            $this->car_make_id->removeElement($carMakeId);
            // set the owning side to null (unless already changed)
            if ($carMakeId->getCarModel() === $this) {
                $carMakeId->setCarModel(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|CarBrandModel[]
     */
    public function getCarBrandModels(): Collection
    {
        return $this->carBrandModels;
    }

    public function addCarBrandModel(CarBrandModel $carBrandModel): self
    {
        if (!$this->carBrandModels->contains($carBrandModel)) {
            $this->carBrandModels[] = $carBrandModel;
            $carBrandModel->setCarBrand($this);
        }

        return $this;
    }

    public function removeCarBrandModel(CarBrandModel $carBrandModel): self
    {
        if ($this->carBrandModels->contains($carBrandModel)) {
            $this->carBrandModels->removeElement($carBrandModel);
            // set the owning side to null (unless already changed)
            if ($carBrandModel->getCarBrand() === $this) {
                $carBrandModel->setCarBrand(null);
            }
        }

        return $this;
    }
}
