<?php

include("Database.php");

class Application extends Database
{

    public static function createOutput($name, $board, $gpio, $state): string
    {

        $sql = "INSERT INTO Outputs (name, board, gpio, state)VALUES ('" . $name . "', '" . $board . "', '" . $gpio . "', '" . $state . "')";

        if (parent::connectDatabase()->query($sql)) {

            self::closeConnection();
            return "New output created successfully with named : $name";
        }

        self::closeConnection();
        return "Error: " . $sql . "<br>" . parent::connectDatabase()->error;
    }


    public static function getAllOutputs()
    {

        $sql = "SELECT id, name, board, gpio, state FROM Outputs ORDER BY board";

        if (!($result = parent::connectDatabase()->query($sql))) {
            return false;
        }
        parent::connectDatabase()->close();
        return $result;

    }

    public static function getAllBoards()
    {
        $sql = "SELECT board, last_request FROM Boards ORDER BY board";

        if ($result = parent::connectDatabase()->query($sql)) {
            self::closeConnection();
            return $result;
        }
        self::closeConnection();
        return false;
    }

    public static function getOutputBoardById($id)
    {
        $sql = "SELECT board FROM Outputs WHERE id='" . $id . "'";

        if ($result = parent::connectDatabase()->query($sql)) {
            self::closeConnection();
            return $result;
        }
        self::closeConnection();
        return false;
    }

    public static function deleteOutput($id): string
    {

        $sql = "DELETE FROM Outputs WHERE id='" . $id . "'";

        if (parent::connectDatabase()->query($sql) !== TRUE) {
            self::closeConnection();
            return "Error: " . $sql . "<br>" . parent::connectDatabase()->error;
        }

        self::closeConnection();
        return "Output deleted successfully";
    }

    public static function getAllOutputStates($board)
    {

        $sql = "SELECT gpio, state FROM Outputs WHERE board='" . $board . "'";

        if ($result = parent::connectDatabase()->query($sql)) {
            self::closeConnection();
            return $result;
        }
        self::closeConnection();
        return false;
    }

    public static function getBoard($board)
    {

        $sql = "SELECT board, last_request FROM Boards WHERE board='" . $board . "'";
        if ($result = parent::connectDatabase()->query($sql)) {
            self::closeConnection();
            return $result;
        }
        self::closeConnection();
        return false;
    }

    public static function createBoard($board): string
    {

        $sql = "INSERT INTO Boards (board) VALUES ('" . $board . "')";

        if (parent::connectDatabase()->query($sql) === TRUE) {
            self::closeConnection();
            return "New board created successfully";
        }
        self::closeConnection();
        return "Error: " . $sql . "<br>" . parent::connectDatabase()->error;
    }


    public static function updateOutput($id, $state): string
    {

        $sql = "UPDATE Outputs SET state='" . $state . "' WHERE id='" . $id . "'";

        if (parent::connectDatabase()->query($sql) === TRUE) {
            self::closeConnection();
            return "Output state updated successfully";
        }
        self::closeConnection();
        return "Error: " . $sql . "<br>" . parent::connectDatabase()->error;
    }

    public static function deleteBoard($board): string
    {

        $sql = "DELETE FROM Boards WHERE board='" . $board . "'";

        if (parent::connectDatabase()->query($sql) === TRUE) {
            self::closeConnection();
            return "Board deleted successfully";
        }
        self::closeConnection();
        return "Error: " . $sql . "<br>" . parent::connectDatabase()->error;
    }

    public static function updateLastBoardTime($board): string
    {

        $sql = "UPDATE Boards SET last_request=now() WHERE board='" . $board . "'";

        if (parent::connectDatabase()->query($sql) === TRUE) {
            self::closeConnection();
            return "Output state updated successfully";
        }
        self::closeConnection();
        return "Error: " . $sql . "<br>" . parent::connectDatabase()->error;
    }

    public static function updateValue($table, ?array $data = [], int $id = null): bool
    {
        $args = [];
        foreach ($data as $key => $value) {
            $args [] = "$key = '$value'";
        }

        $sql = "UPDATE $table SET " . implode(',', $args) . " WHERE id = $id";
        return parent::connectDatabase()->query($sql);
    }

    private static function closeConnection(): void
    {
        parent::connectDatabase()->close();
    }
}


?>
