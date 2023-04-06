<?php

namespace App\Entity;

use App\Repository\AcquisitionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AcquisitionRepository::class)]
class Acquisition
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToMany(mappedBy: 'acquisition', targetEntity: User::class)]
    private Collection $user;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Annonce $annonce = null;

    #[ORM\OneToMany(mappedBy: 'acquisition', targetEntity: Address::class)]
    private Collection $adress;

    public function __construct()
    {
        $this->user = new ArrayCollection();
        $this->adress = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUser(): Collection
    {
        return $this->user;
    }

    public function addUser(User $user): self
    {
        if (!$this->user->contains($user)) {
            $this->user->add($user);
            $user->setAcquisition($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->user->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getAcquisition() === $this) {
                $user->setAcquisition(null);
            }
        }

        return $this;
    }

    public function getAnnonce(): ?Annonce
    {
        return $this->annonce;
    }

    public function setAnnonce(?Annonce $annonce): self
    {
        $this->annonce = $annonce;

        return $this;
    }

    /**
     * @return Collection<int, Address>
     */
    public function getAdress(): Collection
    {
        return $this->adress;
    }

    public function addAdress(Address $adress): self
    {
        if (!$this->adress->contains($adress)) {
            $this->adress->add($adress);
            $adress->setAcquisition($this);
        }

        return $this;
    }

    public function removeAdress(Address $adress): self
    {
        if ($this->adress->removeElement($adress)) {
            // set the owning side to null (unless already changed)
            if ($adress->getAcquisition() === $this) {
                $adress->setAcquisition(null);
            }
        }

        return $this;
    }
}
