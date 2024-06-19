<?php

namespace App\Entity;

use App\Repository\CustomerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CustomerRepository::class)]
class Dealer extends User
{
    #[ORM\Column(length: 100)]
    private ?string $companyName = null;

    #[ORM\Column(length: 100)]
    private ?string $contactFirstname = null;

    #[ORM\Column(length: 100)]
    private ?string $contactLastname = null;

    #[ORM\Column(length: 100)]
    private ?string $phone = null;

    #[ORM\Column(length: 255)]
    private ?string $token = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $tokenExpireAt = null;

    /**
     * Get the value of companyName
     */
    public function getCompanyName(): ?string
    {
        return $this->companyName;
    }

    /**
     * Set the value of companyName
     */
    public function setCompanyName(?string $companyName): self
    {
        $this->companyName = $companyName;

        return $this;
    }

    public function getContactLastname(): ?string
    {
        return $this->contactLastname;
    }

    public function setContactLastname(string $contactLastname): static
    {
        $this->contactLastname = $contactLastname;

        return $this;
    }

    public function getContactFirstname(): ?string
    {
        return $this->contactFirstname;
    }

    public function setContactFirstname(string $contactFirstname): static
    {
        $this->contactFirstname = $contactFirstname;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get the value of token
     */
    public function getToken(): ?string
    {
        return $this->token;
    }

    /**
     * Set the value of token
     */
    public function setToken(?string $token): self
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get the value of tokenExpireAt
     */
    public function getTokenExpireAt(): ?\DateTimeImmutable
    {
        return $this->tokenExpireAt;
    }

    /**
     * Set the value of tokenExpireAt
     */
    public function setTokenExpireAt(?\DateTimeImmutable $tokenExpireAt): self
    {
        $this->tokenExpireAt = $tokenExpireAt;

        return $this;
    }

}
