<?php

namespace App\Document;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
/**
 * @MongoDB\Document
 */
class ParametrageEmail 
{
      /**
     * @MongoDB\Id
     */
    protected $id;
    
   
    
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
    protected $host;
      /**
     * @MongoDB\Field(type="string")
     */
    protected $port;
    /**
     * @MongoDB\Field(type="string")
     */
    protected $username;

    
    public function getId(): ?string
    {
        return $this->id;
    }
    public function getHost(): ?string
    {
        return $this->host;
    }

    public function getPort(): ?string
    {
        return $this->port;
    }
    public function getUsername(): ?string
    {
        return $this->username;
    }
    

   
   
    
    public function getEmail(): ?string
    {
        return $this->email;
    }
    public function getPassword(): string
    {
        return (string) $this->password;

}


    public function setHost(string $host): self
    {
        $this->host = $host;

        return $this;
    }
    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }
    public function setPort(string $port): self
    {
        $this->port = $port;

        return $this;
    }
    public function setId(string $id): self
    {
        $this->id = $id;

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
}