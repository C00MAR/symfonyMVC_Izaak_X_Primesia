<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\ProductRepository;
use App\State\ProductProvider;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(
            uriTemplate: '/admin/products',
            provider: ProductProvider::class,
            security: "is_granted('ROLE_ADMIN')"
        ),
        new Get(
            uriTemplate: '/admin/products/{id}',
            provider: ProductProvider::class,
            security: "is_granted('ROLE_ADMIN')"
        )
    ]
)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $price = null;

    #[ORM\Column(length: 255)]
    private ?string $formated_price = null;

    #[ORM\Column(length: 255)]
    private ?string $category = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $createdBy = null;

    #[ORM\Column(type: Types::JSON, nullable: true)]
    private ?array $images = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getFormatedPrice(): ?string
    {
        return $this->formated_price;
    }

    public function setFormatedPrice(string $formated_price): static
    {
        $this->formated_price = $formated_price;

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?User $createdBy): static
    {
        $this->createdBy = $createdBy;
        return $this;
    }

    public function getImages(): ?array
    {
        return $this->images ?? [];
    }

    public function setImages(?array $images): static
    {
        $this->images = $images;
        return $this;
    }

    public function addImage(string $image): static
    {
        if (!in_array($image, $this->getImages())) {
            $this->images[] = $image;
        }
        return $this;
    }

    public function removeImage(string $image): static
    {
        $images = $this->getImages();
        $key = array_search($image, $images);
        if ($key !== false) {
            unset($images[$key]);
            $this->images = array_values($images);
        }
        return $this;
    }

    public function getImagePaths(): array
    {
        $paths = [];
        foreach ($this->getImages() as $image) {
            $paths[] = '/uploads/products/' . $this->getId() . '/' . $image;
        }
        return $paths;
    }

    public function getFirstImage(): ?string
    {
        $images = $this->getImages();
        return !empty($images) ? $images[0] : null;
    }

    public function getFirstImagePath(): ?string
    {
        $firstImage = $this->getFirstImage();
        return $firstImage ? '/uploads/products/' . $this->getId() . '/' . $firstImage : null;
    }

    public function hasImages(): bool
    {
        return !empty($this->getImages());
    }
}
