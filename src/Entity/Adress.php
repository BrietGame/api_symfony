<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Repository\AdressRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AdressRepository::class)]
#[ApiResource(
    collectionOperations: ['GET', 'POST'],
    itemOperations: [
        'GET' => [
            'method' => 'GET',
            'normalization_context' => [
                'groups' => ['adress.read']
            ]
        ],
        'PUT' => [
            'method' => 'PUT',
            'normalization_context' => [
                'groups' => ['adress.write']
            ]
        ],
        'delete'
    ],
    denormalizationContext: ['groups' => ['adress.write']],
    normalizationContext: ['groups' => ['adress.read']]
)]
class Adress
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Groups(['adress.read', 'adress.write'])]
    private ?int $nbVoie = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Groups(['adress.read', 'adress.write'])]
    private ?string $street = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Groups(['adress.read', 'adress.write'])]
    private ?int $zip = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Groups(['adress.read', 'adress.write'])]
    private ?string $city = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['adress.read', 'adress.write'])]
    private ?string $state = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Groups(['adress.read', 'adress.write'])]
    private ?string $country = null;

    #[ORM\ManyToOne(inversedBy: 'adress')]
    #[Groups(['adress.read'])]
    private ?User $userOwner = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNbVoie(): ?int
    {
        return $this->nbVoie;
    }

    public function setNbVoie(int $nbVoie): self
    {
        $this->nbVoie = $nbVoie;

        return $this;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(string $street): self
    {
        $this->street = $street;

        return $this;
    }

    public function getZip(): ?int
    {
        return $this->zip;
    }

    public function setZip(int $zip): self
    {
        $this->zip = $zip;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(?string $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getUserOwner(): ?User
    {
        return $this->userOwner;
    }

    public function setUserOwner(?User $userOwner): self
    {
        $this->userOwner = $userOwner;

        return $this;
    }
}
