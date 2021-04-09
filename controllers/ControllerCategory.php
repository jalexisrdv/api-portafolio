<?php

require_once 'utilities/headers.php';
require_once 'utilities/ResponseCode.php';
require_once 'utilities/ResponseWrapper.php';
require_once 'utilities/ExceptionResponse.php';
require_once 'utilities/Query.php';
require_once 'models/daos/CategoryDAO.php';
require_once 'models/dtos/CategoryDTO.php';
require_once 'validators/CategoryValidator.php';
require_once 'converters/CategoryConverter.php';
require_once 'exceptions/BadRequestException.php';
require_once 'exceptions/NotFoundException.php';

class ControllerCategory
{

    public static function create()
    {
        try {
            $requestBody = json_decode(file_get_contents("php://input"));
            $dto = CategoryConverter::fromRequestBody($requestBody);
            CategoryValidator::create($dto);
            $dao = new CategoryDAO();
            $dao->create($dto);
            echo WrapperResponse::createResponse(ResponseCode::OK, 'La categoria fue creada', true);
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
            $dao = new CategoryDAO();
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
            $dao = new CategoryDAO();
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
            $dto = CategoryConverter::fromRequestBody($requestBody);
            CategoryValidator::update($dto);
            $dao = new CategoryDAO();
            $dao->update($dto);
            echo WrapperResponse::createResponse(ResponseCode::OK, 'La categoria fue actualizada', true);
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
            $dao = new CategoryDAO();
            $dao->delete($id);
            echo WrapperResponse::createResponse(ResponseCode::OK, 'La categoria fue eliminada', true);
        } catch (NotFoundException $e) {
            echo WrapperResponse::createResponse(ResponseCode::NOT_FOUND, $e->getMessage(), false);
        } catch (PDOException $e) {
            $message = ExceptionResponse::getMessage($e->getMessage());
            echo WrapperResponse::createResponse(ResponseCode::INTERNAL_SERVER_ERROR, $message, false);
        }
    }
}
