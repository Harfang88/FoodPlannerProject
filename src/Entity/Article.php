<?php

namespace App\Entity;

use App\Utils\Slugger;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ArticleRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Article
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups("blog")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("blog")
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     * @Groups("blog")
     */
    private $content;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("blog")
     */
    private $slug;

    /**
     * @ORM\Column(type="datetime")
     * @Groups("blog")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="articles")
     * @Groups("blog")
     */
    private $user;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Category", inversedBy="articles")
     * @Groups("blog")
     */
    private $category;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Review", mappedBy="article", cascade="remove")
     * @Groups("blog")
     */
    private $reviews;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("blog")
     */
    private $image;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Groups("blog")
     */
    private $objectID;

    public function __construct()
    {
        $this->category = new ArrayCollection();
        $this->reviews = new ArrayCollection();
        $this->createdAt = new \DateTime();
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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

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

    /**
     * @return Collection|Category[]
     */
    public function getCategory(): Collection
    {
        return $this->category;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->category->contains($category)) {
            $this->category[] = $category;
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        if ($this->category->contains($category)) {
            $this->category->removeElement($category);
        }

        return $this;
    }

    /**
     * @return Collection|Review[]
     */
    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function addReview(Review $review): self
    {
        if (!$this->reviews->contains($review)) {
            $this->reviews[] = $review;
            $review->setArticle($this);
        }

        return $this;
    }

    public function removeReview(Review $review): self
    {
        if ($this->reviews->contains($review)) {
            $this->reviews->removeElement($review);
            // set the owning side to null (unless already changed)
            if ($review->getArticle() === $this) {
                $review->setArticle(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->title;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

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
