<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Utils\IngredientSerializer;

/**
 * @ORM\Entity(repositoryClass="App\Repository\IngredientRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Ingredient
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("recipes")
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("recipes")
     */
    private $unit;


    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Intolerance", inversedBy="ingredients")
     */
    private $intolerences;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Recipe", inversedBy="ingredients")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $recipe;

    /**
     * @ORM\Column(type="integer")
     * @Groups("recipes")
     */
    private $quantity;

    public function __construct()
    {
        $this->intolerences = new ArrayCollection();
    }

    /**
    * @ORM\PrePersist
    * @ORM\PreUpdate
    */
    public function serialize()
    {
        $serializer = new IngredientSerializer();
        $this->name = $serializer->serializer($this->name);
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

    public function getUnit(): ?string
    {
        return $this->unit;
    }

    public function setUnit(string $unit): self
    {
        $this->unit = $unit;

        return $this;
    }

    /**
     * @return Collection|Intolerance[]
     */
    public function getIntolerences(): Collection
    {
        return $this->intolerences;
    }

    public function addIntolerence(Intolerance $intolerence): self
    {
        if (!$this->intolerences->contains($intolerence)) {
            $this->intolerences[] = $intolerence;
        }

        return $this;
    }

    public function removeIntolerence(Intolerance $intolerence): self
    {
        if ($this->intolerences->contains($intolerence)) {
            $this->intolerences->removeElement($intolerence);
        }

        return $this;
    }

    public function __toString()
    {
        return $this->name;
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

    public function getQuantity(): ?string
    {
        return $this->quantity;
    }

    public function setQuantity(string $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }
}
