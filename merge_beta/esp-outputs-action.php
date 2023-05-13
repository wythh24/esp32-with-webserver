<?php
include_once('esp-database.php');

$action = $id = $name = $gpio = $state = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = test_input($_POST["action"]);
    if ($action == "output_create") {
        $name = test_input($_POST["name"]);
        $board = test_input($_POST["board"]);
        $gpio = test_input($_POST["gpio"]);
        $state = test_input($_POST["state"]);
        $result = Application::createOutput($name, $board, $gpio, $state);

        $result2 = Application::getBoard($board);
        if (!$result2->fetch_assoc()) {
            Application:: createBoard($board);
        }
        echo $result;
    } else if ($action == "update_value") {
        $name = test_input($_POST["name"]);
        $board = test_input($_POST["board"]);
        $gpio = test_input($_POST["gpio"]);
        $id = test_input($_POST["id"]);
        $data = [
            'name' => $name,
            'board' => $board,
            'gpio' => $gpio,
        ];

        Application::updateValue("outputs", $data, $id);

    }
    echo "No data request with post";
}

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $action = test_input($_GET["action"]);

    if ($action == "outputs_state") {
        $board = test_input($_GET["board"]);
        $result = Application::getAllOutputStates($board);

        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $rows[$row["gpio"]] = $row["state"];
            }
        }
        echo json_encode($rows);
        $result = Application::getBoard($board);
        if ($result->fetch_assoc()) {
            Application::updateLastBoardTime($board);
        }

    } else if ($action == "output_update") {
        $id = test_input($_GET["id"]);
        $state = test_input($_GET["state"]);
        $result = Application::updateOutput($id, $state);
        echo $result;
    } else if ($action == "output_delete") {
        $id = test_input($_GET["id"]);
        $board = Application::getOutputBoardById($id);
        if ($row = $board->fetch_assoc()) {
            $board_id = $row["board"];
        }
        $result = Application::deleteOutput($id);
        $result2 = Application::getAllOutputStates($board_id);
        if (!$result2->fetch_assoc()) {
            Application::deleteBoard($board_id);
        }
        echo $result;
    } else {
        echo "Invalid HTTP request.";
    }
}

function test_input($data): string
{
    $data = trim($data);
    $data = stripslashes($data);
    return htmlspecialchars($data);
}

