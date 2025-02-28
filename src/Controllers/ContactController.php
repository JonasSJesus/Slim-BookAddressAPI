<?php

namespace Agenda\Controllers;

use Agenda\Entities\Contacts;
use Agenda\Repositories\ContactRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ContactController
{
     private ContactRepository $contactRepository;
     
     public function __construct(ContactRepository $contactRepository) {
          $this->contactRepository = $contactRepository;
     }



     public function read(Request $request, Response $response): Response
     {
          // Busca dados do banco de dados. Se não vier nada, retorna um 404
          $dbData = $this->contactRepository->read();
          if ($dbData == null) {
               $body = [
                    "error" => "nenhum dado encontrado!"
               ];
               $payload = json_encode($body);

               $response->getBody()->write($payload);
               return $response
                    ->withHeader('Content-Type', 'application/json')
                    ->withStatus(404);
          }
     
          // escrevendo no corpo da requisição a o payload, que são os dados do banco
          $payload = json_encode($dbData);          

          $response->getBody()->write($payload);
          return $response
               ->withHeader('Content-Type', 'application/json');
     }


     public function create(Request $request, Response $response): Response
     {
          // Pega a requisicao e transforma em array assoc
          $request = $request->getBody()->getContents(); // Capturando arquivo JSON da Stream de input (php://input)
          $contactData = json_decode($request, true);

          // Validações de dados
          if (!$dataValidated = $this->validateDataFromRequest($contactData)){
               $payload = json_encode(['Error' => 'Dados invalidos']);
               $response->getBody()->write($payload);

               return $response
                    ->withHeader('Content-Type', 'application/json')
                    ->withStatus(400);
          }

          $contact = $this->createObject($contactData);
          
          // Salvando no Banco de Dados. Se não salvar, cai no bloco IF
          $saveToDB = $this->contactRepository->create($contact);

          if (!$saveToDB) { 
               $payload = json_encode(['Error' => 'Erro ao salvar dados']);
               $response->getBody()->write($payload);

               return $response
                    ->withHeader('Content-Type', 'application/json')
                    ->withStatus(503);
          }

          return $response
               ->withHeader('Content-Type', 'application/json')
               ->withStatus(201);
     }



     /**
      * Valida os dados vindos da Request depois de decodificados de JSON para array
      * @param array $contactsRequest
      * @return bool
      */
     private function validateDataFromRequest(array $contactsRequest): bool
     {
          /** 
           * TODO: melhorar a forma de validação e os retornos
           */
          if (!array_key_exists('name', $contactsRequest)){
               return false;
          }
          
          if (!array_key_exists('phone_number', $contactsRequest)){
               return false;
          }
          
          if (!array_key_exists('email', $contactsRequest)){
               return false;
          }
          if (!array_key_exists('address', $contactsRequest)){
               return false;
          }

          return true;
     }
     private function createObject(array $contactsRequest)#: Contacts
     {
          return new Contacts($contactsRequest['name'], $contactsRequest['phone_number'], $contactsRequest['email'], $contactsRequest['address']);
     }
}
