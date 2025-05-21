document.addEventListener('DOMContentLoaded', function () {
    document.getElementById("addName").addEventListener("click", function() {
        let container = document.getElementById("nameContainer");
        let inputGroup = document.createElement("div");
        inputGroup.classList.add("input-group", "mb-2");

        let input = document.createElement("input");
        input.type = "text";
        input.name = "name[]";
        input.classList.add("form-control");
        input.placeholder = "Enter name";

        let buttonGroup = document.createElement("div");
        buttonGroup.classList.add("input-group-append");

        let removeButton = document.createElement("button");
        removeButton.type = "button";
        removeButton.classList.add("btn", "btn-danger");
        removeButton.innerHTML = '<i class="mdi mdi-minus"></i>';
        removeButton.addEventListener("click", function() {
            container.removeChild(inputGroup);
        });

        buttonGroup.appendChild(removeButton);
        inputGroup.appendChild(input);
        inputGroup.appendChild(buttonGroup);
        container.appendChild(inputGroup);
    });
});