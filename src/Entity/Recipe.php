<?php

namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Utils\Slugger;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecipeRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Recipe
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups("recipes")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"recipes", "planning"})
     */
    private $title;

    /**
     * @ORM\Column(type="integer")
     * @Groups("recipes")
     */
    private $calorie;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("recipes")
     */
    private $slug;

    /**
     * @ORM\Column(type="integer")
     * @Groups("recipes")
     */
    private $difficulty;

    /**
     * @ORM\Column(type="time")
     * @Groups("recipes")
     */
    private $time;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("recipes")
     */
    private $picture;

    /**
     * @ORM\Column(type="datetime")
     * @Groups("recipes")
     */
    private $created_at;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups("recipes")
     */
    private $publishable;

    /**
     * @ORM\Column(type="boolean")
     * @Groups("recipes")
     */
    private $validated;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Etape", mappedBy="recipe", orphanRemoval=true, cascade={"persist"})
     * @Groups("recipes")
     */
    private $etapes;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Type", inversedBy="recipes")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     * @Groups("recipes")
     */
    private $type;



    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="recipe", orphanRemoval=true)
     * @Groups("recipes")
     */
    private $comments;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", mappedBy="favoris")
     * @Groups("recipes")
     */
    private $usersFavoris;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Note", mappedBy="recipe", orphanRemoval=true)
     * @Groups("recipes")
     */
    private $notes;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Planning", mappedBy="recipe", orphanRemoval=true)
     */
    private $plannings;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="recipes")
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     * @Groups("recipes")
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Ingredient", mappedBy="recipe", orphanRemoval=true, cascade={"persist"})
     * @Groups("recipes")
     */
    private $ingredients;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Groups("recipes")
     */
    private $objectID;

    public function __construct()
    {
        $this->etapes = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->usersFavoris = new ArrayCollection();
        $this->notes = new ArrayCollection();
        $this->created_at = new \DateTime();
        $this->ingredients = new ArrayCollection();
        
    }

    /**
    * @ORM\PrePersist
    * @ORM\PreUpdate
    */
    public function applySlug()
    {
        $slugger = new Slugger();
        $this->slug = $slugger->slugger($this->title);
        $this->objectID = uniqid();
    }

  
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getCalorie(): ?int
    {
        return $this->calorie;
    }

    public function setCalorie(int $calorie): self
    {
        $this->calorie = $calorie;

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

    public function getDifficulty(): ?int
    {
        return $this->difficulty;
    }

    public function setDifficulty(int $difficulty): self
    {
        $this->difficulty = $difficulty;

        return $this;
    }

    public function getTime()
    {
        return $this->time;
    }

    public function setTime($time): self
    {
        $this->time = $time;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(string $picture): self
    {
        $this->picture = $picture;

        return $this;
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

    public function getPublishable(): ?bool
    {
        return $this->publishable;
    }

    public function setPublishable(?bool $publishable): self
    {
        $this->publishable = $publishable;

        return $this;
    }

    public function getValidated(): ?bool
    {
        return $this->validated;
    }

    public function setValidated(bool $validated): self
    {
        $this->validated = $validated;

        return $this;
    }

    /**
     * @return Collection|Etape[]
     */
    public function getEtapes(): Collection
    {
        return $this->etapes;
    }

    public function addEtape(Etape $etape): self
    {
        if (!$this->etapes->contains($etape)) {
            $this->etapes[] = $etape;
            $etape->setRecipe($this);
        }

    }

    public function removeEtape(Etape $etape): self
    {
        if ($this->etapes->contains($etape)) {
            $this->etapes->removeElement($etape);
            // set the owning side to null (unless already changed)
            if ($etape->getRecipe() === $this) {
                $etape->setRecipe(null);
            }
        }

        return $this;
    }

    public function getType(): ?Type
    {
        return $this->type;
    }

    public function setType(?Type $type): self
    {
        $this->type = $type;

        return $this;
    }


    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setRecipe($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getRecipe() === $this) {
                $comment->setRecipe(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsersFavoris(): Collection
    {
        return $this->usersFavoris;
    }

    public function addUsersFavori(User $usersFavori): self
    {
        if (!$this->usersFavoris->contains($usersFavori)) {
            $this->usersFavoris[] = $usersFavori;
            $usersFavori->addFavori($this);
        }

        return $this;
    }

    public function removeUsersFavori(User $usersFavori): self
    {
        if ($this->usersFavoris->contains($usersFavori)) {
            $this->usersFavoris->removeElement($usersFavori);
            $usersFavori->removeFavori($this);
        }

        return $this;
    }

    /**
     * @return Collection|Note[]
     */
    public function getNotes(): Collection
    {
        return $this->notes;
    }

    public function addNote(Note $note): self
    {
        if (!$this->notes->contains($note)) {
            $this->notes[] = $note;
            $note->setRecipe($this);
        }

        return $this;
    }

    public function removeNote(Note $note): self
    {
        if ($this->notes->contains($note)) {
            $this->notes->removeElement($note);
            // set the owning side to null (unless already changed)
            if ($note->getRecipe() === $this) {
                $note->setRecipe(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function __toString()
    {
        return $this->title;
    }

    /**
     * @return Collection|Ingredient[]
     */
    public function getIngredients(): Collection
    {
        return $this->ingredients;
    }

    public function addIngredient(Ingredient $ingredient): self
    {
        if (!$this->ingredients->contains($ingredient)) {
            $this->ingredients[] = $ingredient;
            $ingredient->setRecipe($this);
        }

        return $this;
    }

    public function removeIngredient(Ingredient $ingredient): self
    {
        if ($this->ingredients->contains($ingredient)) {
            $this->ingredients->removeElement($ingredient);
            // set the owning side to null (unless already changed)
            if ($ingredient->getRecipe() === $this) {
                $ingredient->setRecipe(null);
            }
        }

        return $this;
    }

    public function getObjectID(): ?string
    {
        return $this->objectID;
    }

    public function setObjectID(?string $objectID): self
    {
        $this->objectID = $objectID;

        return $this;
    }

}
