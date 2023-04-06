<?php

namespace App\Entity;

use App\Repository\AddressRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AddressRepository::class)]
class Address
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $street = null;

    #[ORM\Column(length: 255)]
    private ?string $city = null;

    #[ORM\Column]
    private ?int $zip = null;

    #[ORM\ManyToOne(inversedBy: 'address')]
    private ?User $user = null;

    #[ORM\OneToMany(mappedBy: 'address', targetEntity: Acquisition::class)]
    private Collection $acquisitions;

    public function __construct()
    {
        $this->acquisitions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(string $street): self
    {
        $this->street = $street;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getZip(): ?int
    {
        return $this->zip;
    }

    public function setZip(int $zip): self
    {
        $this->zip = $zip;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Acquisition>
     */
    public function getAcquisitions(): Collection
    {
        return $this->acquisitions;
    }

    public function addAcquisition(Acquisition $acquisition): self
    {
        if (!$this->acquisitions->contains($acquisition)) {
            $this->acquisitions->add($acquisition);
            $acquisition->setAddress($this);
        }

        return $this;
    }

    public function removeAcquisition(Acquisition $acquisition): self
    {
        if ($this->acquisitions->removeElement($acquisition)) {
            // set the owning side to null (unless already changed)
            if ($acquisition->getAddress() === $this) {
                $acquisition->setAddress(null);
            }
        }

        return $this;
    }
}
