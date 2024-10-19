<?php

namespace App\Entity;

use App\Repository\FineCategoryPricingRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FineCategoryPricingRepository::class)]
class FineCategoryPricing
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $montant = null;

    #[ORM\Column(length: 20)]
    private ?string $devise = null;

    #[ORM\ManyToOne(inversedBy: 'fineCategoryPricings')]
    private ?ReportCategory $report_pricing = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): static
    {
        $this->montant = $montant;

        return $this;
    }

    public function getDevise(): ?string
    {
        return $this->devise;
    }

    public function setDevise(string $devise): static
    {
        $this->devise = $devise;

        return $this;
    }

    public function getReportPricing(): ?ReportCategory
    {
        return $this->report_pricing;
    }

    public function setReportPricing(?ReportCategory $report_pricing): static
    {
        $this->report_pricing = $report_pricing;

        return $this;
    }
}
