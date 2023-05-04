<?php

namespace interface;

use models\Board;

interface BoardRepositoryInterface
{
    public function getAllBoard();
    public function createBoard(Board $board);
    public function updateBoard(Board $board);
}