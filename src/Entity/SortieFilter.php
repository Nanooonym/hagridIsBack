<?php
namespace App\Entity;

use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

class SortieFilter {

    /**
     * @var string|null
     */
    private $campus;

    /**
     * @var string|null
     */
    private $name;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateDebut;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\GreaterThan(propertyPath="dateDebut", message="La date de fin doit être supérieure à la date de début")
     */
    private $dateFin;

    /**
     * @var boolean|null
     */
    private $isOrganisateur;

    /**
     * @var boolean|null
     */
    private $isInscrit;

    /**
     * @var boolean|null
     */
    private $isNotInscrit;

    /**
     * @var boolean|null
     */
    private $passee;

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     * @return SortieFilter
     */
    public function setName(?string $name): SortieFilter
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return bool|null
     */
    public function getIsOrganisateur(): ?bool
    {
        return $this->isOrganisateur;
    }

    /**
     * @param bool|null $isOrganisateur
     * @return SortieFilter
     */
    public function setIsOrganisateur(?bool $isOrganisateur): SortieFilter
    {
        $this->isOrganisateur = $isOrganisateur;
        return $this;
    }

    /**
     * @return bool|null
     */
    public function getIsInscrit(): ?bool
    {
        return $this->isInscrit;
    }

    /**
     * @param bool|null $isInscrit
     * @return SortieFilter
     */
    public function setIsInscrit(?bool $isInscrit): SortieFilter
    {
        $this->isInscrit = $isInscrit;
        return $this;
    }

    /**
     * @return bool|null
     */
    public function getIsNotInscrit(): ?bool
    {
        return $this->isNotInscrit;
    }

    /**
     * @param bool|null $isNotInscrit
     * @return SortieFilter
     */
    public function setIsNotInscrit(?bool $isNotInscrit): SortieFilter
    {
        $this->isNotInscrit = $isNotInscrit;
        return $this;
    }

    /**
     * @return bool|null
     */
    public function getPassee(): ?bool
    {
        return $this->passee;
    }

    /**
     * @param bool|null $passee
     * @return SortieFilter
     */
    public function setPassee(?bool $passee): SortieFilter
    {
        $this->passee = $passee;
        return $this;
    }

    public function getCampus(): ?Campus
    {
        return $this->campus;
    }

    public function setCampus(?Campus $campus): self
    {
        $this->campus = $campus;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(?\DateTimeInterface $dateDebut): self
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(?\DateTimeInterface $dateFin): self
    {
        $this->dateFin = $dateFin;

        return $this;
    }


}