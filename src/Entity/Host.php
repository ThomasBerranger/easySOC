<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\HostRepository")
 */
class Host
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $OS;

    /**
     * @ORM\Column(type="integer")
     */
    private $Port;

     /**
     * @ORM\Column(type="integer")
     */
    private $IP;

     /**
     * @ORM\Column(type="integer")
     */
    private $Service;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOS(): ?string
    {
        return $this->OS;
    }

    public function setOS(string $OS): self
    {
        $this->OS = $OS;

        return $this;
    }

    public function getPort(): ?int
    {
        return $this->Port;
    }

    public function setPort(int $Port): self
    {
        $this->Port = $Port;

        return $this;
    }

    public function getIP(): ?string
    {
        return $this->IP;
    }

    public function setIP(string $IP): self
    {
        $this->IP = $IP;

        return $this;
    }

    public function getService(): ?string
    {
        return $this->Service;
    }

    public function setService(string $Service): self
    {
        $this->Service = $Service;

        return $this;
    }
}
