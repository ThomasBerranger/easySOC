<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ModuleRepository")
 */
class Module
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Result", mappedBy="module")
     */
    private $result;

    public function __construct()
    {
        $this->result = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|Result[]
     */
    public function getResult(): Collection
    {
        return $this->result;
    }

    public function addResult(Result $result): self
    {
        if (!$this->result->contains($result)) {
            $this->result[] = $result;
            $result->setModule($this);
        }

        return $this;
    }

    public function removeResult(Result $result): self
    {
        if ($this->result->contains($result)) {
            $this->result->removeElement($result);
            // set the owning side to null (unless already changed)
            if ($result->getModule() === $this) {
                $result->setModule(null);
            }
        }

        return $this;
    }
}
