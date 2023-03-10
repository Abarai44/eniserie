<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;


#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'Il y a déja un compte avec cette adresse mail')]
#[UniqueEntity(fields: ['pseudo'], message: 'Il y a déja un compte avec ce pseudo')]
#[Vich\Uploadable]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\Length([], max: 250, maxMessage: '180 caractères maximum')]
    #[Assert\Email(
        message: " Votre Email est non valide.",
    )]
    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */

    #[Assert\Length([], min: 8, max: 100, minMessage: '8 caractères minimun', maxMessage: '50 caractères maximum')]
    #[ORM\Column]
    private ?string $password = null;

    #[Assert\Length([], min: 4, max: 30, minMessage: '4 caractères minimun', maxMessage: '30 caractères maximum')]
    #[Assert\NotBlank]
    #[ORM\Column(length: 30)]
    private ?string $pseudo = null;

    #[Assert\Length([], min: 2, max: 30, minMessage: '2 caractères minimun', maxMessage: '30 caractères maximum')]
    #[Assert\NotBlank]
    #[ORM\Column(length: 30)]
    private ?string $nom = null;

    #[Assert\Length([], min: 2, max: 30, minMessage: '2 caractères minimun', maxMessage: '30 caractères maximum')]
    #[Assert\NotBlank]
    #[ORM\Column(length: 30)]
    private ?string $prenom = null;
    #[Assert\Regex("/^0[1-9]\d{8}$/", message: "Le numéro de téléphone {{ value }} n'est pas un numéro de téléphone valide en France.")]
    #[ORM\Column(length: 15, nullable: true)]
    private ?string $telephone = null;


    #[ORM\Column]
    private ?bool $actif = true;

    #[ORM\ManyToOne(inversedBy: 'users')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Site $site = null;

    #[ORM\OneToMany(mappedBy: 'organisateur', targetEntity: Sortie::class)]
    private Collection $organisateur;

    #[ORM\ManyToMany(targetEntity: Sortie::class, inversedBy: 'participants')]
    private Collection $participant;

    #[ORM\Column(nullable: true)]
    private $photo = null;

    #[assert\File(maxSize: "2M", maxSizeMessage: ' 2 mega max pour une photo')]
    #[Vich\UploadableField(mapping: 'user_avatar', fileNameProperty: 'photo')]
    private $imageFile;


    #[ORM\Column(nullable: true)] private ?\DateTime $updatedAct;


    public function __construct()
    {
        $this->organisateur = new ArrayCollection();
        $this->participant = new ArrayCollection();


    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string)$this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }


    public function isActif(): ?bool
    {
        return $this->actif;
    }

    public function setActif(bool $actif): self
    {
        $this->actif = $actif;

        return $this;
    }

    public function getSite(): ?Site
    {
        return $this->site;
    }

    public function setSite(?Site $site): self
    {
        $this->site = $site;

        return $this;
    }

    /**
     * @return Collection<int, Sortie>
     */
    public function getOrganisateur(): Collection
    {
        return $this->organisateur;
    }

    public function addOrganisateur(Sortie $organisateur): self
    {
        if (!$this->organisateur->contains($organisateur)) {
            $this->organisateur->add($organisateur);
            $organisateur->setOrganisateur($this);
        }

        return $this;
    }

    public function removeOrganisateur(Sortie $organisateur): self
    {
        if ($this->organisateur->removeElement($organisateur)) {
            // set the owning side to null (unless already changed)
            if ($organisateur->getOrganisateur() === $this) {
                $organisateur->setOrganisateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Sortie>
     */
    public function getParticipant(): Collection
    {
        return $this->participant;
    }

    public function addParticipant(Sortie $participant): self
    {
        if (!$this->participant->contains($participant)) {
            $this->participant->add($participant);
        }

        return $this;
    }

    public function removeParticipant(Sortie $participant): self
    {
        $this->participant->removeElement($participant);

        return $this;
    }

    public function getPhoto()
    {
        return $this->photo;
    }

    public function setPhoto($photo)
    {
        $this->photo = $photo;

    }

    public function getImageFile()
    {
        return $this->imageFile;
    }

    public function setImageFile(File $photo = null)
    {
        $this->imageFile = $photo;

        if ($photo) {
            // if 'updatedAt' is not defined in your entity, use another property
            $this->updatedAct = new \DateTime('now');
        }

    }

    public function __serialize(): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'imageFile' => base64_encode($this->imageFile),
            'password' => $this->password,
        ];
    }

    public function __unserialize(array $serialized)
    {
        $this->imageFile = base64_decode($serialized['imageFile']);
        $this->email = $serialized['email'];
        $this->id = $serialized['id'];
        $this->password = $serialized['password'];
        return $this;
    }

}
