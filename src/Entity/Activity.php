<?php

namespace App\Entity;

use App\Entity\Contracts\SluggableInterface;
use App\Entity\Traits\Sluggable;
use App\Entity\Traits\SoftDeleteable;
use App\Entity\Traits\Timestampable;
use App\Enum\ActivityStatusEnum;
use App\Enum\ActivityTypeEnum;
use App\Enum\Direction;
use App\Enum\Priority;
use App\Repository\ActivityRepository;
use App\Validator\ActivityConstraint;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ActivityRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ActivityConstraint]

class Activity implements SluggableInterface
{

    public function getSlugSource(): ?string
    {
        return trim(($this->subject ?? '') . ' ' . ($this->scheduled_at?->format('Y-m-d H:i:s') ?? ''));
    }

    public function getSlugFields(): array
    {
        return ['subject', 'scheduledAt'];
    }

    use Timestampable;
    use SoftDeleteable;
    use Sluggable;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Assert\Length(
        min: 2,
        max: 100,
    )]
    private ?string $subject;

    #[ORM\Column(enumType: ActivityTypeEnum::class, nullable: false)]
    // private ActivityTypeEnum $activityTypeEnum = ActivityTypeEnum::Activité;
    private ActivityTypeEnum $activityTypeEnum = ActivityTypeEnum::TASK;

    #[ORM\Column(enumType: ActivityStatusEnum::class, nullable: false)]
    private ActivityStatusEnum $activityStatusEnum = ActivityStatusEnum::TODO;

    #[ORM\Column(enumType: Priority::class, nullable: false)]
    private Priority $priority = Priority::MEDIUM;

    #[ORM\Column(enumType: Direction::class, nullable: false)]
    private Direction $direction = Direction::INBOUND;

    #[ORM\Column]
    private ?\DateTimeImmutable $scheduled_at = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $due_date = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $completed_at = null;

    #[ORM\ManyToOne(inversedBy: 'activities')]
    private ?Lead $lead = null;

    #[ORM\ManyToOne(inversedBy: 'activities')]
    private ?Opportunity $opportunity = null;

    #[ORM\ManyToOne(inversedBy: 'activities')]
    private ?Account $account = null;

    #[ORM\ManyToOne(inversedBy: 'activities')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'activities')]
    private ?Contact $contact = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): static
    {
        $this->subject = trim($subject);

        return $this;
    }

    public function getActivityTypeEnum(): ?ActivityTypeEnum
    {
        return $this->activityTypeEnum;
    }

    public function setActivityTypeEnum(?ActivityTypeEnum $activityTypeEnum): static
    {
        $this->activityTypeEnum = $activityTypeEnum;

        return $this;
    }

    public function getActivityStatusEnum(): ?ActivityStatusEnum
    {
        return $this->activityStatusEnum;
    }

    public function setActivityStatusEnum(?ActivityStatusEnum $activityStatusEnum): static
    {
        $this->activityStatusEnum = $activityStatusEnum;

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

    public function getDirection(): ?Direction
    {
        return $this->direction;
    }

    public function setDirection(?Direction $direction): static
    {
        $this->direction = $direction;

        return $this;
    }

    public function getScheduledAt(): ?\DateTimeImmutable
    {
        return $this->scheduled_at;
    }

    public function setScheduledAt(\DateTimeImmutable $scheduled_at): static
    {
        $this->scheduled_at = $scheduled_at;

        return $this;
    }

    public function getDueDate(): ?\DateTimeImmutable
    {
        return $this->due_date;
    }

    public function setDueDate(?\DateTimeImmutable $due_date): static
    {
        $this->due_date = $due_date;

        return $this;
    }

    public function getCompletedAt(): ?\DateTimeImmutable
    {
        return $this->completed_at;
    }

    public function setCompletedAt(?\DateTimeImmutable $completed_at): static
    {
        $this->completed_at = $completed_at;

        return $this;
    }

    public function getLead(): ?Lead
    {
        return $this->lead;
    }

    public function setLead(?Lead $lead): static
    {
        $this->lead = $lead;

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

    public function getAccount(): ?Account
    {
        return $this->account;
    }

    public function setAccount(?Account $account): static
    {
        $this->account = $account;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getContact(): ?Contact
    {
        return $this->contact;
    }

    public function setContact(?Contact $contact): static
    {
        $this->contact = $contact;

        return $this;
    }
    public function markAsDone(): self
    {
        $this->completed_at = new \DateTimeImmutable();
        return $this;
    }
}
