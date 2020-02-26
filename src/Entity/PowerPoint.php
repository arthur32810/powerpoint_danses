<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PowerPointRepository")
 */
class PowerPoint
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Danse", mappedBy="powerPoint", orphanRemoval=true)
     */
    private $danses;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="powerpoint")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function __construct()
    {
        $this->danses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Danse[]
     */
    public function getDanses(): Collection
    {
        return $this->danses;
    }

    public function addDanse(Danse $danse): self
    {
        if (!$this->danses->contains($danse)) {
            $this->danses[] = $danse;
            $danse->setPowerPoint($this);
        }

        return $this;
    }

    public function removeDanse(Danse $danse): self
    {
        if ($this->danses->contains($danse)) {
            $this->danses->removeElement($danse);
            // set the owning side to null (unless already changed)
            if ($danse->getPowerPoint() === $this) {
                $danse->setPowerPoint(null);
            }
        }

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
}
