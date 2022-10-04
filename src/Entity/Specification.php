<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\SpecificationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SpecificationRepository::class)]
#[ApiResource(
    collectionOperations: ['GET', 'POST'],
    itemOperations: [
        'GET' => [
            'method' => 'GET',
            'normalization_context' => [
                'groups' => ['specification.read']
            ]
        ],
        'PUT' => [
            'method' => 'PUT',
            'normalization_context' => [
                'groups' => ['specification.write']
            ]
        ],
        'delete'
    ],
    denormalizationContext: ['groups' => ['specification.write']],
    normalizationContext: ['groups' => ['specification.read']]
)]
class Specification
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Assert\NotBlank]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Groups(['specification.read', 'specification.write'])]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank]
    #[Groups(['specification.read', 'specification.write'])]
    private ?string $content = null;

    #[ORM\ManyToMany(targetEntity: CatSpecification::class, inversedBy: 'specifications')]
    #[Groups(['specification.read'])]
    private Collection $catSpe;

    #[ORM\ManyToMany(targetEntity: Produit::class, mappedBy: 'specifications')]
    private Collection $produits;

    public function __construct()
    {
        $this->catSpe = new ArrayCollection();
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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return Collection<int, CatSpecification>
     */
    public function getCatSpe(): Collection
    {
        return $this->catSpe;
    }

    public function addCatSpe(CatSpecification $catSpe): self
    {
        if (!$this->catSpe->contains($catSpe)) {
            $this->catSpe->add($catSpe);
        }

        return $this;
    }

    public function removeCatSpe(CatSpecification $catSpe): self
    {
        $this->catSpe->removeElement($catSpe);

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
            $produit->addSpecification($this);
        }

        return $this;
    }

    public function removeProduit(Produit $produit): self
    {
        if ($this->produits->removeElement($produit)) {
            $produit->removeSpecification($this);
        }

        return $this;
    }
}
