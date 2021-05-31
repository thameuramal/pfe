<?php

namespace App\Document;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @MongoDB\Document
 */
class AlerteReatard
{
      /**
     * @MongoDB\Id
     */
    protected $id;

    
    /**
     * @MongoDB\Field(type="string")
     */
    protected $idemp;
   
     /**
     * @MongoDB\Field(type="string")
     */
    protected $societe;
     /**
     * @MongoDB\Field(type="string")
     */
    protected $date;
    /**
     * @MongoDB\Field(type="string")
     */
    protected $nbalerte;
   

    
    public function getId(): ?string
    {
        return $this->id;
    }
    public function getIdemp(): ?string
    {
        return $this->idemp;
    }

 
    public function getDate(): ?string
    {
        return $this->date;
    }

    public function getNbAlerte(): ?string
    {
        return $this->nbalerte;
    }
     
   


   
    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }
    public function setDate(string $date): self
    {
        $this->date = $date;

        return $this;
    }
    public function setIdemp(string $idemp): self
    {
        $this->idemp = $idemp;

        return $this;
    }
    public function setNbAlerte(string $nbalerte): self
    {
        $this->nbalerte = $nbalerte;

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
}