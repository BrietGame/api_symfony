<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\AddPanierByUserController;
use App\Controller\GetPanierByUserController;
use App\Controller\UpdatePanierByUserController;
use App\Repository\PanierRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PanierRepository::class)]
#[ApiResource(
    collectionOperations: [
        'GET' => [
            'method' => 'GET',
            'path' => '/carts',
        ],
        'POST' => [
            'method' => 'POST',
            'path' => '/cart/add',
            'controller' => AddPanierByUserController::class,
            'denormalization_context' => [
                'groups' => ['panier.write', 'produit.write']
            ],
        ],
        'PUT' => [
            'method' => 'PUT',
            'path' => '/cart/update/{userId}',
            'controller' => UpdatePanierByUserController::class,
            'denormalization_context' => [
                'groups' => ['panier.write', 'produit.write']
            ],
            'parameters' => [
                'userId' => [
                    'type' => 'string',
                    'required' => true,
                    'description' => 'User ID'
                ]
            ]
        ],
    ],
    itemOperations: [
        'GET' => [
            'method' => 'GET',
            'path' => '/cart/{id}',
            'controller' => GetPanierByUserController::class,
            'normalization_context' => [
                'groups' => ['panier.read']
            ]
        ],
        'delete' => [
            'method' => 'DELETE',
            'path' => '/cart/{id}'
        ]
    ],
    attributes: [
        'denormalization_context' => ['groups' => ['panier.write']],
        'normalization_context' => ['groups' => ['panier.read']]
    ],
    denormalizationContext: ['groups' => ['panier.write', 'produit.write']],
    normalizationContext: ['groups' => ['panier.read', 'produit.read']],
)]
class Panier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Assert\NotBlank]
    private ?int $id = null;

    #[ORM\Column(type: Types::GUID)]
    #[Assert\NotBlank]
    #[Assert\Uuid]
    #[Groups(['panier.read', 'panier.write'])]
    private ?string $uuid = null;

    #[ORM\OneToOne(inversedBy: 'panier', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank]
    #[Groups(['panier.read', 'panier.write'])]
    private ?User $userId = null;

    #[ORM\ManyToMany(targetEntity: Produit::class, inversedBy: 'paniers', cascade: ['persist', 'remove'])]
    #[Assert\NotBlank]
    #[Groups(['panier.read', 'panier.write'])]
    private Collection $produits;

    public function __construct()
    {
        $this->produits = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function setUuid(string $uuid): self
    {
        $this->uuid = $uuid;

        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->userId;
    }

    public function setUserId(User $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * @return Collection<int, Produit>
     */
    public function getProduits(): Collection
    {
        return $this->produits;
    }

    public function addProduit(Produit $produit): self
    {
        if (!$this->produits->contains($produit)) {
            $this->produits->add($produit);
        }

        return $this;
    }

    public function removeProduit(Produit $produit): self
    {
        $this->produits->removeElement($produit);

        return $this;
    }
}
