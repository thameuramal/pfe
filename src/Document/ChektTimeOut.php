<?php

namespace App\Document;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @MongoDB\Document
 */
class ChektTimeOut
{
      /**
     * @MongoDB\Id
     */
    protected $id;
      /**
      
     * @MongoDB\Field(type="string")
     */
    protected $date;

    /**
     * @MongoDB\Field(type="string")
     */
     protected $idEmp;
    /**
     * @MongoDB\Field(type="string")
     */
    protected $numeroSocietes;
     /**
      
     * @MongoDB\Field(type="string")
     */
    protected $temps;
     /**
     * @MongoDB\Field(type="string")
     */
    protected $nomEmp;


    
    public function getId(): ?string
    {
        return $this->id;
    }
    public function getIdEmp(): ?string
    {
        return $this->idEmp;
    }
    public function getNomEmp(): ?string
    {
        return $this->nomEmp;
    }
    public function getDate(): ?string
    {
        return $this->date;
    }
   
    public function getNumeroSocietes(): ?string
    {
        return $this->numeroSocietes;
    }
    
    public function getTemps(): ?string
    {
        return $this->temps;
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
    public function setNomEmp(string $nomEmp): self
    {
        $this->nomEmp = $nomEmp;

        return $this;
    }
    
    public function setNumeroSocietes(string $numeroSocietes): self
    {
        $this->numeroSocietes = $numeroSocietes;

        return $this;
    }
    public function setTemps(string $temps): self
    
    {
        
        $this->temps = $temps;

        return $this;
    }
    public function setDate(string $date): self
    
    {
        
        $this->date = $date;

        return $this;
    }
    public function __toString() {
        return $this->date;
    }
    
}