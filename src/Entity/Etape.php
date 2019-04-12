<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EtapeRepository")
 */
class Etape
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     * @Groups({"recipes"})
     */
    private $description;

    /**
     * @ORM\Column(type="integer")
     * @Groups("recipes")
     */
    private $etapeOrder;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Recipe", inversedBy="etapes")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $recipe;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getEtapeOrder(): ?int
    {
        return $this->etapeOrder;
    }

    public function setEtapeOrder(int $etapeOrder): self
    {
        $this->etapeOrder = $etapeOrder;

        return $this;
    }

    public function getRecipe(): ?Recipe
    {
        return $this->recipe;
    }

    public function setRecipe(?Recipe $recipe): self
    {
        $this->recipe = $recipe;

        return $this;
    }


}
