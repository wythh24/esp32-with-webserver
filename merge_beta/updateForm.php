<div class="modal fade " id="updateForm" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="updateModalLabel">Update information</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form onsubmit="return updateValue();">
                    <div class="mb-3">
                        <label for="Id" class="form-label">Id</label>
                        <input type="number" class="form-control" id="Id">
                    </div>
                    <div class="mb-3">
                        <label for="outputName" class="form-label">Name</label>
                        <input type="text" class="form-control" id="outputName">
                    </div>
                    <div class="mb-3">
                        <label for="outputBoard" class="form-label">Board</label>
                        <input type="number" name="board" min="0" class="form-control" id="outputBoard">
                    </div>
                    <div class="mb-3">
                        <label for="outputGpio" class="form-label">Gpio</label>
                        <input type="number" name="gpio" min="0" class="form-control" id="outputGpio">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" onclick="updateValue()" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>

<script>
    const updateValue = () => {
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "esp-outputs-action.php", true);

        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.onreadystatechange = function () {
            if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
                setTimeout(function () {
                    window.location.reload();
                });
            }
        }
        let Id = document.getElementById("Id").value;
        let outputName = document.getElementById("outputName").value;
        let outputBoard = document.getElementById("outputBoard").value;
        let outputGpio = document.getElementById("outputGpio").value;

        let httpRequestData = "action=update_value&name=" + outputName + "&board=" + outputBoard + "&gpio=" + outputGpio + "&id=" + Id;
        console.log(httpRequestData)
        xhr.send(httpRequestData);

    }
</script>
