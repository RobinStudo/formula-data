<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Repository\DriverRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ReadableCollection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: DriverRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(),
        new Post(),
        new Get(
            normalizationContext: [
                'groups' => ['driver:read', 'driver:extra'],
            ]
        ),
        new Delete(),
    ],
    normalizationContext: [
        'groups' => ['driver:read']
    ]
)]
class Driver
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 120)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 120)]
    #[Groups(['driver:read'])]
    private ?string $name = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Groups(['driver:read'])]
    private ?int $number = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    #[Assert\NotBlank]
    private ?DateTimeInterface $birthdate = null;

    #[ORM\ManyToOne(inversedBy: 'drivers')]
    #[Groups(['driver:read'])]
    private ?Team $team = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getBirthdate(): ?DateTimeInterface
    {
        return $this->birthdate;
    }

    public function setBirthdate(DateTimeInterface $birthdate): self
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    public function getTeam(): ?Team
    {
        return $this->team;
    }

    public function setTeam(?Team $team): self
    {
        $this->team = $team;

        return $this;
    }

    #[Groups(['driver:read'])]
    public function getIsActive(): bool
    {
        return $this->team !== null;
    }

    #[Groups(['driver:read'])]
    public function getAge(): int
    {
        $today = new DateTime();
        return $this->birthdate->diff($today)->y;
    }

    #[Groups(['driver:extra'])]
    public function getTeammates(): ReadableCollection
    {
        return $this->team->getDrivers()->filter(function($driver){
            return $driver !== $this;
        });
    }
}
