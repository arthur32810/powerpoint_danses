<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use DateTime;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PowerPointRepository")
 * @Vich\Uploadable()
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

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $backgroundSlides;

    /**
     * @Vich\UploadableField(mapping="powerpoint_backgroundSlides", fileNameProperty="backgroundSlides")
     * @var File
     * @Assert\Image(
     *      allowPortrait = false,
     * )
     */
    private $backgroundSlidesImageFile;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $backgroundSlidesUpdatedAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private $backgroundSlidesDefaut;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated_at;


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

    public function getBackgroundSlides(): ?string
    {
        return $this->backgroundSlides;
    }

    public function setBackgroundSlides(?string $backgroundSlides): self
    {
        $this->backgroundSlides = $backgroundSlides;

        return $this;
    }

    /**
     * @param File $backgroundSlidesImageFile
     */
    public function setBackgroundSlidesImageFile(?File $backgroundSlidesImageFile = null): void
    {
        $this->backgroundSlidesImageFile = $backgroundSlidesImageFile;

        if (null !== $backgroundSlidesImageFile) {
            $this->backgroundSlidesUpdatedAt = new \DateTimeImmutable();
        }
    }

    /**
     * @return File
     */
    public function getBackgroundSlidesImageFile(): ?File
    {
        return $this->backgroundSlidesImageFile;
    }

    public function getBackgroundSlidesUpdatedAt(): ?\DateTimeInterface
    {
        return $this->backgroundSlidesUpdatedAt;
    }

    public function setBackgroundSlidesUpdatedAt(?\DateTimeInterface $backgroundSlidesUpdatedAt): self
    {
        $this->backgroundSlidesUpdatedAt = $backgroundSlidesUpdatedAt;

        return $this;
    }

    public function getBackgroundSlidesDefaut(): ?bool
    {
        return $this->backgroundSlidesDefaut;
    }

    public function setBackgroundSlidesDefaut(bool $backgroundSlidesDefaut): self
    {
        $this->backgroundSlidesDefaut = $backgroundSlidesDefaut;

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
}