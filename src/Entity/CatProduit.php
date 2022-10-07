<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CatProduitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CatProduitRepository::class)]
#[ApiResource(
    collectionOperations: ['GET', 'POST'],
    itemOperations: [
        'GET' => [
            'method' => 'GET',
            'normalization_context' => [
                'groups' => ['catproduit.read']
            ]
        ],
        'PUT' => [
            'method' => 'PUT',
            'normalization_context' => [
                'groups' => ['catproduit.write']
            ]
        ],
        'delete'
    ],
    denormalizationContext: ['groups' => ['catproduit.write']],
    normalizationContext: ['groups' => ['catproduit.read']]
)]
class CatProduit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Assert\NotBlank]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Groups(['catproduit.read', 'catproduit.write'])]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['catproduit.read', 'catproduit.write'])]
    private ?string $description = null;

    #[ORM\ManyToMany(targetEntity: Produit::class, mappedBy: 'category')]
    #[Groups(['catproduit.read'])]
    private Collection $produits;

    public function __construct()
    {
        $this->produits = new ArrayCollection();
    }

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

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
            $produit->addCategory($this);
        }

        return $this;
    }

    public function removeProduit(Produit $produit): self
    {
        if ($this->produits->removeElement($produit)) {
            $produit->removeCategory($this);
        }

        return $this;
    }
}
