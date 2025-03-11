<?php

namespace Agenda\Controllers\Contacts;

use Agenda\Entities\Contacts;
use Agenda\Services\ContactService;
use Agenda\Controllers\BaseController;
use Agenda\Repositories\ContactRepository;
use Agenda\Controllers\ControllerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ContactController extends BaseController 
{
    private ContactRepository $contactRepository;
    
    public function __construct(ContactRepository $contactRepository) {
        $this->contactRepository = $contactRepository;
    }
    
    
    
    /**
    * Controlador para manipular leitura de dados do banco
    *
    * @param \Psr\Http\Message\ServerRequestInterface $request
    * @param \Psr\Http\Message\ResponseInterface $response
    * @return Response
    */
    public function read(Request $request, Response $response): Response
    {   
        // Busca dados do banco de dados. Se não vier nada, retorna um 404
        $dbData = $this->contactRepository->read(true);

        if ($dbData == null) {
            $payload = ["error" => "nenhum dado encontrado!"];
            return $this->jsonResponse($response, $payload, 404);
        }
        
        
        // Transforma o retorno do banco em JSON, escreve no corpo da resposta e retorna a resposta com o status code
        return $this->jsonResponse($response, $dbData);
    }
    
    
    
    /**
     * 
     * Recupera um dado do banco com base no id passado na url (/contacts/{id})
     * 
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param array $args
     * @return Response
     */
    public function readOne(Request $request, Response $response, array $args): Response
    {
        if(!$this->validateIntAndReturnResponse($args['id'])){
            $payload = ['Error' => 'Contato não encontrado'];

            return $this->jsonResponse($response, $payload, 404);
        }

        $id = $args['id'];
        
        $contactData = $this->contactRepository->readById($id);
        if ($contactData == null) {
            $payload = ['Error' => 'Contato não encontrado'];
            
            return $this->jsonResponse($response, $payload, 404);
        }

        return $this->jsonResponse($response, $contactData);
    }
        
        

    /**
    * Controlador para inserir dados do banco

    * @param \Psr\Http\Message\ServerRequestInterface $request
    * @param \Psr\Http\Message\ResponseInterface $response
    * @return Response
    */
    public function create(Request $request, Response $response): Response
    {
        $params = $request->getParsedBody();                   // Capturando arquivo JSON da Stream de input (php://input) 
        // Validações de dados
        if (!$this->validateDataFromRequest($params)){
            $payload = ['Error' => 'Dados invalidos']; 
            
            return $this->jsonResponse($response, $payload, 400);
        }
        
        $contactOBJ = $this->hydrate($params);
        
        // Salvando no Banco de Dados.
        $saveToDB = $this->contactRepository->create($contactOBJ);
        
        // Se não conseguir salvar, cai no bloco IF
        if (!$saveToDB) { 
            $payload = ['Error' => 'Erro ao salvar dados'];
            
            return $this->jsonResponse($response, $payload, 503);
        }

        $payload = ["Success" => "Contato adicionado com sucesso!"];
        return $this->jsonResponse($response, $payload, 201);
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
            $payload = ["Error" => "Dados invalidos"];

            return $this->jsonResponse($response, $payload, 400);
        }
        
        // Verifica se o valor de {id} inserido na url (e recuperado com $args) é um inteiro
        if (!$this->validateIntAndReturnResponse($args['id'])){
            $payload = ["Error" => "Usuario não encontrado"];

            return $this->jsonResponse($response, $payload, 404);
        }
        
        $id = (int) $args['id'];
        
        $contact = $this->hydrate($dataRequest);
        $contact->setId($id);
        
        $updateInDb = $this->contactRepository->update($contact);
        
        if(!$updateInDb) {
            $payload = ['Error' => 'Erro ao atualizar dados'];
            
            return $this->jsonResponse($response, $payload, 503);
        }
        
        $payload = ['Success' => 'Contato Atualizado com sucesso!'];
        return $this->jsonResponse($response, $payload);
    }


    
    /**
     * Deleta Contatos com base no {id}
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param array $args 
     * @return Response
     */
    public function delete(Request $request, Response $response, array $args): Response
    {
        if (!$this->validateIntAndReturnResponse($args['id'])) {
            $payload = ['Error' => 'Contato não encontrado'];
            
            return $this->jsonResponse($response, $payload, 503);
        }

        $id = (int) $args['id'];

        $deleteOnDB = $this->contactRepository->delete($id);

        if (!$deleteOnDB) {
            $payload =['Error' => 'Erro ao Deletar Contato'];

            return $this->jsonResponse($response, $payload, 404);
        }

        $payload = ['Success' => 'Contato Deletado com sucesso!'];
        return $this->jsonResponse($response, $payload);
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
    
    private function validateIntAndReturnResponse(string $args): bool
    {
        return filter_var($args, FILTER_VALIDATE_INT);
    }
    
    private function hydrate(array $contactsRequest): Contacts
    {
        return new Contacts(
            $contactsRequest['name'],
            $contactsRequest['phone_number'], 
            $contactsRequest['email'], 
            $contactsRequest['address']);
        }
    }
