<?php

namespace App\Document;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @MongoDB\Document
 */
class HeureTravaille
{
      /**
     * @MongoDB\Id
     */
    protected $id;
   /**
     * @MongoDB\Field(type="string")
     */
    protected $idEmp;

     /**
     * @MongoDB\Field(type="string")
     */
    protected $numrosociete;
    /**
     * @MongoDB\Field(type="string")
     */
    protected $nom;
    /**
     * @MongoDB\Field(type="string")
     */
    protected $nbrheure;
    /**
     * @MongoDB\Field(type="string")
     */
    protected $jour;
  

    
    public function getId(): ?string
    {
        return $this->id;
    }
    public function getIdEmp(): ?string
    {
        return $this->idEmp;
    }
    public function getNumrosociete(): ?string
    {
        return $this->numrosociete;
    }
    public function getJour(): ?string
    {
        return $this->jour;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }
    public function getNbrheure(): ?string
    {
        return $this->nbrheure;
    }
   


  
    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }
    public function setIdEmp(string $idEmp): self
    {
        $this->idEmp = $idEmp;

        return $this;
    }
    public function setNumrosociete(string $numrosociete): self
    {
        $this->numrosociete = $numrosociete;

        return $this;
    }
    public function setJour(string $jour): self
    {
        $this->jour = $jour;

        return $this;
    }
    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }
    public function setNbrheure(string $nbrheure): self
    {
        $this->nbrheure = $nbrheure;

        return $this;
    }
    
}