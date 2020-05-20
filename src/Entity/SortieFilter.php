<?php
namespace App\Entity;

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
     * @var datetime|null
     */
    private $dateDebut;

    /**
     * @var datetime|null
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
    public function getCampus(): ?string
    {
        return $this->campus;
    }

    /**
     * @param string|null $campus
     * @return SortieFilter
     */
    public function setCampus(?string $campus): SortieFilter
    {
        $this->campus = $campus;
        return $this;
    }

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
     * @return datetime|null
     */
    public function getDateDebut(): ?datetime
    {
        return $this->dateDebut;
    }

    /**
     * @param datetime|null $dateDebut
     * @return SortieFilter
     */
    public function setDateDebut(?datetime $dateDebut): SortieFilter
    {
        $this->dateDebut = $dateDebut;
        return $this;
    }

    /**
     * @return datetime|null
     */
    public function getDateFin(): ?datetime
    {
        return $this->dateFin;
    }

    /**
     * @param datetime|null $dateFin
     * @return SortieFilter
     */
    public function setDateFin(?datetime $dateFin): SortieFilter
    {
        $this->dateFin = $dateFin;
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

}