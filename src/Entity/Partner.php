<?php

namespace App\Entity;

use App\Repository\PartnerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Blacklist;
use App\Entity\Product;

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
     * @ORM\Column(type="string", length=255)
     */
    private string $picture;

    /**
     * @ORM\OneToMany(targetEntity=Product::class, mappedBy="partner")
     */
    private Collection $products;

    /**
     * @ORM\OneToMany(targetEntity=Blacklist::class, mappedBy="partner")
     */
    private Collection $blacklists;

    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->blacklists = new ArrayCollection();
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

    public function getAffiliateKey(): ?string
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

    public function getPicture(): ?string
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

    public function removeProduct(Product $product): self
    {
        if ($this->products->removeElement($product)) {
            // set the owning side to null (unless  already changed)
            if ($product->getPartner() === $this) {
            }
        }

        return $this;
    }

    /**
     * @return Collection|Blacklist[]
     */
    public function getBlacklists(): Collection
    {
        return $this->blacklists;
    }

    public function addBlacklist(Blacklist $blacklist): self
    {
        if (!$this->blacklists->contains($blacklist)) {
            $this->blacklists[] = $blacklist;
            $blacklist->setPartner($this);
        }

        return $this;
    }

    public function removeBlacklist(Blacklist $blacklist): self
    {
        if ($this->blacklists->removeElement($blacklist)) {
            // set the owning side to null (unless already changed)
            if ($blacklist->getPartner() === $this) {
            }
        }

        return $this;
    }
}
