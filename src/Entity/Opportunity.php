<?php

namespace App\Entity;

use App\Entity\Contracts\SluggableInterface;
use App\Entity\Traits\Sluggable;
use App\Entity\Traits\SoftDeleteable;
use App\Entity\Traits\Timestampable;
use App\Repository\OpportunityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Enum\Priority;

#[ORM\Entity(repositoryClass: OpportunityRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ORM\Table(indexes: [
    new ORM\Index(name: "title_idx", columns: ["title"]),
])]

class Opportunity implements SluggableInterface
{

    use Timestampable;
    use SoftDeleteable;
    use Sluggable;

    public function getSlugSource(): ?string
    {
        return $this->account . ' ' . $this->title;
    }

    public function getSlugFields(): array
    {
        return ['account', 'title'];
    }

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(
        min: 2,
        max: 100,
    )]
    private ?string $title = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    #[Assert\PositiveOrZero]
    private ?string $amount = null;

    #[ORM\Column(enumType: Priority::class, nullable: false)]
    private Priority $priority = Priority::MEDIUM;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $expected_close_date = null;

    /**
     * @var Collection<int, OpportunityContact>
     */
    #[ORM\OneToMany(targetEntity: OpportunityContact::class, mappedBy: 'opportunity', orphanRemoval: true, cascade: ['persist'])]
    private Collection $opportunityContacts;

    #[ORM\ManyToOne(targetEntity: Account::class, inversedBy: 'opportunities')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Account $account = null;

    #[ORM\ManyToOne(inversedBy: 'opportunities')]
    #[ORM\JoinColumn(nullable: false)]
    private ?OpportunityStage $opportunityStage = null;

    /**
     * @var Collection<int, OpportunityLog>
     */
    #[ORM\OneToMany(targetEntity: OpportunityLog::class, mappedBy: 'opportunity', orphanRemoval: true)]
    private Collection $opportunityLogs;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $content = null;

    /**
     * @var Collection<int, Activity>
     */
    #[ORM\OneToMany(targetEntity: Activity::class, mappedBy: 'opportunity')]
    private Collection $activities;

    public function __construct()
    {
        $this->opportunityContacts = new ArrayCollection();
        $this->opportunityLogs = new ArrayCollection();
        $this->activities = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

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

    public function getPriority(): ?Priority
    {
        return $this->priority;
    }

    public function setPriority(?Priority $priority): static
    {
        $this->priority = $priority;

        return $this;
    }

    public function getExpectedCloseDate(): ?\DateTimeImmutable
    {
        return $this->expected_close_date;
    }

    public function setExpectedCloseDate(?\DateTimeImmutable $expected_close_date): static
    {
        $this->expected_close_date = $expected_close_date;

        return $this;
    }

    public function getEngagement(): ?float
    {
        if (!$this->amount || !$this->opportunityStage) {
            return null;
        }

        return $this->amount * ($this->opportunityStage->getProbability() / 100);
    }


    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function __toString(): string
    {
        return $this->title ?? '';
    }

    /**
     * @return Collection<int, OpportunityContact>
     */
    public function getOpportunityContacts(): Collection
    {
        return $this->opportunityContacts;
    }

    public function addOpportunityContact(OpportunityContact $opportunityContact): static
    {
        if (!$this->opportunityContacts->contains($opportunityContact)) {
            $this->opportunityContacts->add($opportunityContact);
            $opportunityContact->setOpportunity($this);
        }

        return $this;
    }

    public function removeOpportunityContact(OpportunityContact $opportunityContact): static
    {
        if ($this->opportunityContacts->removeElement($opportunityContact)) {
            // `opportunity` is non-nullable, so the link must be removed rather than nulled.
            if ($opportunityContact->getOpportunity() === $this) {
                // $opportunityContact->setOpportunity(null);
            }
        }

        return $this;
    }

    public function getAccount(): ?Account
    {
        return $this->account;
    }

    public function setAccount(?Account $account): static
    {
        $this->account = $account;

        return $this;
    }

    public function getOpportunityStage(): ?OpportunityStage
    {
        return $this->opportunityStage;
    }

    public function setOpportunityStage(?OpportunityStage $opportunityStage): static
    {
        $this->opportunityStage = $opportunityStage;

        return $this;
    }

    /**
     * @return Collection<int, OpportunityLog>
     */
    public function getOpportunityLogs(): Collection
    {
        return $this->opportunityLogs;
    }

    public function addOpportunityLog(OpportunityLog $opportunityLog): static
    {
        if (!$this->opportunityLogs->contains($opportunityLog)) {
            $this->opportunityLogs->add($opportunityLog);
            $opportunityLog->setOpportunity($this);
        }

        return $this;
    }

    public function removeOpportunityLog(OpportunityLog $opportunityLog): static
    {
        if ($this->opportunityLogs->removeElement($opportunityLog)) {
            // `opportunity` is non-nullable, so the log should be removed with orphanRemoval.
            if ($opportunityLog->getOpportunity() === $this) {
                // $opportunityLog->setOpportunity(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Activity>
     */
    public function getActivities(): Collection
    {
        return $this->activities;
    }

    public function addActivity(Activity $activity): static
    {
        if (!$this->activities->contains($activity)) {
            $this->activities->add($activity);
            $activity->setOpportunity($this);
        }

        return $this;
    }

    public function removeActivity(Activity $activity): static
    {
        if ($this->activities->removeElement($activity)) {
            // set the owning side to null (unless already changed)
            if ($activity->getOpportunity() === $this) {
                $activity->setOpportunity(null);
            }
        }

        return $this;
    }
}
