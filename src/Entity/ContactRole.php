<?php

namespace App\Entity;

use App\Repository\ContactRoleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ContactRoleRepository::class)]
class ContactRole
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 75)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    /**
     * @var Collection<int, OpportunityContact>
     */
    #[ORM\OneToMany(targetEntity: OpportunityContact::class, mappedBy: 'contact_role', orphanRemoval: true)]
    private Collection $opportunityContacts;

    public function __construct()
    {
        $this->opportunityContacts = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function __toString(): string
    {
        return $this->name ?? 'Role du contact';
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
            $opportunityContact->setContactRole($this);
        }

        return $this;
    }

    public function removeOpportunityContact(OpportunityContact $opportunityContact): static
    {
        if ($this->opportunityContacts->removeElement($opportunityContact)) {
            // `contact_role` is non-nullable, so the link must be removed rather than nulled.
            if ($opportunityContact->getContactRole() === $this) {
                // $opportunityContact->setContactRole(null);
            }
        }

        return $this;
    }
}
