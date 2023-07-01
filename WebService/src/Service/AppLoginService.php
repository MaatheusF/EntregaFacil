<?php

/*
Company: CodeBeaver 2023
Description: Service responsável por verificar se as credenciais de Autenticação enviadas pelo Aplicativo Android
             estão corretas, e retorna os dados básicos do usuário, configurações do Aplicativo, e personalização.
Author: Matheus Francisco Favero
License: Licença MIT
*/

namespace App\Service;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;

class AppLoginService
{
    # Função que executa a logica de Login do App
    # @Param: $JsonData : JSON recebido na Requisição
    # @Param: $entityManager : Objeto de Conexão com o Doctrine
    # @Return: Retorna o JSON de Response
    public function LoginServiceMain($JsonData, EntityManagerInterface $entityManager): array
    {
        try {
            // Realiza o Parse das Tags do JSON
            $JDataUsername = $JsonData['Login']['user_username'];
            $JDataPassword = $JsonData['Login']['user_password'];

            // Verifica se o Login é verdadeiro
            if($this->ConsultCredentials($entityManager, $JDataUsername, $JDataPassword)){
                // Busca os dados de retorno do Metodo
                return $this->ConsultData($JDataUsername,$entityManager);
            } else {
                // Retorna o JSON de Excessão
                return $this->BuildJsonError('Incorrect login credentials');
            }
        } catch ( \Exception $ex ){
            return $this->BuildJsonError('An internal error occurred on the Server');
        }
    }

    # Função que verifica se o Login informado é valido
    # @Param $entityManager Objeto de Conexão com o Doctrine
    # @Param $JDataUsername Nome de Usuario
    # @Param $JDataPassword Senha do Usuario
    # @Return: Retorna "True" se o login for valido
    public function ConsultCredentials(EntityManagerInterface $entityManager, $JDataUsername, $JDataPassword): bool
    {
        try {
            $SQL = 'SELECT user_id FROM user_authentication WHERE user_username = :username AND user_password = :password';
            $STMT = $entityManager->getConnection()->prepare($SQL);
            $STMT->bindValue(1, $JDataUsername);
            $STMT->bindValue(2, $JDataPassword);
            $Data = $STMT->executeQuery()->fetchAllAssociative();

            if(count($Data) >= 1){
                return true;
            } else {
                return false;
            }
        } catch (\Exception $ex){
            return false;
        }
    }

    # Função que consulta os dados de retorno do Metodo
    # @Param: $JDataUsername Nome de Usuario
    # @Param: $entityManager Objeto de Conexão com o Doctrine
    # @Return: Retorna os dados basicos do cadastro do Usuario
    public function ConsultData($JDataUsername, EntityManagerInterface $entityManager): array
    {
        try {
            $SQL = 'SELECT user_id,user_username,user_email,user_name FROM user_authentication WHERE user_username = :username';
            $STMT = $entityManager->getConnection()->prepare($SQL);
            $STMT->bindValue(1, $JDataUsername);
            $Data = $STMT->executeQuery()->fetchAllAssociative();
            return $this->BuildJsonSuccess($Data[0]);

        } catch (\Exception $ex ){
            return $this->BuildJsonError('An internal error occurred when requesting user data');
        }
    }

    # Função que constroi o JSON de Sucesso
    # @Param: $Data Dados retornados do Banco de dados
    # @Return: Retorna o JSON montado com os dados informados
    public function BuildJsonSuccess($Data): array {
        return $JsonSuccess = [
            'success' => [
                'user_id' => $Data['user_id'],
                'user_username' => $Data['user_username'],
                'user_email' => $Data['user_email'],
                'user_name' => $Data['user_name']
            ]
        ];
    }

    # Função que constroi o JSON de Erro
    # @Param: $ErrorMessage Mensagem de erro a ser retornada
    # @Return: Retorna o JSON montado com os dados do erro gerado
    public function BuildJsonError($ErrorMessage): array {
        return $JsonError = [
            'error' => [
                'error_message' => $ErrorMessage
            ]
        ];
    }
}