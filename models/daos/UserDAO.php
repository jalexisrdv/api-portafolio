<?php

class UserDAO
{

    private $connection;

    public function __construct()
    {
        require_once 'Connection.php';
        $this->connection = Connection::getConnection();
    }

    /**
     * @throws PDOException
     */
    public function create(UserDTO $dto)
    {
        $sql = 'INSERT INTO users (user_name, user_password) VALUES (:user_name, :user_password)';
        $statement = $this->connection->prepare($sql);
        $statement->execute(array(
            ':user_name' => $dto->getUserName(),
            ':user_password' => $dto->getPassword()
        ));
        return $statement->rowCount() >= 1;
    }

    /**
     * @throws NotFoundException PDOException
     */
    public function read()
    {
        $sql = 'SELECT user_id AS id, user_name AS username FROM users';
        $statement = $this->connection->prepare($sql);
        $statement->execute();
        $result = $statement->fetchAll();
        if(sizeof($result) === 0) {
            throw new NotFoundException('No se encontraron usuarios');
        }
        return $result;
    }

    /**
     * @throws NotFoundException PDOException
     */
    public function readById($id)
    {
        $sql = 'SELECT user_id AS id, user_name AS username FROM users WHERE user_id = :user_id';
        $statement = $this->connection->prepare($sql);
        $statement->execute(array(
            ':user_id' => $id
        ));
        $result = $statement->fetchAll();
        if(sizeof($result) === 0) {
            throw new NotFoundException('No existe usuario con id => ' . $id);
        }
        return $result;
    }

    /**
     * @throws NotFoundException PDOException
     */
    public function readByName($username)
    {
        $sql = 'SELECT user_id AS id, user_name AS username, user_password AS password FROM users WHERE user_name = :user_name';
        $statement = $this->connection->prepare($sql);
        $statement->execute(array(
            ':user_name' => $username
        ));
        $result = $statement->fetchAll();
        if(sizeof($result) === 0) {
            throw new NotFoundException('No existe usuario con username => ' . $username);
        }
        return $result;
    }

    /**
     * @throws NotFoundException PDOException
     */
    public function update(UserDTO $dto)
    {
        $this->readById($dto->getId());//verifica si existe usuario a actualizar
        $args = array(
            ':user_id' => $dto->getId(),
            ':user_name' => $dto->getUserName(),
            ':user_password' => $dto->getPassword()
        );
        $query = Query::createUpdateFromArray('users', $args, 'user_id');
        $statement = $this->connection->prepare($query->getSql());
        $statement->execute($query->getArgs());
        return true;
    }

    /**
     * @throws NotFoundException PDOException
     */
    public function delete($id)
    {
        $sql = 'DELETE FROM users WHERE user_id = :user_id';
        $statement = $this->connection->prepare($sql);
        $statement->execute(array(
            ':user_id' => $id
        ));
        if($statement->rowCount() < 1) {
            throw new NotFoundException('No existe usuario con id => ' . $id);
        }
        return $statement->rowCount() >= 1;
    }
}
