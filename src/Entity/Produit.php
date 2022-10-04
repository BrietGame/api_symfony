<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\ProduitController;
use App\Repository\ProduitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProduitRepository::class)]
#[ApiResource(
    collectionOperations: [
        'GET',
        'POST' => [
            'method' => 'POST',
            'path' => '/produits/add',
            'controller' => ProduitController::class,
            'read' => false
        ]
    ],
    itemOperations: [
        'GET' => [
            'method' => 'GET',
            'normalization_context' => [
                'groups' => ['produit.read']
            ]
        ],
        'PUT' => [
            'method' => 'PUT',
            'normalization_context' => [
                'groups' => ['produit.write']
            ]
        ],
        'delete',

    ],
    denormalizationContext: ['groups' => ['produit.write']],
    normalizationContext: ['groups' => ['produit.read']],
)]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['produit.read'])]
    private ?int $id = null;

    #[ORM\Column(type: Types::GUID)]
    #[Assert\Uuid]
    private ?string $uuid = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Groups(['produit.read', 'produit.write'])]
    private ?string $name = null;

    #[ORM\ManyToMany(targetEntity: Specification::class, inversedBy: 'produits')]
//    #[Groups(['produit.read', 'produit.write'])]
    private Collection $specifications;

    #[ORM\ManyToMany(targetEntity: CatProduit::class, inversedBy: 'produits')]
//    #[Groups(['produit.read', 'produit.write'])]
    private Collection $category;

    #[ORM\ManyToMany(targetEntity: Panier::class, mappedBy: 'produits')]
    private Collection $paniers;

    #[ORM\Column(length: 255)]
    #[Groups(['produit.read', 'produit.write'])]
    private ?string $price = null;

    public function __construct()
    {
        $this->specifications = new ArrayCollection();
        $this->category = new ArrayCollection();
        $this->paniers = new ArrayCollection();
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Specification>
     */
    public function getSpecifications(): Collection
    {
        return $this->specifications;
    }

    public function addSpecification(Specification $specification): self
    {
        if (!$this->specifications->contains($specification)) {
            $this->specifications->add($specification);
        }

        return $this;
    }

    public function removeSpecification(Specification $specification): self
    {
        $this->specifications->removeElement($specification);

        return $this;
    }

    /**
     * @return Collection<int, CatProduit>
     */
    public function getCategory(): Collection
    {
        return $this->category;
    }

    public function addCategory(CatProduit $category): self
    {
        if (!$this->category->contains($category)) {
            $this->category->add($category);
        }

        return $this;
    }

    public function removeCategory(CatProduit $category): self
    {
        $this->category->removeElement($category);

        return $this;
    }

    /**
     * @return Collection<int, Panier>
     */
    public function getPaniers(): Collection
    {
        return $this->paniers;
    }

    public function addPanier(Panier $panier): self
    {
        if (!$this->paniers->contains($panier)) {
            $this->paniers->add($panier);
            $panier->addProduit($this);
        }

        return $this;
    }

    public function removePanier(Panier $panier): self
    {
        if ($this->paniers->removeElement($panier)) {
            $panier->removeProduit($this);
        }

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }
}
