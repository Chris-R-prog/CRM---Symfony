<?php

namespace App\Entity;

use App\Entity\Industry;
use App\Entity\Traits\SoftDeleteable;
use App\Entity\Traits\Timestampable;
use App\Repository\AccountRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use App\Entity\Traits\Sluggable;

#[ORM\Entity(repositoryClass: AccountRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[UniqueEntity(fields: ['siret'])]
#[ORM\Table(indexes: [
    new ORM\Index(name: "account_name_idx", columns: ["account_name"]),
    new ORM\Index(name: "address_city_idx", columns: ["city"]),
    new ORM\Index(name: "address_postal_idx", columns: ["postalcode"]),
])]

class Account
{

    use Timestampable;
    use SoftDeleteable;
    use Sluggable;

    public function getSlugSource(): string
    {
        return $this->accountName;
    }

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $ownerUserId = null;

    #[ORM\Column(length: 14, nullable: true, unique: true)]
    #[Assert\Regex('/^[0-9]{14}$/')]
    private ?string $siret = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 2,
        max: 255
    )]
    private ?string $accountName = null;

    #[ORM\ManyToOne(targetEntity: Account::class)]
    #[ORM\JoinColumn(name: "parent_account_id", referencedColumnName: "id", nullable: true)]
    private ?Account $parentAccount = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(max: 255)]
    private ?string $addressline1 = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(max: 255)]
    private ?string $addressline2 = null;

    #[ORM\Column(length: 100, nullable: true)]
    #[Assert\Length(max: 100)]
    private ?string $city = null;

    #[ORM\Column(length: 20, nullable: true)]
    #[Assert\Length(max: 20)]
    #[Assert\Regex('/^[A-Za-z0-9\- ]+$/')]
    private ?string $postalcode = null;

    #[ORM\Column(length: 100, nullable: true)]
    #[Assert\Country]
    private ?string $country = 'FR';

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Url]
    private ?string $website = null;

    #[ORM\Column(length: 20, nullable: true)]
    #[Assert\Length(max: 20)]
    #[Assert\Regex('/^[0-9+\s().-]+$/')]
    private ?string $phoneNumber = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $comment = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: true)]
    private ?Industry $industry = null;

    #[ORM\Column(nullable: true)]
    #[Assert\Range(min: 0, max: 1)]
    private ?float $riskScoring = null;

    #[ORM\Column(nullable: true)]
    #[Assert\Range(min: 0, max: 1)]
    private ?float $priorityScoring = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    #[Assert\PositiveOrZero]
    private ?string $turnover = null;

    #[ORM\Column(nullable: true)]
    #[Assert\PositiveOrZero]
    private ?int $numberOfEmployees = null;

    /**
     * @var Collection<int, Contact>
     */
    #[ORM\OneToMany(mappedBy: 'account', targetEntity: Contact::class)]
    private Collection $contacts;

    /**
     * @var Collection<int, Opportunity>
     */
    #[ORM\OneToMany(targetEntity: Opportunity::class, mappedBy: 'account', orphanRemoval: true)]
    private Collection $opportunities;

    public function __construct()
    {
        $this->contacts = new ArrayCollection();
        $this->opportunities = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOwnerUserId(): ?int
    {
        return $this->ownerUserId;
    }

    public function setOwnerUserId(int $ownerUserId): static
    {
        $this->ownerUserId = $ownerUserId;
        return $this;
    }

    public function getSiret(): ?string
    {
        return $this->siret;
    }

    public function setSiret(?string $siret): static
    {
        $this->siret = $siret ? trim($siret) : null;
        return $this;
    }

    public function getAccountName(): ?string
    {
        return $this->accountName;
    }

    public function setAccountName(string $accountName): static
    {
        $this->accountName = mb_strtoupper(trim($accountName));


        return $this;
    }

    public function getParentAccount(): ?Account
    {
        return $this->parentAccount;
    }

    public function setParentAccount(?Account $parentAccount): static
    {
        $this->parentAccount = $parentAccount;
        return $this;
    }

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(?string $website): static
    {
        $website = $website ? trim($website) : null;

        if ($website && !str_starts_with($website, 'http')) {
            $website = 'https://' . $website;
        }

        $this->website = $website;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?string $phoneNumber): static
    {
        $this->phoneNumber = $phoneNumber ? trim($phoneNumber) : null;
        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): static
    {
        $this->comment = $comment ? trim($comment) : null;
        return $this;
    }

    public function getRiskScoring(): ?float
    {
        return $this->riskScoring;
    }

    public function setRiskScoring(?float $riskScoring): static
    {
        $this->riskScoring = $riskScoring;
        return $this;
    }

    public function getPriorityScoring(): ?float
    {
        return $this->priorityScoring;
    }

    public function setPriorityScoring(?float $priorityScoring): static
    {
        $this->priorityScoring = $priorityScoring;
        return $this;
    }

    public function getTurnover(): ?string
    {
        return $this->turnover;
    }

    public function setTurnover(?string $turnover): static
    {
        $turnover = trim($turnover);
        $this->turnover = $turnover === '' ? null : $turnover;
        return $this;
    }

    public function getNumberOfEmployees(): ?int
    {
        return $this->numberOfEmployees;
    }

    public function setNumberOfEmployees(?int $numberOfEmployees): static
    {
        $this->numberOfEmployees = $numberOfEmployees;
        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;
        return $this;
    }

    public function getIndustry(): ?Industry
    {
        return $this->industry;
    }

    public function setIndustry(?Industry $industry): static
    {
        $this->industry = $industry;
        return $this;
    }

    public function getAddressline1(): ?string
    {
        return $this->addressline1;
    }

    public function setAddressline1(?string $addressline1): static
    {
        $this->addressline1 = $addressline1 ? trim($addressline1) : null;

        return $this;
    }

    public function getAddressline2(): ?string
    {
        return $this->addressline2;
    }

    public function setAddressline2(?string $addressline2): static
    {
        $this->addressline2 = $addressline2 ? trim($addressline2) : null;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): static
    {
        $this->city = $city ? ucwords(strtolower(trim($city))) : null;

        return $this;
    }

    public function getPostalcode(): ?string
    {
        return $this->postalcode;
    }

    public function setPostalcode(?string $postalcode): static
    {
        $this->postalcode = $postalcode ? strtoupper(trim($postalcode)) : null;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): static
    {
        $this->country = $country ? strtoupper(trim($country)) : null;

        return $this;
    }

    public function __toString(): string
    {
        return $this->accountName ?? '';
    }

    /**
     * @return Collection<int, Contact>
     */
    public function getContacts(): Collection
    {
        return $this->contacts;
    }

    public function addContact(Contact $contact): static
    {
        if (!$this->contacts->contains($contact)) {
            $this->contacts->add($contact);
            $contact->setAccount($this);
        }

        return $this;
    }

    public function removeContact(Contact $contact): static
    {
        if ($this->contacts->removeElement($contact)) {
            // set the owning side to null (unless already changed)
            if ($contact->getAccount() === $this) {
                $contact->setAccount(null);
            }
        }

        return $this;
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
            $opportunity->setAccount($this);
        }

        return $this;
    }

    public function removeOpportunity(Opportunity $opportunity): static
    {
        if ($this->opportunities->removeElement($opportunity)) {
            // set the owning side to null (unless already changed)
            if ($opportunity->getAccount() === $this) {
                $opportunity->setAccount(null);
            }
        }

        return $this;
    }
}
