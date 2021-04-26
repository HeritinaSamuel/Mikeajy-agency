<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(
 *      fields={"email"},
 *      message="Une autre compte est deja souscrit avec cette email, veuillez le modifier"
 * )
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(
     *      message="Veuillez renseigner votre prenom"
     * )
     * @Assert\Length(
     *      min=3,
     *      minMessage="Votre prenom doit avoir au moins 3 caracteres"
     * )
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(
     *      message="Veuillez renseigner votre nom"
     * )
     * @Assert\Length(
     *      min=3,
     *      minMessage="Votre nom doit avoir au moins 3 caracteres"
     * )
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Email(
     *      message="Veuillez renseigner un email valide"
     * )
     * @Assert\NotBlank(
     *      message="Veuillez entrer votre email"
     * )
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Image(
     *      minWidth=70, minWidthMessage="La resolution de votre avatar est trop petit",
     *      minHeight=60, minHeightMessage="La resolution de votre avatar est trop petit",
     *      maxWidth=150, maxWidthMessage="La resolution de votre avatar est trop grand",
     *      maxHeight=150, maxHeightMessage="La resolution de votre avatar est trop grand"
     * )
     * @Assert\File(
     *     maxSize = "100k",
     *     maxSizeMessage="Veuillez uploader un image moins de 100Ko",
     *     mimeTypes = {"image/jpeg", "image/png","image/jpg"},
     *     mimeTypesMessage = "Veuilez uploader un image valide"
     * )
     */
    private $avatar;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *      min=8,
     *      minMessage="Le mot de passe doit avoir au moins 8 caractere"
     * )
     * @Assert\NotBlank(
     *      message="Veuillez entrer votre mot de passe"
     * )
     */
    private $hash;

    /**
     * @Assert\EqualTo(
     *      propertyPath="hash",
     *      message="Vous n'avez pas confirme votre mot de passe"
     * )
     * @Assert\NotBlank(
     *      message="Veuillez renseigner la confirmation de votre mot de passe"
     * )
     */
    public $passwordConfirm;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min=20, minMessage="L'intoduction doit avoir au moins 20 caractere")
     * @Assert\NotBlank(
     *      message="Veuillez renseigner une petite introduction"
     * )
     */
    private $biographie;

    /**
     * @ORM\Column(type="text")
     * @Assert\Length(min=100, minMessage="Le contenu doit avoir au moins 100 caractere")
     * @Assert\NotBlank(
     *      message="Veuillez renseigner votre description"
     * )
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity=Ad::class, mappedBy="author", orphanRemoval=true)
     */
    private $ads;

    /**
     * @ORM\ManyToMany(targetEntity=Role::class, mappedBy="users")
     */
    private $userRoles;

    /**
     * @ORM\OneToMany(targetEntity=Booking::class, mappedBy="booker")
     */
    private $bookings;

    public function fullName()
    {
        return ($this->name .' '. $this->lastName);
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
            $this->slug = $slugify->slugify($this->lastName . '-' .$this->name);
        }
    }
    
    public function __construct()
    {
        $this->ads = new ArrayCollection();
        $this->userRoles = new ArrayCollection();
        $this->bookings = new ArrayCollection();
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

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getHash(): ?string
    {
        return $this->hash;
    }

    public function setHash(string $hash): self
    {
        $this->hash = $hash;

        return $this;
    }

    public function getBiographie(): ?string
    {
        return $this->biographie;
    }

    public function setBiographie(string $biographie): self
    {
        $this->biographie = $biographie;

        return $this;
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
     * @return Collection|Ad[]
     */
    public function getAds(): Collection
    {
        return $this->ads;
    }

    public function addAd(Ad $ad): self
    {
        if (!$this->ads->contains($ad)) {
            $this->ads[] = $ad;
            $ad->setAuthor($this);
        }

        return $this;
    }

    public function removeAd(Ad $ad): self
    {
        if ($this->ads->removeElement($ad)) {
            // set the owning side to null (unless already changed)
            if ($ad->getAuthor() === $this) {
                $ad->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return (Role|string)[] The user roles
     */
    public function getRoles()
    {
        $roles = $this->userRoles->map(function($role){
            return $role->getTitle();
        })->toArray();

        $roles[] = 'ROLE_USER';

        return $roles;
    }

    /**
     * @return string|null
     */
    public function getPassword(){
        return $this->hash;
    }

    public function getSalt()
    {
        # code...
    }

    public function getUsername()
    {
        return $this->email;
    }

    public function eraseCredentials()
    {
        # code...
    }

    /**
     * @return Collection|Role[]
     */
    public function getUserRoles(): Collection
    {
        return $this->userRoles;
    }

    public function addUserRole(Role $userRole): self
    {
        if (!$this->userRoles->contains($userRole)) {
            $this->userRoles[] = $userRole;
            $userRole->addUser($this);
        }

        return $this;
    }

    public function removeUserRole(Role $userRole): self
    {
        if ($this->userRoles->removeElement($userRole)) {
            $userRole->removeUser($this);
        }

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
            $booking->setBooker($this);
        }

        return $this;
    }

    public function removeBooking(Booking $booking): self
    {
        if ($this->bookings->removeElement($booking)) {
            // set the owning side to null (unless already changed)
            if ($booking->getBooker() === $this) {
                $booking->setBooker(null);
            }
        }

        return $this;
    }
}
