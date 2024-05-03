<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PostRepository::class)]
class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

   

    #[ORM\Column(length: 255)]
    private ?string $postTitle = null;

    #[ORM\Column(length: 255)]
    private ?string $postEdition = null;

    #[ORM\Column(length: 255)]
    private ?string $postCondition = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?int $postPrice = null;

    #[ORM\ManyToOne(inversedBy: 'posts')]
    private ?Category $postCategory = null;

    #[ORM\Column]
    private ?bool $isValid = null;

    #[ORM\ManyToOne(inversedBy: 'posts')]
    private ?User $user = null;

    /**
     * @var Collection<int, Files>
     */
    #[ORM\OneToMany(targetEntity: Files::class, mappedBy: 'post')]
    private Collection $postImages;

    public function __construct()
    {
        $this->postImages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

  

    public function getPostTitle(): ?string
    {
        return $this->postTitle;
    }

    public function setPostTitle(string $postTitle): static
    {
        $this->postTitle = $postTitle;

        return $this;
    }

    public function getPostEdition(): ?string
    {
        return $this->postEdition;
    }

    public function setPostEdition(string $postEdition): static
    {
        $this->postEdition = $postEdition;

        return $this;
    }

    public function getPostCondition(): ?string
    {
        return $this->postCondition;
    }

    public function setPostCondition(string $postCondition): static
    {
        $this->postCondition = $postCondition;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getPostPrice(): ?int
    {
        return $this->postPrice;
    }

    public function setPostPrice(int $postPrice): static
    {
        $this->postPrice = $postPrice;

        return $this;
    }

    public function getPostCategory(): ?Category
    {
        return $this->postCategory;
    }

    public function setPostCategory(?Category $postCategory): static
    {
        $this->postCategory = $postCategory;

        return $this;
    }

    public function isValid(): ?bool
    {
        return $this->isValid;
    }

    public function setValid(bool $isValid): static
    {
        $this->isValid = $isValid;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Files>
     */
    public function getPostImages(): Collection
    {
        return $this->postImages;
    }

    public function addPostImage(Files $postImage): static
    {
        if (!$this->postImages->contains($postImage)) {
            $this->postImages->add($postImage);
            $postImage->setPost($this);
        }

        return $this;
    }

    public function removePostImage(Files $postImage): static
    {
        if ($this->postImages->removeElement($postImage)) {
            // set the owning side to null (unless already changed)
            if ($postImage->getPost() === $this) {
                $postImage->setPost(null);
            }
        }

        return $this;
    }
}
