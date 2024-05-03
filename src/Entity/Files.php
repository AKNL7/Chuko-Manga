<?php

namespace App\Entity;

use App\Repository\FilesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FilesRepository::class)]
class Files
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $post_image = null;

    #[ORM\ManyToOne(inversedBy: 'postImages')]
    private ?Post $post = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPostImage(): ?string
    {
        return $this->post_image;
    }

    public function setPostImage(string $post_image): static
    {
        $this->post_image = $post_image;

        return $this;
    }

    public function getPost(): ?Post
    {
        return $this->post;
    }

    public function setPost(?Post $post): static
    {
        $this->post = $post;

        return $this;
    }
}
