<?php

namespace App\Entity;

use App\Repository\OpportunityLogRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\Traits\Timestampable;
use App\Repository\OpportunityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;


#[ORM\Entity(repositoryClass: OpportunityLogRepository::class)]
class OpportunityLog
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $changedAt = null;


    #[ORM\ManyToOne(targetEntity: Opportunity::class, inversedBy: 'opportunityLogs')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Opportunity $opportunity = null;

    #[ORM\ManyToOne(targetEntity: OpportunityStage::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?OpportunityStage $stage = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $amount = null;

    #[ORM\Column(nullable: true)]
    private ?float $engagement = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getChangedAt(): ?\DateTimeImmutable
    {
        return $this->changedAt;
    }

    public function setChangedAt(\DateTimeImmutable $changedAt): static
    {
        $this->changedAt = $changedAt;

        return $this;
    }

    public function getOpportunity(): ?Opportunity
    {
        return $this->opportunity;
    }

    public function setOpportunity(?Opportunity $opportunity): static
    {
        $this->opportunity = $opportunity;

        return $this;
    }

    public function getStage(): ?OpportunityStage
    {
        return $this->stage;
    }

    public function setStage(?OpportunityStage $stage): static
    {
        $this->stage = $stage;

        return $this;
    }

    public function getAmount(): ?string
    {
        return $this->amount;
    }

    public function setAmount(?string $amount): static
    {
        $this->amount = $amount;

        return $this;
    }

    public function getEngagement(): ?float
    {
        return $this->engagement;
    }

    public function setEngagement(?float $engagement): static
    {
        $this->engagement = $engagement;

        return $this;
    }
}
