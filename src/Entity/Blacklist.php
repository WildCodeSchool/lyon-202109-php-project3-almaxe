<?php

namespace App\Entity;

use App\Repository\BlacklistRepository;
use App\Entity\Partner;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BlacklistRepository::class)
 */
class Blacklist
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
     * @ORM\Column(type="date")
     */
    private DateTime $lastTested;

    /**
     * @ORM\ManyToOne(targetEntity=Partner::class, inversedBy="blacklists")
     */
    private Partner $partner;

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

    public function getLastTested(): DateTime
    {
        return $this->lastTested;
    }

    public function setLastTested(DateTime $lastTested): self
    {
        $this->lastTested = $lastTested;

        return $this;
    }

    public function getPartner(): Partner
    {
        return $this->partner;
    }

    public function setPartner(Partner $partner): self
    {
        $this->partner = $partner;

        return $this;
    }
}
