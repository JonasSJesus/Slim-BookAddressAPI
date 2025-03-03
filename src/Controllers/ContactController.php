<?php

namespace Agenda\Controllers;

use Agenda\Entities\Contacts;
use Agenda\Repositories\ContactRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ContactController
{
	private ContactRepository $contactRepository;
	////private ContactService $contactService;
	
	public function __construct(ContactRepository $contactRepository) {
		$this->contactRepository = $contactRepository;
	}
	
	
	
	/**
	* Controlador para manipular leitura de dados do banco
	* @param \Psr\Http\Message\ServerRequestInterface $request
	* @param \Psr\Http\Message\ResponseInterface $response
	* @return Response
	*/
	public function read(Request $request, Response $response): Response
	{
		// Busca dados do banco de dados. Se não vier nada, retorna um 404
		$dbData = $this->contactRepository->read(true);
		
		
		if ($dbData == null) {
			$payload = json_encode([
				"error" => "nenhum dado encontrado!"
			]);
			
			$response->getBody()->write($payload);                                // Escreve o payload json no corpo da resposta
			return $response                                                              // Retorna a resposta com as modificações definidas abaixo:
				->withHeader('Content-Type', 'application/json')               // Define o tipo de retorno para json
				->withStatus(404); ;                                                  // Define o status-code do retorno
			}
			
			
			// Transforma o retorno do banco em JSON, escreve no corpo da resposta e retorna a resposta
			$payload = json_encode($dbData);
			$response->getBody()->write($payload);
			return $response
				->withHeader('Content-Type', 'application/json')
				->withStatus(200);
		}
		
		
		/**
		* Controlador para inserir dados do banco
		* @param \Psr\Http\Message\ServerRequestInterface $request
		* @param \Psr\Http\Message\ResponseInterface $response
		* @return Response
		*/
		public function create(Request $request, Response $response): Response
		{
			
			$request = $request->getBody()->getContents();                   // Capturando arquivo JSON da Stream de input (php://input)
			$contactData = json_decode($request, true);   // Pega a requisicao e transforma em array assoc
			
			
			// Validações de dados
			if (!$this->validateDataFromRequest($contactData)){
				$payload = json_encode(['Error' => 'Dados invalidos']); 
				
				// Manipulando a Resposta
				$response->getBody()->write($payload);
				return $response
					->withHeader('Content-Type', 'application/json')
					->withStatus(400);
			}
			
			$contact = $this->createObject($contactData);
			
			// Salvando no Banco de Dados.
			$saveToDB = $this->contactRepository->create($contact);
			
			// Se não conseguir salvar, cai no bloco IF
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
		* Controlador para atualizar dados do banco
		* @param \Psr\Http\Message\ServerRequestInterface $request
		* @param \Psr\Http\Message\ResponseInterface $response
		* @return Response
		*/
		public function update(Request $request, Response $response, array $args): Response
		{
			$request = $request->getBody()->getContents();                   // Capturando arquivo JSON da Stream de input (php://input) 
			$dataRequest = json_decode($request, true);   // Pega a requisicao e transforma em array assoc
			
			// Valida os dados da requisição, se não estiverem com o formato aceito, retorna um erro
			if(!$this->validateDataFromRequest($dataRequest)){
				$payload = json_encode([
					"Error" => "Dados invalidos"
				]);
				$response->getBody()->write($payload);
				
				return $response
					->withHeader('Content-Type', 'application/json')
					->withStatus(400);
			}
			
			// Verifica se o valor de {id} inserido na url (e recuperado com $args) é um inteiro
			if (!filter_var($args['id'], FILTER_VALIDATE_INT)){
				$payload = json_encode([
					"Error" => "Usuario não encontrado"
				]);
				$response->getBody()->write($payload);
				
				return $response
					->withHeader('Content-Type', 'application/json')
					->withStatus(404);
			}
			
			$id = (int) $args['id'];
			
			$contact = $this->createObject($dataRequest);
			$contact->setId($id);
			
			$updateInDb = $this->contactRepository->update($contact);
			
			if(!$updateInDb) {
				$payload = json_encode(['Error' => 'Erro ao atualizar dados']);
				
				$response->getBody()->write($payload);
				return $response
					->withHeader('Content-Type', 'application/json')
					->withStatus(503);
			}
			
			$payload = json_encode(['Success' => 'Contato Atualizado com sucesso!']);
			$response->getBody()->write($payload);
			return $response
				->withHeader('Content-Type', 'application/json')
				->withStatus(200);
		}
		
		
		
		public function readOne(Request $request, Response $response, array $args): Response
		{
			if(!$this->validateIntAndReturnResponse($args)){
				$payload = json_encode(['Error' => 'Contato não encontrado']);
				
				$response->getBody()->write($payload);
				return $response
					->withHeader('Content-Type', 'application/json')
					->withStatus(503);
			}
			$id = $args['id'];
			
			$contactData = $this->contactRepository->readById($id);
			if ($contactData == null) {
				$payload = json_encode(['Error' => 'Contato não encontrado']);
				
				$response->getBody()->write($payload);
				return $response
					->withHeader('Content-Type', 'application/json')
					->withStatus(503);
			}
			
			$payload = json_encode($contactData);
			
			$response->getBody()->write($payload);
			return $response
				->withHeader('Content-Type', 'application/json')
				->withStatus(200);
		}
		
		//=======================================|Helpers|===========================================//
		
		
		/**
		* Valida os dados vindos da Request depois de decodificados de JSON para array
		* @param array $contactsRequest
		* @return bool
		*/
		private function validateDataFromRequest(array|null $contactsRequest): bool
		{
			/** 
			* TODO: melhorar a forma de validação e os retornos
			* ! Pelo amor de Deus não deixa esse código assim!
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
		
		private function validateIntAndReturnResponse(array $args): bool
		{
			return filter_var($args['id'], FILTER_VALIDATE_INT);
		}
		
		private function createObject(array $contactsRequest): Contacts
		{
			return new Contacts(
				$contactsRequest['name'],
				$contactsRequest['phone_number'], 
				$contactsRequest['email'], 
				$contactsRequest['address']);
			}
		}
