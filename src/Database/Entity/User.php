<?php

namespace Rafael\SubwayRoutesApi\Database\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity]
#[ORM\Table(name: 'users')]
class User
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private UuidInterface $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $username;

    #[ORM\Column(type: 'string')]
    private string $password;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    private string $email;

    #[ORM\Column(type: 'uuid')]
    private string $role_id;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $phone;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $street;

    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    private ?string $city;

    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    private ?string $country;

    #[ORM\Column(type: 'string', length: 20, nullable: true)]
    private ?string $postal_code;

    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    private ?string $state;

    #[ORM\Column(type: 'date', nullable: true)]
    private ?\DateTime $date_of_birth;

    #[ORM\Column(type: 'string', length: 10, nullable: true)]
    private ?string $gender;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $nationality;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $languages;

    public function __construct(
        string $username,
        string $password,
        string $email,
        UuidInterface $role_id,
        ?string $phone = null,
        ?string $street = null,
        ?string $city = null,
        ?string $country = null,
        ?string $postal_code = null,
        ?string $state = null,
        ?\DateTime $date_of_birth = null,
        ?string $gender = null,
        ?string $nationality = null,
        ?string $languages = null
    ) {
        $this->username = $username;
        $this->password = password_hash($password, PASSWORD_DEFAULT);
        $this->email = $email;
        $this->role_id = $role_id;
        $this->phone = $phone;
        $this->street = $street;
        $this->city = $city;
        $this->country = $country;
        $this->postal_code = $postal_code;
        $this->state = $state;
        $this->date_of_birth = $date_of_birth;
        $this->gender = $gender;
        $this->nationality = $nationality;
        $this->languages = $languages;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getRoleId(): string
    {
        return $this->role_id;
    }

    public function setRoleId(string $role_id): void
    {
        $this->role_id = $role_id;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): void
    {
        $this->phone = $phone;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(?string $street): void
    {
        $this->street = $street;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): void
    {
        $this->city = $city;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): void
    {
        $this->country = $country;
    }

    public function getPostalCode(): ?string
    {
        return $this->postal_code;
    }

    public function setPostalCode(?string $postal_code): void
    {
        $this->postal_code = $postal_code;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(?string $state): void
    {
        $this->state = $state;
    }

    public function getDateOfBirth(): ?\DateTime
    {
        return $this->date_of_birth;
    }

    public function setDateOfBirth(?\DateTime $date_of_birth): void
    {
        $this->date_of_birth = $date_of_birth;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(?string $gender): void
    {
        $this->gender = $gender;
    }

    public function getNationality(): ?string
    {
        return $this->nationality;
    }

    public function setNationality(?string $nationality): void
    {
        $this->nationality = $nationality;
    }

    public function getLanguages(): ?string
    {
        return $this->languages;
    }

    public function setLanguages(?string $languages): void
    {
        $this->languages = $languages;
    }
}
