<?php

namespace App\Entity;

use App\Repository\FineRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FineRepository::class)]
class Fine
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Report $report = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateCreated = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateClosed = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $status = null;

    #[ORM\Column(nullable: true)]
    private ?float $amount = null;

    /**
     * @var Collection<int, FineCategory>
     */
    #[ORM\OneToMany(targetEntity: FineCategory::class, mappedBy: 'fine_category')]
    private Collection $fineCategories;

    public function __construct()
    {
        $this->fineCategories = new ArrayCollection();
        $this->dateCreated = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReport(): ?Report
    {
        return $this->report;
    }

    public function setReport(?Report $report): static
    {
        $this->report = $report;

        return $this;
    }

    public function getDateCreated(): ?\DateTimeInterface
    {
        return $this->dateCreated;
    }

    public function setDateCreated(\DateTimeInterface $dateCreated): static
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }

    public function getDateClosed(): ?\DateTimeInterface
    {
        return $this->dateClosed;
    }

    public function setDateClosed(?\DateTimeInterface $dateClosed): static
    {
        $this->dateClosed = $dateClosed;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(?int $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(?float $amount): static
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * @return Collection<int, FineCategory>
     */
    public function getFineCategories(): Collection
    {
        return $this->fineCategories;
    }

    public function addFineCategory(FineCategory $fineCategory): static
    {
        if (!$this->fineCategories->contains($fineCategory)) {
            $this->fineCategories->add($fineCategory);
            $fineCategory->setFineCategory($this);
        }

        return $this;
    }

    public function removeFineCategory(FineCategory $fineCategory): static
    {
        if ($this->fineCategories->removeElement($fineCategory)) {
            // set the owning side to null (unless already changed)
            if ($fineCategory->getFineCategory() === $this) {
                $fineCategory->setFineCategory(null);
            }
        }

        return $this;
    }
}
