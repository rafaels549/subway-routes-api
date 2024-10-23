<?php

namespace Rafael\SubwayRoutesApi\Database\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity]
#[ORM\Table(name: 'manufacturer')]
class Manufacturer
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private UuidInterface $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $phone;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $email;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $street;

    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    private ?string $city;

    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    private ?string $state;

    #[ORM\Column(type: 'string', length: 20, nullable: true)]
    private ?string $postal_code;

    #[ORM\Column(type: 'string', length: 50, nullable: true)]
    private ?string $website;

    public function __construct(
        string $name,
        ?string $phone = null,
        ?string $email = null,
        ?string $street = null,
        ?string $city = null,
        ?string $state = null,
        ?string $postal_code = null,
        ?string $website = null
    ) {
        $this->name = $name;
        $this->description = $description;
        $this->phone = $phone;
        $this->email = $email;
        $this->street = $street;
        $this->city = $city;
        $this->state = $state;
        $this->postal_code = $postal_code;
        $this->website = $website;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): void
    {
        $this->phone = $phone;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
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

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(?string $state): void
    {
        $this->state = $state;
    }

    public function getPostalCode(): ?string
    {
        return $this->postal_code;
    }

    public function setPostalCode(?string $postal_code): void
    {
        $this->postal_code = $postal_code;
    }

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(?string $website): void
    {
        $this->website = $website;
    }
}
