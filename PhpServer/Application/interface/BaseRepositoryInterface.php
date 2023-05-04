<?php


interface BaseRepositoryInterface
{
    public function getAll($table);

    public function getById($table, $id);

    public function create($table, $data);

    public function update($table, $id, $data);

    public function delete($table, $data);
}