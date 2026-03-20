<?php

namespace App\Entity;

use App\Repository\OpportunityStageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: OpportunityStageRepository::class)]
class OpportunityStage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 75, nullable: false)]
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 2,
        max: 75
    )]
    private ?string $name = null;

    #[ORM\Column]
    #[Assert\PositiveOrZero]
    private ?int $position = null;

    #[ORM\Column]
    #[Assert\PositiveOrZero]
    private ?int $probability = null;

    /**
     * @var Collection<int, Opportunity>
     */
    #[ORM\OneToMany(targetEntity: Opportunity::class, mappedBy: 'opportunityStage', orphanRemoval: true)]
    private Collection $opportunities;

    /**
     * @var Collection<int, OpportunityLog>
     */
    #[ORM\OneToMany(targetEntity: OpportunityLog::class, mappedBy: 'stage', orphanRemoval: true)]
    private Collection $yes;

    public function __construct()
    {
        $this->opportunities = new ArrayCollection();
        $this->yes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): static
    {
        $this->position = $position;

        return $this;
    }

    public function getProbability(): ?int
    {
        return $this->probability;
    }

    public function setProbability(int $probability): static
    {
        $this->probability = $probability;

        return $this;
    }

    public function __toString(): string
    {
        return $this->name;
    }

    /**
     * @return Collection<int, Opportunity>
     */
    public function getOpportunities(): Collection
    {
        return $this->opportunities;
    }

    public function addOpportunity(Opportunity $opportunity): static
    {
        if (!$this->opportunities->contains($opportunity)) {
            $this->opportunities->add($opportunity);
            $opportunity->setOpportunityStage($this);
        }

        return $this;
    }

    public function removeOpportunity(Opportunity $opportunity): static
    {
        if ($this->opportunities->removeElement($opportunity)) {
            // set the owning side to null (unless already changed)
            if ($opportunity->getOpportunityStage() === $this) {
                $opportunity->setOpportunityStage(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, OpportunityLog>
     */
    public function getYes(): Collection
    {
        return $this->yes;
    }

    public function addYe(OpportunityLog $ye): static
    {
        if (!$this->yes->contains($ye)) {
            $this->yes->add($ye);
            $ye->setStage($this);
        }

        return $this;
    }

    public function removeYe(OpportunityLog $ye): static
    {
        if ($this->yes->removeElement($ye)) {
            if ($ye->getStage() === $this) {
            }
        }

        return $this;
    }
}
