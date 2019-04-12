<?php

namespace App\Entity;

use App\Utils\Slugger;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TypeRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Type
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
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Recipe", mappedBy="type", orphanRemoval=true)
     */
    private $recipes;

    public function __construct()
    {
        $this->recipes = new ArrayCollection();
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function applySlug()
    {
        $slugger = new Slugger();
        $this->slug = $slugger->slugger($this->getName());
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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return Collection|Recipe[]
     */
    public function getRecipes(): Collection
    {
        return $this->recipes;
    }

    public function addRecipe(Recipe $recipe): self
    {
        if (!$this->recipes->contains($recipe)) {
            $this->recipes[] = $recipe;
            $recipe->setType($this);
        }

        return $this;
    }

    public function removeRecipe(Recipe $recipe): self
    {
        if ($this->recipes->contains($recipe)) {
            $this->recipes->removeElement($recipe);
            // set the owning side to null (unless already changed)
            if ($recipe->getType() === $this) {
                $recipe->setType(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }
}
