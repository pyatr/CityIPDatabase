<?php

namespace App\Entity;

use App\Repository\IprangelocationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: IprangelocationRepository::class)]
class Iprangelocation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 128)]
    private ?string $city = null;

    #[ORM\Column(options: ["unsigned" => true])]
    private ?int $ip_address_from = null;

    #[ORM\Column(options: ["unsigned" => true])]
    private ?int $ip_address_to = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getIpAddressFrom(): ?int
    {
        return $this->ip_address_from;
    }

    public function setIpAddressFrom(int $ip_address_from): static
    {
        $this->ip_address_from = $ip_address_from;

        return $this;
    }

    public function getIpAddressTo(): ?int
    {
        return $this->ip_address_to;
    }

    public function setIpAddressTo(int $ip_address_to): static
    {
        $this->ip_address_to = $ip_address_to;

        return $this;
    }
}
