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
      * @param bool $associative Quando `true`, retorna um array associativo; Quando `false`, retorna um objeto. Default = true
      */
     public function read(bool $associative = true): array|null
     {
          $stmt = $this->pdo->query(
               'SELECT * FROM contacts;'
          );

          $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
          if (empty($data)){
               return null;
          }
          
          if ($associative === true){
               return $data;
          }else{
               $dataAsObj = array_map($this->hydrate(...), $data);
               return $dataAsObj;
          }
     }



     public function readById(int $id, bool $associative = true): Contacts|array|null
     {
          $stmt = $this->pdo->prepare(
               'SELECT * FROM contacts WHERE id = :id;'
          );
          $stmt->bindValue(':id', $id);
          $stmt->execute();

          $data = $stmt->fetch(PDO::FETCH_ASSOC);
          
          if (empty($data)){
               return null;
          }

          return $data;
     }


     /**
      * Summary of update
      * @param \Agenda\Entities\Contacts $contacts
      * @return bool
      */
     public function update(Contacts $contacts): bool
     {
          $stmt = $this->pdo->prepare(
               'UPDATE contacts SET name = :name, phone_number = :phone_number, email = :email, address = :address WHERE id = :id;'
          );
          $stmt->bindValue(':name', $contacts->name);
          $stmt->bindValue(':phone_number', $contacts->phone_number);
          $stmt->bindValue(':email', $contacts->email);
          $stmt->bindValue(':address', $contacts->address);
          $stmt->bindValue(':id', $contacts->getId());

          return $stmt->execute();

     }


     /**
      * Summary of delete
      * @param int $id
      * @return bool
      */
     public function delete(int $id)
     {
          $stmt = $this->pdo->prepare(
               'DELETE FROM contacts WHERE id = :id;'
          );
          $stmt->bindValue(':id', $id);
          
          return $stmt->execute();
     }



     /**
      * Summary of hydrate
      * @param array $data
      * @return Contacts
      */
     private function hydrate(array $data): Contacts
     {
          $contact = new Contacts($data['name'], $data['phone_number'], $data['email'], $data['address']);
          $contact->setId($data['id']);
          $contact->setCreatedAt($data['created_at']);

          return $contact;
     }
}
