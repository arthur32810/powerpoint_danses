<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DanseRepository")
 */
class Danse
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="smallint")
     * @Assert\PositiveOrZero
     */
    private $position_playlist;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\PowerPoint", inversedBy="danses")
     * @ORM\JoinColumn(nullable=false)
     */
    private $powerPoint;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPositionPlaylist(): ?int
    {
        return $this->position_playlist;
    }

    public function setPositionPlaylist(int $position_playlist): self
    {
        $this->position_playlist = $position_playlist;

        return $this;
    }

    public function getPowerPoint(): ?PowerPoint
    {
        return $this->powerPoint;
    }

    public function setPowerPoint(?PowerPoint $powerPoint): self
    {
        $this->powerPoint = $powerPoint;

        return $this;
    }
}
