<?php

namespace App\Entity;

use App\Repository\ImportCSVRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ImportCSVRepository::class)
 */
class ImportCSV
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\File(
     *     mimeTypes = {"text/csv", "application/vnd.ms-excel", "text/plain"},
     *     mimeTypesMessage = "Vous ne pouvez uploader que des fichiers .csv"
     *     )
     */

    private $file;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFile(): ?string
    {
        return $this->file;
    }

    public function setFile(string $file): self
    {
        $this->file = $file;

        return $this;
    }
}
