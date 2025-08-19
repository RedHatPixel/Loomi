document.querySelectorAll(".editButton").forEach(function (button) {
    const parent = button.parentElement;
    const updateFormSelector = button.getAttribute("update-form");
    const quantityFormSelector = button.getAttribute("quantity-form");
    const quantityContentSelector = button.getAttribute("quantity-content");

    const updateForm = updateFormSelector
        ? document.querySelector(updateFormSelector)
        : null;

    const quantityForm = quantityFormSelector
        ? document.querySelector(quantityFormSelector)
        : null;

    const quantityContent = quantityContentSelector
        ? document.querySelector(quantityContentSelector)
        : null;

    button.addEventListener("click", function (event) {
        event.preventDefault();

        // Toggle the visibility of the update form
        if (
            updateForm.style.display === "none" ||
            updateForm.style.display === ""
        ) {
            updateForm.style.display = "inline-block";
            quantityForm.style.display = "flex";
            quantityContent.style.display = "none";
            button.style.display = "none";
        } else {
            updateForm.style.display = "none";
            quantityForm.style.display = "none";
            quantityContent.style.display = "inline-block";
            button.style.display = "inline-block";
        }
    });
});
