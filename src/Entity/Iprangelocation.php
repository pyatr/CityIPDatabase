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

    #[ORM\Column(type: 'tinyint', options: ["unsigned" => true])]
    private ?int $ip_byte_1_from = null;

    #[ORM\Column(type: 'tinyint', options: ["unsigned" => true])]
    private ?int $ip_byte_1_to = null;

    #[ORM\Column(type: 'tinyint', options: ["unsigned" => true])]
    private ?int $ip_byte_2_from = null;

    #[ORM\Column(type: 'tinyint', options: ["unsigned" => true])]
    private ?int $ip_byte_2_to = null;

    #[ORM\Column(type: 'tinyint', options: ["unsigned" => true])]
    private ?int $ip_byte_3_from = null;

    #[ORM\Column(type: 'tinyint', options: ["unsigned" => true])]
    private ?int $ip_byte_3_to = null;

    #[ORM\Column(type: 'tinyint', options: ["unsigned" => true])]
    private ?int $ip_byte_4_from = null;

    #[ORM\Column(type: 'tinyint', options: ["unsigned" => true])]
    private ?int $ip_byte_4_to = null;

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

    public function getIpByte1From(): ?int
    {
        return $this->ip_byte_1_from;
    }

    public function setIpByte1From(int $ip_byte_1_from): static
    {
        $this->ip_byte_1_from = $ip_byte_1_from;

        return $this;
    }

    public function getIpByte1To(): ?int
    {
        return $this->ip_byte_1_to;
    }

    public function setIpByte1To(int $ip_byte_1_to): static
    {
        $this->ip_byte_1_to = $ip_byte_1_to;

        return $this;
    }

    public function getIpByte2From(): ?int
    {
        return $this->ip_byte_2_from;
    }

    public function setIpByte2From(int $ip_byte_2_from): static
    {
        $this->ip_byte_2_from = $ip_byte_2_from;

        return $this;
    }

    public function getIpByte2To(): ?int
    {
        return $this->ip_byte_2_to;
    }

    public function setIpByte2To(int $ip_byte_2_to): static
    {
        $this->ip_byte_2_to = $ip_byte_2_to;

        return $this;
    }

    public function getIpByte3From(): ?int
    {
        return $this->ip_byte_3_from;
    }

    public function setIpByte3From(int $ip_byte_3_from): static
    {
        $this->ip_byte_3_from = $ip_byte_3_from;

        return $this;
    }

    public function getIpByte3To(): ?int
    {
        return $this->ip_byte_3_to;
    }

    public function setIpByte3To(int $ip_byte_3_to): static
    {
        $this->ip_byte_3_to = $ip_byte_3_to;

        return $this;
    }

    public function getIpByte4From(): ?int
    {
        return $this->ip_byte_4_from;
    }

    public function setIpByte4From(int $ip_byte_4_from): static
    {
        $this->ip_byte_4_from = $ip_byte_4_from;

        return $this;
    }

    public function getIpByte4To(): ?int
    {
        return $this->ip_byte_4_to;
    }

    public function setIpByte4To(int $ip_byte_4_to): static
    {
        $this->ip_byte_4_to = $ip_byte_4_to;

        return $this;
    }
}
