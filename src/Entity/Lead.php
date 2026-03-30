<?php

namespace App\Entity;

use App\Entity\Contracts\SluggableInterface;
use App\Entity\Traits\Sluggable;
use App\Entity\Traits\SoftDeleteable;
use App\Entity\Traits\Timestampable;
use App\Enum\Title;
use App\Repository\LeadRepository;
use Doctrine\DBAL\Types\Types;
use App\Enum\Status;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: LeadRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ORM\Table(indexes: [
    new ORM\Index(name: "company_name_idx", columns: ["company_name"]),
])]

class Lead implements SluggableInterface
{

    use Timestampable;
    use SoftDeleteable;
    use Sluggable;

    public function getSlugSource(): ?string
    {
        return $this->companyName . ' ' . $this->subject;
    }

    public function getSlugFields(): array
    {
        return ['companyName', 'subject'];
    }

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
        message: "Le nom contient des caractères invalides."
    )]
    private ?string $firstName = null;

    #[ORM\Column(length: 100)]
    #[Assert\Length(
        min: 2,
        max: 100
    )]
    #[Assert\Regex(
        pattern: "/^[\p{L} '-]+$/u",
        message: "Le nom contient des caractères invalides."
    )]
    private ?string $lastName = null;

    #[ORM\Column(length: 180)]
    #[Assert\NotBlank]
    #[Assert\Email]
    #[Assert\Length(max: 180)]
    private ?string $email = null;

    #[ORM\Column(length: 20, nullable: true)]
    #[Assert\Length(max: 20)]
    #[Assert\Regex('/^[0-9+\s().-]+$/')]
    private ?string $phoneNumber = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 2,
        max: 255
    )]
    private ?string $companyName;

    #[ORM\Column(length: 150, nullable: true)]
    private ?string $jobtitle = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $source = null;

    #[ORM\Column(length: 100)]
    #[Assert\Length(
        min: 2,
        max: 100,
    )]
    private ?string $subject;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $comment = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $converted_at = null;

    #[ORM\Column(enumType: Status::class)]
    private Status $status = Status::Nouveau;

    #[ORM\Column(length: 100, nullable: true)]
    #[Assert\Country]
    private ?string $country = 'FR';

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'leads')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): static
    {
        if ($firstName === null) {
            $this->firstName = null;
            return $this;
        }

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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email ? trim($email) : null;

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

    public function getCompanyName(): ?string
    {
        return $this->companyName;
    }

    public function setCompanyName(string $companyName): static
    {
        $this->companyName = mb_strtoupper(trim($companyName));

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

    public function getSource(): ?string
    {
        return $this->source;
    }

    public function setSource(?string $source): static
    {
        $this->source = $source ? strtolower(trim($source)) : null;

        return $this;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): static
    {
        $this->subject = $subject;

        return $this;
    }

    public function getConvertedAt(): ?\DateTimeImmutable
    {
        return $this->converted_at;
    }

    public function setConvertedAt(?\DateTimeImmutable $converted_at): static
    {
        $this->converted_at = $converted_at;

        return $this;
    }

    public function __toString(): string
    {
        return trim(($this->firstName ?? '') . ' ' . ($this->lastName ?? '')) ?: $this->email ?? '';
    }

    public function getStatus(): Status
    {
        return $this->status;
    }

    public function setStatus(Status $status): static
    {
        $this->status = $status;

        return $this;
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

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): static
    {
        $this->comment = $comment ? trim($comment) : null;
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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
