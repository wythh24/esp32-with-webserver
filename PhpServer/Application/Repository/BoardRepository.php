<?php

namespace Repository;


use interface\BaseRepositoryInterface;
use interface\BoardRepositoryInterface;
use models\Board;

class BoardRepository implements BoardRepositoryInterface
{
    private string $tableName = "board";
    private BaseRepositoryInterface $repository;

    public function __construct()
    {
        $this->repository = new BaseBaseRepository();
    }

    public function getAllBoard(): array
    {
        return $this->repository->getAll($this->tableName);
    }

    public function createBoard(Board $board): bool
    {
        return $this->repository->create($this->tableName, $board);
    }

    public function updateBoard(Board $board)
    {
        // TODO: Implement updateBoard() method.
    }
}