<?php

namespace App\Entity;

use App\Repository\ReviewRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReviewRepository::class)
 */
class Review
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="integer")
     */
    private int $rating;

    /**
     * @ORM\Column(type="text", length=65535, nullable=true)
     */
    private ?string $comment;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $reviewerForname;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $reviewerLastname;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="reviews")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Product $product;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(int $rating): self
    {
        $this->rating = $rating;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getReviewerForname(): ?string
    {
        return $this->reviewerForname;
    }

    public function setReviewerForname(?string $reviewerForname): self
    {
        $this->reviewerForname = $reviewerForname;

        return $this;
    }

    public function getReviewerLastname(): ?string
    {
        return $this->reviewerLastname;
    }

    public function setReviewerLastname(?string $reviewerLastname): self
    {
        $this->reviewerLastname = $reviewerLastname;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }
}
