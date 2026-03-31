<?php

namespace App\Entity;

use App\Entity\Traits\SoftDeleteable;
use App\Entity\Traits\Timestampable;
use App\Repository\ContactRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use App\Enum\Title;
use App\Entity\Account;
use App\Entity\Contracts\SluggableInterface;
use App\Entity\Traits\Sluggable;

#[ORM\Entity(repositoryClass: ContactRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[UniqueEntity(fields: ['email'])]
#[ORM\Table(indexes: [
    new ORM\Index(name: "last_name_idx", columns: ["last_name"]),
    new ORM\Index(name: "address_city_idx", columns: ["city"]),
    new ORM\Index(name: "address_postal_idx", columns: ["postalcode"]),
])]

class Contact implements SluggableInterface
{

    public function getSlugSource(): ?string
    {
        return $this->firstName . ' ' . $this->lastName;
    }

    public function getSlugFields(): array
    {
        return ['firstName', 'lastName'];
    }

    use Timestampable;
    use SoftDeleteable;
    use Sluggable;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(enumType: Title::class, nullable: true)]
    private ?Title $title = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 2,
        max: 100
    )]
    #[Assert\Regex(
        pattern: "/^[\p{L} '-]+$/u",
        message: "Le nom contient des caracteres invalides."
    )]
    private ?string $firstName = null;

    #[ORM\Column(length: 100)]
    #[Assert\Length(
        min: 2,
        max: 100
    )]
    #[Assert\Regex(
        pattern: "/^[\p{L} '-]+$/u",
        message: "Le nom contient des caracteres invalides."
    )]
    private ?string $lastName = null;

    #[ORM\Column(length: 150, nullable: true)]
    private ?string $jobtitle = null;

    #[ORM\Column(length: 20, nullable: true)]
    #[Assert\Length(max: 20)]
    #[Assert\Regex('/^[0-9+\s().-]+$/')]
    private ?string $phoneNumber = null;

    #[ORM\Column(length: 20, nullable: true)]
    #[Assert\Length(max: 20)]
    #[Assert\Regex('/^[0-9+\s().-]+$/')]
    private ?string $mobile = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\NotBlank]
    #[Assert\Email]
    #[Assert\Length(max: 180)]
    private ?string $email = null;

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

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $comment = null;

    #[ORM\ManyToOne(inversedBy: 'contacts')]
    private ?Account $account = null;

    /**
     * @var Collection<int, OpportunityContact>
     */
    #[ORM\OneToMany(targetEntity: OpportunityContact::class, mappedBy: 'contact', orphanRemoval: true)]
    private Collection $opportunityContacts;

    /**
     * @var Collection<int, Activity>
     */
    #[ORM\OneToMany(targetEntity: Activity::class, mappedBy: 'contact')]
    private Collection $activities;

    public function __construct()
    {
        $this->opportunityContacts = new ArrayCollection();
        $this->activities = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?Title
    {
        return $this->title;
    }

    public function setTitle(?Title $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $firstname = trim($firstName);
        $this->firstName = mb_convert_case($firstname, MB_CASE_TITLE, "UTF-8");

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $lastName = trim($lastName);
        $this->lastName = mb_strtoupper($lastName, "UTF-8");

        return $this;
    }

    public function getJobtitle(): ?string
    {
        return $this->jobtitle;
    }

    public function setJobtitle(?string $jobtitle): static
    {
        $this->jobtitle = $jobtitle ? trim($jobtitle) : null;

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

    public function getMobile(): ?string
    {
        return $this->mobile;
    }

    public function setMobile(?string $mobile): static
    {
        $this->mobile = $mobile ? trim($mobile) : null;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email ? trim($email) : null;

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

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): static
    {
        $this->comment = $comment ? trim($comment) : null;
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

    public function getAccount(): ?Account
    {
        return $this->account;
    }

    public function setAccount(?Account $account): self
    {
        $this->account = $account;
        return $this;
    }

    public function getDisplayName(): string
    {
        return sprintf(
            '%s %s - %s',
            $this->firstName,
            $this->lastName,
            $this->jobtitle
        );
    }

    public function __toString(): string
    {
        return $this->lastName ?? '';
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
            $opportunityContact->setContact($this);
        }

        return $this;
    }

    public function removeOpportunityContact(OpportunityContact $opportunityContact): static
    {
        if ($this->opportunityContacts->removeElement($opportunityContact)) {
            if ($opportunityContact->getContact() === $this) {
                // `contact` is non-nullable, so the link must be removed rather than nulled.
                // $opportunityContact->setContact(null);
            }
        }

        return $this;
    }

    public function removeContact(OpportunityContact $opportunityContact): static
    {
        return $this->removeOpportunityContact($opportunityContact);
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
            $activity->setContact($this);
        }

        return $this;
    }

    public function removeActivity(Activity $activity): static
    {
        if ($this->activities->removeElement($activity)) {
            // set the owning side to null (unless already changed)
            if ($activity->getContact() === $this) {
                $activity->setContact(null);
            }
        }

        return $this;
    }
}
