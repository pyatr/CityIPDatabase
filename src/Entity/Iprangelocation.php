<?php

namespace App\Entity;

use App\Repository\IprangelocationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: IprangelocationRepository::class)]
// #[UniqueEntity('ip_range_from')]
// #[UniqueEntity('ip_range_to')]
class Iprangelocation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 16)]
    private ?string $ip_range_from = null;

    #[ORM\Column(length: 16)]
    private ?string $ip_range_to = null;

    #[ORM\Column(length: 128)]
    private ?string $city = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIpRangeFrom(): ?string
    {
        return $this->ip_range_from;
    }

    public function setIpRangeFrom(string $ip_range_from): static
    {
        $this->ip_range_from = $ip_range_from;

        return $this;
    }

    public function getIpRangeTo(): ?string
    {
        return $this->ip_range_to;
    }

    public function setIpRangeTo(string $ip_range_to): static
    {
        $this->ip_range_to = $ip_range_to;

        return $this;
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
}
