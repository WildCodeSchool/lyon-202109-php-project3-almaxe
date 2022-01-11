<?php

namespace App\Entity;

use App\Repository\PartnerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PartnerRepository::class)
 */
class Partner
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $affiliateKey;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $active;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private string $picture;

    /**
     * @ORM\OneToMany(targetEntity=Product::class, mappedBy="partner")
     */
    private Collection $products;

    public function __construct()
    {
        $this->products = new ArrayCollection();
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

    public function getAffiliateKey(): string
    {
        return $this->affiliateKey;
    }

    public function setAffiliateKey(string $affiliateKey): self
    {
        $this->affiliateKey = $affiliateKey;

        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getPicture(): string
    {
        return $this->picture;
    }

    public function setPicture(string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    /**
     * @return Collection|Product[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->setPartner($this);
        }

        return $this;
    }

    public function removeProduct(Product $product, PartnerRepository $partnerRepository): self
    {
        $partner = $partnerRepository->findOneBy(['name' => 'default']);
        if (!$partner) {
            $partner = new Partner();
        }
        if ($this->products->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getPartner() === $this) {
                $product->setPartner($partner);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }
}
