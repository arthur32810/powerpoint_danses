<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @ORM\OneToMany(targetEntity="App\Entity\Danse", mappedBy="powerPoint", orphanRemoval=true, cascade={"all"})
     * @Assert\Valid
     */
    private $danses;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="powerpoint")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
}
