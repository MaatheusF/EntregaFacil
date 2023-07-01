<?php

/*
Company: CodeBeaver 2023
Description: Controller responsável por verificar se as credenciais de Autenticação enviadas pelo Aplicativo Android
             estão corretas, e retorna os dados básicos do usuário, configurações do Aplicativo, e personalização.
Author: Matheus Francisco Favero
License: Licença MIT
*/

namespace App\Controller;

use Doctrine\DBAL\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\AppLoginService;
use Doctrine\ORM\EntityManagerInterface;

class AppLoginController extends AbstractController
{

    /** @throws Exception */
    #[Route('/api/AppLogin', name: 'APP_AppLogin', methods: 'POST')]
    public function index (AppLoginService $appLoginService, EntityManagerInterface $entityManager, Request $request): JsonResponse
    {
        // Obtem o JSON da requisição e associa em um Array
        $JsonData = json_decode($request->getContent(), true);

        // Verifica se todas as Tags Obrigatorias Existem no Request
        $dataReturn = $this->ValidateJsonTags($JsonData);
        if($dataReturn != null){
            return new JsonResponse($dataReturn, Response::HTTP_BAD_REQUEST);
        }

        // Chama o Service para tratar os dados
        $dataReturn = $appLoginService->LoginServiceMain($JsonData, $entityManager);

        // Retorna a Resposta da Requisição convertendo para JSON
        return new JsonResponse($dataReturn, Response::HTTP_ACCEPTED);
    }

    # Função que verifica se todas as Tags Obrigatorias do Json foram informadas
    # @param $JsonData Array contendo o JSON
    # @return Retorna null se todas as tags existem, ou a mensagem de erro caso alguma não exista
    public function ValidateJsonTags($JsonData): ?array
    {
        if(array_key_exists('Login', $JsonData) && gettype($JsonData['Login']) == 'array'){

            if(!array_key_exists('user_username', $JsonData['Login']) || gettype($JsonData['Login']['user_username']) != 'string'){
                return ['error' => 'Tag \'user_username\' is mandatory but was not informed, or has an invalid value'];
            }
            if(!array_key_exists('user_password', $JsonData['Login']) || gettype($JsonData['Login']['user_password']) != 'string'){
                return ['error' => 'Tag \'user_password\' is mandatory but was not informed, or has an invalid value'];
            } else {
                return null;
            }
        } else {
            return ['error' => 'Array \'Login\' is mandatory and has not been informed, or contains invalid values'];
        }
    }
}


// JSON Padrão:
//  {
//      "Login":{
//          "user_username": "Teste",
//          "user_password": "Teste"
//      }
//  }
