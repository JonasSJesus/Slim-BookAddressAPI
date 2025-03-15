<?php

namespace Agenda\Controllers\Contacts;

use Agenda\Helpers\validatorTrait;
use Agenda\Controllers\BaseController;
use Agenda\Repositories\ContactRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ContactController extends BaseController
{
    use validatorTrait;

    private ContactRepository $contactRepository;

    public function __construct(ContactRepository $contactRepository)
    {
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
        if (!$this->validateIntAndReturnResponse($args['id'])) {
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
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @return Response
     */
    public function create(Request $request, Response $response): Response
    {
        $params = $request->getParsedBody();                   // Capturando arquivo JSON da Stream de input (php://input)
        
        // Validações de dados
        if (!$this->validateDataFromRequest($params['contact'])) {
            $payload = ['Error' => 'Dados invalidos'];

            return $this->jsonResponse($response, $payload, 400);
        }

        $contactOBJ = $this->createObj($params['contact']);

        $saveToDB = $this->contactRepository->create($contactOBJ);

        if (!$saveToDB) {
            $payload = ['Error' => 'Erro ao salvar dados'];

            return $this->jsonResponse($response, $payload, 503);
        }

        $payload = ["Success" => "Contato adicionado com sucesso!"];
        return $this->jsonResponse($response, $payload, 201);
    }



    /**
     * Controlador para atualizar dados do banco
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @return Response
     */
    public function update(Request $request, Response $response, array $args): Response
    {
        $dataRequest = $request->getParsedBody();                   // Capturando arquivo JSON da Stream de input (php://input)

        // Valida os dados da requisição, se não estiverem com o formato aceito, retorna um erro
        if (!$this->validateDataFromRequest($dataRequest['contact'])) {
            $payload = ["Error" => "Dados invalidos"];

            return $this->jsonResponse($response, $payload, 400);
        }

        // Verifica se o valor de {id} inserido na url (e recuperado com $args) é um inteiro
        if (!$this->validateIntAndReturnResponse($args['id'])) {
            $payload = ["Error" => "Usuario não encontrado"];

            return $this->jsonResponse($response, $payload, 404);
        }

        $id = (int) $args['id'];

        $contact = $this->createObj($dataRequest['contact']);
        $contact->setId($id);

        $updateInDb = $this->contactRepository->update($contact);

        if (!$updateInDb) {
            $payload = ['Error' => 'Erro ao atualizar dados'];

            return $this->jsonResponse($response, $payload, 503);
        }

        $payload = ['Success' => 'Contato Atualizado com sucesso!'];
        return $this->jsonResponse($response, $payload);
    }



    /**
     * Deleta Contatos com base no {id}
     * 
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param array $args 
     * @return Response
     */
    public function delete(Request $request, Response $response, array $args): Response
    {
        if (!$this->validateIntAndReturnResponse($args['id'])) {
            $payload = ['Error' => 'Contato inválido'];

            return $this->jsonResponse($response, $payload, 503);
        }

        $id = (int) $args['id'];

        if (!$this->contactRepository->readById($id)) {
            $payload = ['Error' => 'Contato não encontrado!'];

            return $this->jsonResponse($response, $payload, 404);
        }

        $deleteOnDB = $this->contactRepository->delete($id);

        if (!$deleteOnDB) {
            $payload = ['Error' => 'Erro ao Deletar Contato'];

            return $this->jsonResponse($response, $payload, 404);
        }

        $payload = ['Success' => 'Contato Deletado com sucesso!'];
        return $this->jsonResponse($response, $payload);
    }

}
