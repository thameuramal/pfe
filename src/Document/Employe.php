<?php

namespace App\Document;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
/**
 * @MongoDB\Document
 */
class Employe implements UserInterface
{
      /**
     * @MongoDB\Id
     */
    protected $id;
     /**
     *@MongoDB\Field(type="raw")
     */
    private $roles = [];
    /**
     * @MongoDB\Field(type="string")
     */
    protected $nom;
    /**
     * @MongoDB\Field(type="string")
     */
    protected $idemp;
    
     /**
      
     * @MongoDB\Field(type="string")
     */
    protected $dateDeNaissance;
    /**
      
     * @MongoDB\Field(type="string")
     * @Assert\NotBlank(message="Please, upload the photo.") 
     * @Assert\File(mimeTypes={ "image/png", "image/jpeg" }) 
     */
    protected $photo;
     /**
     * @MongoDB\Field(type="string")
     */
    protected $societe;
     /**
     * @MongoDB\Field(type="string", nullable=true)
     */
    private $reset_token;
    /**
     * @MongoDB\Field(type="string")
     */
    protected $role;
    /**
     * @MongoDB\Field(type="string")
     */
    protected $email;
     /**
     * @MongoDB\Field(type="string")
     */
    protected $password;
       /**
     * @MongoDB\Field(type="string")
     */
    protected $matricule;
      /**
     * @MongoDB\Field(type="string")
     */
    protected $numeroDeTelephone;

    
    public function getId(): ?string
    {
        return $this->id;
    }
    public function getIdemp(): ?string
    {
        return $this->idemp;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }
    public function getRole(): ?string
    {
        return $this->role;
    }
    

    public function getNumeroDeTelephone(): ?string
    {
        return $this->numeroDeTelephone;
    }
     
    public function getDateDeNaissance(): ?\DateTimeInterface
    {
        return $this->dateDeNaissance;
    }
    public function getMatricule(): ?string
    {
        return $this->matricule;
    }
  
    
    public function getEmail(): ?string
    {
        return $this->email;
    }
    public function getPhoto() 
    {
        return $this->photo;
    }
    public function setPhoto ($photo):self
    {
        $this->photo = $photo;

        return $this;
    }

    public function setMatricule(string $matricule): self
    {
        $this->matricule = $matricule;

        return $this;
    }
    public function setRole(string $role): self
    {
        $this->role = $role;

        return $this;
    }
    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }
    public function setIdemp(string $idemp): self
    {
        $this->idemp = $idemp;

        return $this;
    }
    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }
   
    public function setDateDeNaissance(string $dateDeNaissance): self
    
    {
        
        $this->dateDeNaissance = $dateDeNaissance;

        return $this;
    }
    public function setNumeroDeTelephone(string $numeroDeTelephone): self
    {
        $this->numeroDeTelephone = $numeroDeTelephone;

        return $this; 
    }
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }
    public function getSociete(): ?string
    {
        return $this->societe;
    }


    public function setSociete(string $societe): self
    {
        $this->societe= $societe;

        return $this;
    }
     /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }
    
    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        
        // guarantee every user at least has ROLE_USER
    //    return  ['ROLE_USER'];

    //     return array_unique($roles);
    $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = '';

        return array_unique($roles);
    }
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }
     /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }
     /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
     /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }
    public function getResetToken(): ?string
    {
        return $this->reset_token;
    }

    public function setResetToken(?string $reset_token): self
    {
        $this->reset_token = $reset_token;

        return $this;
    }
}