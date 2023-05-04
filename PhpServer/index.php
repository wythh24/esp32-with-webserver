<!--
    This project rewrite by https://github.com/wythh24
    The purpose is clean code and configuration any missing parts

    Reference
    https://github.com/RuiSantosdotme
    https://randomnerdtutorials.com/control-esp32-esp8266-gpios-from-anywhere/

-->

<?php
include_once('esp-database.php');

$result = Application::getAllOutputs();
$html_buttons = null;
if ($result) {
    while ($row = $result->fetch_assoc()) {
        if ($row["state"] == "1") {
            $button_checked = "checked";
        } else {
            $button_checked = "";
        }
        $html_buttons .= '<h3>' . $row["name"] . ' - Board ' . $row["board"] . ' - GPIO ' . $row["gpio"] . ' (<i><a onclick="deleteOutput(this)" href="javascript:void(0);" id="' . $row["id"] . '">Delete</a></i>)</h3><label class="switch"><input type="checkbox" onchange="updateOutput(this)" id="' . $row["id"] . '" ' . $button_checked . '><span class="slider"></span></label>';
    }
}

$result2 = Application::getAllBoards();
$html_boards = null;
if ($result2) {
    $html_boards .= '<h3>Boards</h3>';
    while ($row = $result2->fetch_assoc()) {
        $row_reading_time = $row["last_request"];
        // Uncomment to set timezone to - 1 hour (you can change 1 to any number)
        $row_reading_time = date("Y-m-d H:i:s", strtotime("$row_reading_time - 1 hours"));

        // Uncomment to set timezone to + 4 hours (you can change 4 to any number)
        $row_reading_time = date("Y-m-d H:i:s", strtotime("$row_reading_time + 7 hours"));
        $html_boards .= '<p><strong>Board ' . $row["board"] . '</strong> - Last Request Time: ' . $row_reading_time . '</p>';
    }
}
?>

<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="esp-style.css">
    <title>ESP Output Control</title>
</head>
<body>
<h2>ESP Output Control</h2>
<?php echo $html_buttons; ?>
<br><br>
<?php echo $html_boards; ?>
<br><br>
<div>
    <form onsubmit="return createOutput();">
        <h3>Create New Output</h3>
        <label for="outputName">Name</label>
        <input type="text" name="name" id="outputName"><br>
        <label for="outputBoard">Board ID</label>
        <input type="number" name="board" min="0" id="outputBoard">
        <label for="outputGpio">GPIO Number</label>
        <input type="number" name="gpio" min="0" id="outputGpio">
        <label for="outputState">Initial GPIO State</label>
        <select id="outputState" name="state">
            <option value="0">0 = OFF</option>
            <option value="1">1 = ON</option>
        </select>
        <input type="submit" value="Create Output">
        <p><strong>Note:</strong> in some devices, you might need to refresh the page to see your newly created buttons
            or to remove deleted buttons.</p>
    </form>
</div>

<script>
    function updateOutput(element) {
        let xhr = new XMLHttpRequest();
        if (element.checked) {
            xhr.open("GET", "esp-outputs-action.php?action=output_update&id=" + element.id + "&state=1", true);
        } else {
            xhr.open("GET", "esp-outputs-action.php?action=output_update&id=" + element.id + "&state=0", true);
        }
        xhr.send();
    }

    function deleteOutput(element) {
        const result = confirm("Want to delete this output?");
        if (result) {
            const xhr = new XMLHttpRequest();
            xhr.open("GET", "esp-outputs-action.php?action=output_delete&id=" + element.id, true);
            xhr.send();
            alert("Output deleted");
            setTimeout(function () {
                window.location.reload();
            });
        }
    }

    const createOutput = () => {
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "esp-outputs-action.php", true);

        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.onreadystatechange = function () {
            if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
                alert("Output created");
                setTimeout(function () {
                    window.location.reload();
                });
            }
        }
        let outputName = document.getElementById("outputName").value;
        let outputBoard = document.getElementById("outputBoard").value;
        let outputGpio = document.getElementById("outputGpio").value;
        let outputState = document.getElementById("outputState").value;
        let httpRequestData = "action=output_create&name=" + outputName + "&board=" + outputBoard + "&gpio=" + outputGpio + "&state=" + outputState;
        xhr.send(httpRequestData);
    }
</script>
</body>
</html>
