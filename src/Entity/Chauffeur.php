<?php

namespace App\Entity;

use App\Repository\ChauffeurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ChauffeurRepository::class)]
class Chauffeur
{
    #[ORM\OneToMany(mappedBy: 'chauffeur', targetEntity: Course::class)]
    private $courses;

    public function __construct()
    {
        $this->courses = new ArrayCollection();
    }

    public function getCourses(): Collection
    {
        return $this->courses;
    }

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    private $nom;
    public function getnom(): ?string
    {
        return $this->nom;
    }

    public function setnom(string $nom): self
    {
        $this->statut = $nom;

        return $this;
    }

    #[ORM\Column(type: 'string', length: 255)]
    private $prenoms;
    public function getprenoms(): ?string
    {
        return $this->prenoms;
    }

    public function setprenoms(string $prenoms): self
    {
        $this->statut = $prenoms;

        return $this;
    }

    #[ORM\Column(type: 'string', length: 20)]
    private $telephone;

    #[ORM\Column(type: 'string', length: 10)]
    private $sexe;

    #[ORM\Column(type: 'boolean')]
    private $disponible;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function isDisponible(): bool
    {
        return $this->disponible;
    }

    public function setDisponible(bool $disponible): self
    {
        $this->disponible = $disponible;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): static
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getSexe(): ?string
    {
        return $this->sexe;
    }

    public function setSexe(string $sexe): static
    {
        $this->sexe = $sexe;

        return $this;
    }

    public function addCourse(Course $course): static
    {
        if (!$this->courses->contains($course)) {
            $this->courses->add($course);
            $course->setChauffeur($this);
        }

        return $this;
    }

    public function removeCourse(Course $course): static
    {
        if ($this->courses->removeElement($course)) {
            // set the owning side to null (unless already changed)
            if ($course->getChauffeur() === $this) {
                $course->setChauffeur(null);
            }
        }

        return $this;
    }
}
