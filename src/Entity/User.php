<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\GetUserByEmailController;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ApiResource(
    collectionOperations: ['GET', 'POST'],
    itemOperations: [
        'GET' => [
            'method' => 'GET',
            'normalization_context' => [
                'groups' => ['user.read']
            ]
        ],
        'GETByEmail' => [
            'method' => 'GET',
            'path' => '/users/email/{email}',
            'parameters' => [
                [
                    "name" => "email",
                    "in" => "path",
                    "required" => true,
                    "type" => "string",
                    "description" => "The email of the user"
                ]
            ],
            'controller' => GetUserByEmailController::class,
        ],
        'PUT' => [
            'method' => 'PUT',
            'normalization_context' => [
                'groups' => ['user.write']
            ]
        ],
        'delete'
    ],
    denormalizationContext: ['groups' => ['user.write']],
    normalizationContext: ['groups' => ['user.read']]
)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Assert\NotBlank]
    #[Groups(['user.read', 'panier.read', 'panier.write'])]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\NotBlank]
    #[Assert\Email]
    #[Groups(['user.read', 'user.write'])]
    private ?string $email = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Assert\NotBlank]
    #[Groups(['user.write'])]
    private ?string $password = null;

    #[ORM\Column(type: Types::GUID)]
    #[Assert\NotBlank]
    #[Assert\Uuid]
    private ?string $uuid = null;

    #[ORM\OneToMany(mappedBy: 'userOwner', targetEntity: Adress::class)]
    #[Groups(['user.read', 'user.write'])]
    private Collection $adress;

    #[ORM\OneToOne(mappedBy: 'userId', cascade: ['persist', 'remove'])]
    #[Groups(['user.read', 'user.write'])]
    private ?Panier $panier = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $token = null;

    public function __construct()
    {
        $this->adress = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    /**
     * @return Collection<int, Adress>
     */
    public function getAdress(): Collection
    {
        return $this->adress;
    }

    public function addAdress(Adress $adress): self
    {
        if (!$this->adress->contains($adress)) {
            $this->adress->add($adress);
            $adress->setUserOwner($this);
        }

        return $this;
    }

    public function removeAdress(Adress $adress): self
    {
        if ($this->adress->removeElement($adress)) {
            // set the owning side to null (unless already changed)
            if ($adress->getUserOwner() === $this) {
                $adress->setUserOwner(null);
            }
        }

        return $this;
    }

    public function getPanier(): ?Panier
    {
        return $this->panier;
    }

    public function setPanier(Panier $panier): self
    {
        // set the owning side of the relation if necessary
        if ($panier->getUserId() !== $this) {
            $panier->setUserId($this);
        }

        $this->panier = $panier;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): self
    {
        $this->token = $token;

        return $this;
    }
}
