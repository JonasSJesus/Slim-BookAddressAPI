<?php

namespace Agenda\Entities;

class Contacts
{
     private int $id;
     public readonly string $name;
     public readonly int $phone_number;
     public readonly string $email;
     public readonly string $address;
     public readonly string $created_at;

     public function __construct(string $name, int $phone_number, string $email, string $address) {
          $this->name = $name;
          $this->phone_number = $phone_number;
          $this->email = $email;
          $this->address = $address;
     }


     public function getId(): int
     {
         return $this->id;
     }

     public function setId(int $id): self
     {
         $this->id = $id;
 
         return $this;
     }


    public function setCreatedAt(string $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }
}
