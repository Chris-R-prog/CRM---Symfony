<?php

namespace App\Entity;

use App\Repository\OpportunityContactRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OpportunityContactRepository::class)]
class OpportunityContact
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Opportunity::class, inversedBy: 'opportunityContacts')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Opportunity $opportunity = null;

    #[ORM\ManyToOne(targetEntity: Contact::class, inversedBy: 'opportunityContacts')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Contact $contact = null;

    #[ORM\ManyToOne(targetEntity: ContactRole::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?ContactRole $contact_role = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getContact(): ?Contact
    {
        return $this->contact;
    }

    public function setContact(?Contact $contact): static
    {
        $this->contact = $contact;

        return $this;
    }

    public function getContactRole(): ?ContactRole
    {
        return $this->contact_role;
    }

    public function setContactRole(?ContactRole $contact_role): static
    {
        $this->contact_role = $contact_role;

        return $this;
    }
}
