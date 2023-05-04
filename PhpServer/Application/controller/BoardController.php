<?php

namespace controller;

use interface\BoardRepositoryInterface;
use Repository\BoardRepository;

class BoardController
{
    private BoardRepositoryInterface $repo;

    public function __construct()
    {
        $this->repo = new BoardRepository();
    }

    public function getAllBoards(): array
    {
        return $this->repo->getAllBoard();
    }
}