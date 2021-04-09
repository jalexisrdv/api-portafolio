<?php

class CategoryDAO
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
    public function create(CategoryDTO $dto)
    {
        $sql = 'INSERT INTO categories (category_name) VALUES (:category_name)';
        $statement = $this->connection->prepare($sql);
        $statement->execute(array(
            ':category_name' => $dto->getName(),
        ));
        return $statement->rowCount() >= 1;
    }

    /**
     * @throws NotFoundException PDOException
     */
    public function read()
    {
        $sql = 'SELECT category_id AS categoryId, category_name AS categoryName FROM categories';
        $statement = $this->connection->prepare($sql);
        $statement->execute();
        $result = $statement->fetchAll();
        if(sizeof($result) === 0) {
            throw new NotFoundException('No se encontraron categorias');
        }
        return $result;
    }

    /**
     * @throws NotFoundException PDOException
     */
    public function readById($id)
    {
        $sql = 'SELECT category_id AS categoryId, category_name AS categoryName FROM categories WHERE category_id = :category_id';
        $statement = $this->connection->prepare($sql);
        $statement->execute(array(
            ':category_id' => $id
        ));
        $result = $statement->fetchAll();
        if(sizeof($result) === 0) {
            throw new NotFoundException('No existe categoria con id => ' . $id);
        }
        return $result;
    }

    /**
     * @throws NotFoundException PDOException
     */
    public function update(CategoryDTO $dto)
    {
        $this->readById($dto->getId());//verifica si existe categoria a actualizar
        $args = array(
            ':category_id' => $dto->getId(),
            ':category_name' => $dto->getName()
        );
        $query = Query::createUpdateFromArray('categories', $args, 'category_id');
        $statement = $this->connection->prepare($query->getSql());
        $statement->execute($query->getArgs());
        return true;
    }

    public function delete($id)
    {
        $sql = 'DELETE FROM categories WHERE category_id = :category_id';
        $statement = $this->connection->prepare($sql);
        $statement->execute(array(
            ':category_id' => $id
        ));
        if($statement->rowCount() < 1) {
            throw new NotFoundException('No existe categoria con id => ' . $id);
        }
        return $statement->rowCount() >= 1;
    }
}
