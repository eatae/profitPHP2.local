<?php
namespace App;

use App\Exceptions\DbException;

class Db
{
    protected $db_hdr;

    public function __construct()
    {
        $this->db_hdr = new \PDO('mysql:host=127.0.0.1; dbname=profitphp2', 'root', '');
    }

    /**
     * @return array
     */
    public function query($sql, $params = [], $class='')
    {
        $st_hdr = $this->db_hdr->prepare($sql);
        if (!$st_hdr->execute($params)) {
            throw new DbException('Ошибка запроса');
        }
        if (empty($class)) {
            return $st_hdr->fetchAll();
        }
        return $st_hdr->fetchAll(\PDO::FETCH_CLASS, $class);

    }


    public function queryEach($sql, $params=[], $class='')
    {
        $st_hdr = $this->db_hdr->prepare($sql);
        //задаём объект для выборки
        $st_hdr->setFetchMode(\PDO::FETCH_CLASS, $class);
        if (!$st_hdr->execute($params)) {
            throw new DbException('Ошибка запроса');
        }
        while ($data = $st_hdr->fetch()) {
            yield $data;
        }
    }


    public function execute($sql, $params=[])
    {
        $st_hdr = $this->db_hdr->prepare($sql);
        if(!$st_hdr->execute($params))
            throw new DbException('Ошибка запроса');
    }

    /**
     * @return string
     */
    public function insertId()
    {
        $id = $this->db_hdr->lastInsertId();
        if(!$id) {
            throw new DbException('Ошибка запроса id');
        }
        return $id;
    }



    public function fill(array $data)
    {

    }
}
