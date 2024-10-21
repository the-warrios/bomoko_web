<?php

namespace App\Entity;

use App\Repository\ReportCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ReportCategoryRepository::class)]
class ReportCategory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('reportCategory:read')]
    private ?int $id = null;

    #[Groups('reportCategory:read')]
    #[ORM\Column(length: 255)]
    private ?string $label = null;

    #[Groups('reportCategory:read')]
    #[ORM\Column(length: 50)]
    private ?string $secretKey = null;

    /**
     * @var Collection<int, FineCategoryPricing>
     */
    #[ORM\OneToMany(targetEntity: FineCategoryPricing::class, mappedBy: 'report_pricing')]
    private Collection $fineCategoryPricings;

    /**
     * @var Collection<int, Report>
     */
    #[ORM\OneToMany(targetEntity: Report::class, mappedBy: 'reportCategory')]
    private Collection $reports;

    public function __construct()
    {
        $this->fineCategoryPricings = new ArrayCollection();
        $this->reports = new ArrayCollection();
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

    /**
     * @return Collection<int, Report>
     */
    public function getReports(): Collection
    {
        return $this->reports;
    }

    public function addReport(Report $report): static
    {
        if (!$this->reports->contains($report)) {
            $this->reports->add($report);
            $report->setReportCategory($this);
        }

        return $this;
    }

    public function removeReport(Report $report): static
    {
        if ($this->reports->removeElement($report)) {
            // set the owning side to null (unless already changed)
            if ($report->getReportCategory() === $this) {
                $report->setReportCategory(null);
            }
        }

        return $this;
    }
}
