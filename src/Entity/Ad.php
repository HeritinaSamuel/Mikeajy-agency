<?php

namespace App\Entity;

use App\Entity\Image;
use Cocur\Slugify\Slugify;
use App\Repository\AdRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=AdRepository::class)
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(
 *      fields={"title"},
 *      message="Une autre annonce possede deja le meme titre, veuillez le modifier"
 * )
 */
class Ad
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le titre de l'annonce ne doit pas etre vide")
     * @Assert\Length(min=10, minMessage="Le titre de l'annonce doit avoir au moins 10 caractere")
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank(message="Veillez renseigner le prix")
     */
    private $price;

    /**
     * @ORM\Column(type="text")
     * @Assert\Length(
     *      min=20, 
     *      minMessage="L'intoduction doit avoir au moins 20 caracteres"
     * )
     * @Assert\NotBlank(message="L'introduction ne doit pas etre vide")
     */
    private $introduction;

    /**
     * @ORM\Column(type="text")
     * @Assert\Length(
     *      min=100, 
     *      minMessage="Le contenu doit avoir au moins 200 caracteres"
     * )
     * @Assert\NotBlank(message="Le contenu ne doit pas etre vide")
     */
    private $content;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Veuillez uploader un image")
     * @Assert\Image(
     *      minWidth=400, 
     *      minWidthMessage="L'image de couverture doit avoir au moins 600 pixels de longueur",
     *      minHeight=250,
     *      minHeightMessage="L'image de couverture doit avoir au moins 400 pixels de largeur")
     */
    private $coverImage;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Positive(message="Veuillez renseigner un nombre superieur a zero")
     * @Assert\NotBlank(message="Le nombre de chambre ne doit pas etre vide")
     */
    private $rooms;

    /**
     * @ORM\OneToMany(targetEntity=Image::class, mappedBy="ad", orphanRemoval=true, cascade={"persist"})
     * @Assert\NotBlank(message="L'images ne doit pas etre vide")
     */
    private $images;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="ads")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\OneToMany(targetEntity=Booking::class, mappedBy="ad")
     */
    private $bookings;

    public function __construct()
    {
        $this->images = new ArrayCollection();
        $this->bookings = new ArrayCollection();
    }

    /**
     * Undocumented function
     *@ORM\PrePersist
     *@ORM\PreUpdate
     * @return void
     */
    public function initializeSlug()
    {
        if (empty($this->slug)) {
            $slugify = new Slugify();
            $this->slug = $slugify->slugify($this->title);
        }
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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getIntroduction(): ?string
    {
        return $this->introduction;
    }

    public function setIntroduction(string $introduction): self
    {
        $this->introduction = $introduction;

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

    public function getCoverImage(): ?string
    {
        return $this->coverImage;
    }

    public function setCoverImage(string $coverImage): self
    {
        $this->coverImage = $coverImage;

        return $this;
    }

    public function getRooms(): ?int
    {
        return $this->rooms;
    }

    public function setRooms(int $rooms): self
    {
        $this->rooms = $rooms;

        return $this;
    }

    /**
     * @return Collection|Image[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setAd($this);
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getAd() === $this) {
                $image->setAd(null);
            }
        }

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return Collection|Booking[]
     */
    public function getBookings(): Collection
    {
        return $this->bookings;
    }

    public function addBooking(Booking $booking): self
    {
        if (!$this->bookings->contains($booking)) {
            $this->bookings[] = $booking;
            $booking->setAd($this);
        }

        return $this;
    }

    public function removeBooking(Booking $booking): self
    {
        if ($this->bookings->removeElement($booking)) {
            // set the owning side to null (unless already changed)
            if ($booking->getAd() === $this) {
                $booking->setAd(null);
            }
        }

        return $this;
    }
}
