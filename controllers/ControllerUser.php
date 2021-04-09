<?php

require_once 'utilities/headers.php';
require_once 'utilities/ResponseCode.php';
require_once 'utilities/ResponseWrapper.php';
require_once 'utilities/JWTWrapper.php';
require_once 'utilities/ExceptionResponse.php';
require_once 'utilities/Query.php';
require_once 'models/daos/UserDAO.php';
require_once 'models/dtos/UserDTO.php';
require_once 'validators/UserValidator.php';
require_once 'converters/UserConverter.php';
require_once 'exceptions/BadRequestException.php';

class ControllerUser
{

    const HASH = PASSWORD_DEFAULT;
    const COST = ['cost' => 14];

    public static function create()
    {
        try {
            $requestBody = json_decode(file_get_contents("php://input"));
            $dto = UserConverter::fromRequestBody($requestBody);
            UserValidator::create($dto);
            $passwordHash = self::getPasswordHash($dto->getPassword());
            $dto->setPassword($passwordHash);
            $dao = new UserDAO();
            $dao->create($dto);
            echo WrapperResponse::createResponse(ResponseCode::OK, 'El usuario fue creado', true);
        } catch (BadRequestException $e) {
            echo WrapperResponse::createResponse(ResponseCode::BAD_REQUEST, $e->getMessage(), false);
        } catch (PDOException $e) {
            $message = ExceptionResponse::getMessage($e->getMessage());
            echo WrapperResponse::createResponse(ResponseCode::INTERNAL_SERVER_ERROR, $message, false);
        }
    }

    public static function read()
    {
        try {
            $dao = new UserDAO();
            $responseBody = $dao->read();
            echo WrapperResponse::createResponse(ResponseCode::OK, 'Success', true, $responseBody);
        } catch (NotFoundException $e) {
            echo WrapperResponse::createResponse(ResponseCode::NOT_FOUND, $e->getMessage(), false, null);
        } catch (PDOException $e) {
            $message = ExceptionResponse::getMessage($e->getMessage());
            echo WrapperResponse::createResponse(ResponseCode::INTERNAL_SERVER_ERROR, $message, false);
        }
    }

    public static function readById($id)
    {
        try {
            $dao = new UserDAO();
            $responseBody = $dao->readById($id);
             echo WrapperResponse::createResponse(ResponseCode::OK, 'Success', true, $responseBody[0]);
        } catch (NotFoundException $e) {
            echo WrapperResponse::createResponse(ResponseCode::NOT_FOUND, $e->getMessage(), false, null);
        } catch (PDOException $e) {
            $message = ExceptionResponse::getMessage($e->getMessage());
            echo WrapperResponse::createResponse(ResponseCode::INTERNAL_SERVER_ERROR, $message, false);
        }
    }

    public static function update()
    {
        try {
            $requestBody = json_decode(file_get_contents("php://input"));
            $dto = UserConverter::fromRequestBody($requestBody);
            UserValidator::update($dto);
            $dao = new UserDAO();
            $dao->update($dto);
            echo WrapperResponse::createResponse(ResponseCode::OK, 'El usuario fue actualizado', true);
        } catch (NotFoundException $e) {
            echo WrapperResponse::createResponse(ResponseCode::NOT_FOUND, $e->getMessage(), false);
        } catch (BadRequestException $e) {
            echo WrapperResponse::createResponse(ResponseCode::BAD_REQUEST, $e->getMessage(), false);
        } catch (PDOException $e) {
            $message = ExceptionResponse::getMessage($e->getMessage());
            echo WrapperResponse::createResponse(ResponseCode::INTERNAL_SERVER_ERROR, $message, false);
        }
    }

    public static function delete($id)
    {
        try {
            $dao = new UserDAO();
            $dao->delete($id);
            echo WrapperResponse::createResponse(ResponseCode::OK, 'El usuario fue eliminado', true);
        } catch (NotFoundException $e) {
            echo WrapperResponse::createResponse(ResponseCode::NOT_FOUND, $e->getMessage(), false);
        } catch (PDOException $e) {
            $message = ExceptionResponse::getMessage($e->getMessage());
            echo WrapperResponse::createResponse(ResponseCode::INTERNAL_SERVER_ERROR, $message, false);
        }
    }

    public static function login()
    {
        try {
            $requestBody = json_decode(file_get_contents("php://input"));
            $dto = UserConverter::fromRequestBody($requestBody);
            UserValidator::login($dto);
            $dao = new UserDAO();
            $responseBody = $dao->readByName($dto->getUserName());
            //verifica si la contraseña es igual a la almacenada
            $passwordHash = isset($responseBody[0]['password']) ? $responseBody[0]['password'] : '';
            $validatedPassword = self::validatePassword($dto->getPassword(), $passwordHash);
            if($validatedPassword) {//existe usuario
                if(self::passwordNeedsRehash($passwordHash)) {
                    $passwordHash = self::getPasswordHash($dto->getPassword());
                    $dto->setPassword($passwordHash);
                    $dao->update($dto);
                }
                unset($responseBody[0]['password']);//elimino campo de password
                $expiration = 60 * 60 * 4; //jwt valido por 4 horas
                $responseBody[0]['jwt'] = JWTWrapper::encode();//token sin tiempo de expiracion
                $responseBody[0]['expirableJWT'] = JWTWrapper::encode($expiration);
                echo WrapperResponse::createResponse(ResponseCode::OK, 'Success', true, $responseBody[0]);
            }
        } catch (NotFoundException $e) {
            echo WrapperResponse::createResponse(ResponseCode::NOT_FOUND, $e->getMessage(), false);
        } catch (BadRequestException $e) {
            echo WrapperResponse::createResponse(ResponseCode::BAD_REQUEST, $e->getMessage(), false);
        } catch (PDOException $e) {
            $message = ExceptionResponse::getMessage($e->getMessage());
            echo WrapperResponse::createResponse(ResponseCode::INTERNAL_SERVER_ERROR, $message, false);
        }
    }

    private static function validatePassword($password, $passwordHash) {
        return password_verify($password, $passwordHash);
    }

    private static function getPasswordHash($password) {
        return password_hash($password, self::HASH, self::COST);
    }

    private static function passwordNeedsRehash($passwordHash) {
        return password_needs_rehash($passwordHash, self::HASH, self::COST);
    }

}
