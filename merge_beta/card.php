<?php
include "esp-database.php";
include "updateForm.php";
function stateButton($element): string
{
    return $element == 1 ? "Turn off" : "Turn on";
}

function changeButtonColor($element): string
{
    return $element == 1 ? "btn-success" : "btn-warning";
}

function output(): ?string
{
    $result = Application::getAllOutputs();
    $card = null;
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $card .= '
                <div class="col">
                    <div class="p-2">
                        <div class="card">
                           <div class="card-header text-end">
                                <div class="btn-group dropup">
                                    <button class="btn btn-sm"  type="button" data-bs-toggle="dropdown" data-bs-auto-close="true" data-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" id="' . $row["id"] . '" data-bs-toggle="modal" data-bs-target="#updateForm">Edit</a></li>
                                        <li><a class="dropdown-item" id="' . $row["id"] . '" onclick="deleteOutput(' . $row["id"] . ')">Delete</a></li>
                                    </ul>
                                </div> 
                           </div>
                           <div class="card-body">
                                <h5 class="card-title text-truncate" style="max-width: 550px;">Name : ' . $row["name"] . '</h5>
                                <h6 class=""> Board : ' . $row["board"] . ' </h6>
                                <h6 class="">LED : ' . $row["gpio"] . '</h6>
                                <div  class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <button type="button" onclick="updateState(' . $row["state"] . ', ' . $row["id"] . ')" class="btn ' . changeButtonColor($row["state"]) . '">' . stateButton($row["state"]) . '</button> 
                                </div>
                            </div>
                         </div>
                    </div>
                </div>
            ';
        }
    }
    return $card;
}

?>

<script>
    function updateState(element, id) {
        let xhr = new XMLHttpRequest();
        if (element === 0) {
            xhr.open("GET", "esp-outputs-action.php?action=output_update&id=" + id + "&state=1", true);
        } else {
            xhr.open("GET", "esp-outputs-action.php?action=output_update&id=" + id + "&state=0", true);
        }
        xhr.send();
        setTimeout(function () {
            window.location.reload();
        });
    }

    const deleteOutput = (id) => {
        const xhr = new XMLHttpRequest();
        xhr.open("GET", "esp-outputs-action.php?action=output_delete&id=" + id, true);
        xhr.send();

        const toastElList = [].slice.call(document.querySelectorAll('.toast'))
        const toastList = toastElList.map(function (toastEl) {
            return new bootstrap.Toast(toastEl)
        })
        toastList.forEach(toast => toast.show())
        setTimeout(function () {
            window.location.reload();
        }, 1000);
    }
</script>



