<?php

namespace Repository;

use Database;
use \BaseRepositoryInterface;

class BaseBaseRepository implements BaseRepositoryInterface
{
    private Database $database;

    public function __construct()
    {
        $this->database = new Database();
    }


    public function getAll($table): array
    {
        $sql = "SELECT * FROM $table";
        return $this->database->connect->query($sql)->fetch_all();
    }

    public function getById($table, $id): array
    {
        $sql = "SELECT * FROM {$table} WHERE id = ?";
        return $this->database->connect->query($sql)->fetch_assoc();
    }

    public function create($table, $data): bool
    {
        $key = implode(', ', array_keys($data));
        $values = ':' . implode(', :', array_keys($data));

        $sql = "INSERT INTO {$table} ({$key}) VALUES ({$values})";

        $stmt = $this->database->connect->prepare($sql);
        foreach ($data as $key => $values) {
            $stmt->bind_param(':', $key, $values);
        }
        return $stmt->execute();
    }

    public function update($table, $id, $data): bool
    {
        $set = '';

        foreach ($data as $key => $value) {
            $set .= "{$key}=:{$key}, ";
        }

        $set = rtrim($set, ', ');

        $sql = "UPDATE {$table} SET {$set} WHERE id=:id";
        $stmt = $this->database->connect->prepare($sql);

        foreach ($data as $key => $value) {
            $stmt->bind_param(':' . $key, $value);
        }

        $stmt->bind_param(':id', $id);

        return $stmt->execute();
    }

    public function delete($table, $data): bool
    {
        $sql = "DELETE FROM {$table} WHERE id=:id";
        $stmt = $this->database->connect->prepare($sql);
        $stmt->bind_param(':id', $data);
        return $stmt->execute();
    }
}