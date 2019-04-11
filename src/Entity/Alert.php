<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AlertRepository")
 */
class Alert
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $srcIp;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $srcPort;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $destIp;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $destPort;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $proto;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $action;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $signatureId;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $rev;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $signature;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $category;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $severity;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $flowId;

    /**
     * @ORM\Column(type="string", length=25, nullable=true)
     */
    private $inIface;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $http;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getSrcIp(): ?string
    {
        return $this->srcIp;
    }

    public function setSrcIp(?string $srcIp): self
    {
        $this->srcIp = $srcIp;

        return $this;
    }

    public function getSrcPort(): ?int
    {
        return $this->srcPort;
    }

    public function setSrcPort(?int $srcPort): self
    {
        $this->srcPort = $srcPort;

        return $this;
    }

    public function getDestIp(): ?string
    {
        return $this->destIp;
    }

    public function setDestIp(?string $destIp): self
    {
        $this->destIp = $destIp;

        return $this;
    }

    public function getDestPort(): ?int
    {
        return $this->destPort;
    }

    public function setDestPort(?int $destPort): self
    {
        $this->destPort = $destPort;

        return $this;
    }

    public function getProto(): ?string
    {
        return $this->proto;
    }

    public function setProto(?string $proto): self
    {
        $this->proto = $proto;

        return $this;
    }

    public function getAction(): ?string
    {
        return $this->action;
    }

    public function setAction(?string $action): self
    {
        $this->action = $action;

        return $this;
    }

    public function getSignatureId(): ?int
    {
        return $this->signatureId;
    }

    public function setSignatureId(?int $signatureId): self
    {
        $this->signatureId = $signatureId;

        return $this;
    }

    public function getRev(): ?int
    {
        return $this->rev;
    }

    public function setRev(?int $rev): self
    {
        $this->rev = $rev;

        return $this;
    }

    public function getSignature(): ?string
    {
        return $this->signature;
    }

    public function setSignature(?string $signature): self
    {
        $this->signature = $signature;

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(?string $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getSeverity(): ?int
    {
        return $this->severity;
    }

    public function setSeverity(?int $severity): self
    {
        $this->severity = $severity;

        return $this;
    }

    public function getFlowId(): ?int
    {
        return $this->flowId;
    }

    public function setFlowId(?int $flowId): self
    {
        $this->flowId = $flowId;

        return $this;
    }

    public function getInIface(): ?string
    {
        return $this->inIface;
    }

    public function setInIface(?string $inIface): self
    {
        $this->inIface = $inIface;

        return $this;
    }

    public function getHttp(): ?string
    {
        return $this->http;
    }

    public function setHttp(?string $http): self
    {
        $this->http = $http;

        return $this;
    }
}
