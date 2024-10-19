<?php

namespace App\Entity;

use App\Repository\FineCategoryRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FineCategoryRepository::class)]
class FineCategory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $label = null;

    #[ORM\Column(length: 150)]
    private ?string $secretKey = null;

    #[ORM\ManyToOne(inversedBy: 'fineCategories')]
    private ?Fine $fine_category = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): static
    {
        $this->label = $label;

        return $this;
    }

    public function getSecretKey(): ?string
    {
        return $this->secretKey;
    }

    public function setSecretKey(string $secretKey): static
    {
        $this->secretKey = $secretKey;

        return $this;
    }

    public function getFineCategory(): ?Fine
    {
        return $this->fine_category;
    }

    public function setFineCategory(?Fine $fine_category): static
    {
        $this->fine_category = $fine_category;

        return $this;
    }
}
