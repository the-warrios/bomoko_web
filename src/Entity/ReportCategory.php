<?php

namespace App\Entity;

use App\Repository\ReportCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReportCategoryRepository::class)]
class ReportCategory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $label = null;

    #[ORM\Column(length: 50)]
    private ?string $secretKey = null;

    #[ORM\ManyToOne(inversedBy: 'reportCategories')]
    private ?Report $report_category = null;

    /**
     * @var Collection<int, FineCategoryPricing>
     */
    #[ORM\OneToMany(targetEntity: FineCategoryPricing::class, mappedBy: 'report_pricing')]
    private Collection $fineCategoryPricings;

    public function __construct()
    {
        $this->fineCategoryPricings = new ArrayCollection();
    }

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

    public function getReportCategory(): ?Report
    {
        return $this->report_category;
    }

    public function setReportCategory(?Report $report_category): static
    {
        $this->report_category = $report_category;

        return $this;
    }

    /**
     * @return Collection<int, FineCategoryPricing>
     */
    public function getFineCategoryPricings(): Collection
    {
        return $this->fineCategoryPricings;
    }

    public function addFineCategoryPricing(FineCategoryPricing $fineCategoryPricing): static
    {
        if (!$this->fineCategoryPricings->contains($fineCategoryPricing)) {
            $this->fineCategoryPricings->add($fineCategoryPricing);
            $fineCategoryPricing->setReportPricing($this);
        }

        return $this;
    }

    public function removeFineCategoryPricing(FineCategoryPricing $fineCategoryPricing): static
    {
        if ($this->fineCategoryPricings->removeElement($fineCategoryPricing)) {
            // set the owning side to null (unless already changed)
            if ($fineCategoryPricing->getReportPricing() === $this) {
                $fineCategoryPricing->setReportPricing(null);
            }
        }

        return $this;
    }
}
