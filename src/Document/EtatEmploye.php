<?php

namespace App\Document;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @MongoDB\Document
 */
class EtatEmploye
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
    protected $jour;
    /**
     * @MongoDB\Field(type="string")
     */
    protected $etat;
   
  

    
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

    public function getEtat(): ?string
    {
        return $this->etat;
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
    public function setEtat(string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }
    
}