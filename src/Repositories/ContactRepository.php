<?php

namespace Agenda\Repositories;

use PDO;
use Agenda\Entities\Contacts;

class ContactRepository
{
     private PDO $pdo;

     public function __construct(PDO $pdo) 
     {
          $this->pdo = $pdo;
     }


     /**
      * Adiciona Dados no banco de dados usando prepared statments
      * @param \Agenda\Entities\Contacts $contacts
      * @return bool
      */
     public function create(Contacts $contacts): bool
     {
          $stmt = $this->pdo->prepare(
               'INSERT INTO contacts (name, phone_number, email, address) VALUES (:name, :phone_number, :email, :address);'
          );
          $stmt->bindValue(':name', $contacts->name);
          $stmt->bindValue(':phone_number', $contacts->phone_number);
          $stmt->bindValue(':email', $contacts->email);
          $stmt->bindValue(':address', $contacts->address);

          return $stmt->execute();
     }

     /**
      * Retorna um array com todos os contatos do banco de dados 
      * @return array
      */
     public function read(): array|null
     {
          $stmt = $this->pdo->query(
               'SELECT * FROM contacts;'
          );
          $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
          #$dataFormated = array_map($this->hydrate(...), $data);

          if (empty($data)){
               return null;
          }

          return $data;
     }


     public function update(Contacts $contacts)
     {
          
     }






     private function hydrate(array $data): Contacts
     {
          $contact = new Contacts($data['name'], $data['phone_number'], $data['email'], $data['address']);
          $contact->setId($data['id']);
          $contact->setCreatedAt($data['created_at']);

          return $contact;
     }
}
