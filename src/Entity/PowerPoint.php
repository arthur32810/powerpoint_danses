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

    /**
     * @ORM\Column(type="smallint")
     */
    private $nbDansesSlides;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $primaryDanseColor;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $secondaryDanseColor;

    public function __construct()
    {
        $this->danses = new ArrayCollection();
        $this->nbDansesSlides = 4;
        $this->primaryDanseColor = '#ffff00';
        $this->secondaryDanseColor = '#ffffff';
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

    public function getNbDansesSlides(): ?int
    {
        return $this->nbDansesSlides;
    }

    public function setNbDansesSlides(int $nbDansesSlides): self
    {
        $this->nbDansesSlides = $nbDansesSlides;

        return $this;
    }

    public function getPrimaryDanseColor(): ?string
    {
        return $this->primaryDanseColor;
    }

    public function setPrimaryDanseColor(string $primaryDanseColor): self
    {
        $this->primaryDanseColor = $primaryDanseColor;

        return $this;
    }

    public function getSecondaryDanseColor(): ?string
    {
        return $this->secondaryDanseColor;
    }

    public function setSecondaryDanseColor(string $secondaryDanseColor): self
    {
        $this->secondaryDanseColor = $secondaryDanseColor;

        return $this;
    }

}
