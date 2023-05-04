<?php

namespace models;

class Board
{
    private int $id;
    private int $board;
    private Date $lastRequest;

    public function __construct($id, $board, $lastRequest)
    {
        $this->id = $id;
        $this->board = $board;
        $this->lastRequest = $lastRequest;
    }
}