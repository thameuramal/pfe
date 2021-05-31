<?php

namespace App\Document;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
/**
 * @MongoDB\Document
 */
class ParametreGenerale
{
      /**
     * @MongoDB\Id
     */
    protected $id;
    
   
    /**
     * @MongoDB\Field(type="string")
     */
    protected $heurematin;
    /**
     * @MongoDB\Field(type="string")
     */
    protected $retardmatin;
    /**
     * @MongoDB\Field(type="string")
     */
    protected $heureapresmidi;
    /**
     * @MongoDB\Field(type="string")
     */
    protected $retardapresmidi;
    /** 
     * @MongoDB\Field(type="string")
     */
    protected $seancetravail;
    /**
     * @MongoDB\Field(type="string")
     */ 
   
    protected $url;
     /**
     *@MongoDB\Field(type="raw")
     */
    private $domaines = [];

    
    public function getId(): ?string
    {
        return $this->id;
    }
   
    public function getUrl(): ?string
    {
        return $this->url;
    }
    

    public function getHeurematin(): ?string
    {
        return $this->heurematin;
    }
    public function getRetardmatin(): ?string
    {
        return $this->retardmatin;
    }
    public function getHeureapresmidi(): ?string
    {
        return $this->heureapresmidi;
    }
    public function getSeancetravail(): ?string
    {
        return $this->seancetravail;
    }
    
    public function getRetardapresmidi(): ?string
    {
        return $this->retardapresmidi;
    }
   



    
    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }
    
    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }
    public function setHeurematin(string $heurematin): self
    {
        $this->heurematin = $heurematin;

        return $this;
    }
    public function setRetardmatin(string $retardmatin): self
    {
        $this->retardmatin = $retardmatin;

        return $this;
    } 
    public function setHeureapresmidi(string $heureapresmidi): self
    {
        $this->heureapresmidi = $heureapresmidi;

        return $this;
    }
    public function setSeancetravail(string $seancetravail): self
    {
        $this->seancetravail = $seancetravail;

        return $this;
    } 
    public function setDomaines(array $domaines): self
    {
        $this->domaines = $domaines;

        return $this;
    }
    
    public function setRetardapresmidi(string $retardapresmidi): self
    {
        $this->retardapresmidi = $retardapresmidi;

        return $this;
    }
  
    public function getDomaines(): array
    {
        
        
    $domaines = $this->domaines;
        
        $domaines[] = '';

        return $domaines;
    }
}