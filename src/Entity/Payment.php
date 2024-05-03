<?php

namespace App\Entity;

use App\Repository\PaymentRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PaymentRepository::class)]
class Payment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Post $post = null;

    #[ORM\Column]
    private ?int $price = null;

    #[ORM\Column]
    private ?int $deliveryPrice = null;

    #[ORM\Column]
    private ?int $ttcAmount = null;



    #[ORM\Column]
    private ?\DateTimeImmutable $creatdeAt = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getDeliveryPrice(): ?int
    {
        return $this->deliveryPrice;
    }

    public function setDeliveryPrice(int $deliveryPrice): static
    {
        $this->deliveryPrice = $deliveryPrice;

        return $this;
    }

    public function getTtcAmount(): ?int
    {
        return $this->ttcAmount;
    }

    public function setTtcAmount(int $ttcAmount): static
    {
        $this->ttcAmount = $ttcAmount;

        return $this;
    }

    public function getCreatdeAt(): ?\DateTimeImmutable
    {
        return $this->creatdeAt;
    }

    public function setCreatdeAt(\DateTimeImmutable $creatdeAt): static
    {
        $this->creatdeAt = $creatdeAt;

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
}
