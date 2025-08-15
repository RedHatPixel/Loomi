function limitQuantityAll(quantityInput) {
    const min = parseInt(quantityInput.min) || 1;
    const max = parseInt(quantityInput.max) || Infinity;
    let current = parseInt(quantityInput.value) || min;
    if (current < min) current = min;
    if (current > max) current = max;
    quantityInput.value = current;
}

document.querySelectorAll(".quantityInput").forEach(function (quantityInput) {
    quantityInput.addEventListener("input", function () {
        limitQuantityAll(quantityInput);
    });
});

document.querySelectorAll(".quantityInput").forEach(function (quantityInput) {
    // Find related buttons in the same parent
    const parent = quantityInput.parentElement;
    const leftBtn = parent.querySelector(".buttonLeft");
    const rightBtn = parent.querySelector(".buttonRight");

    // Find the hidden input by a data attribute or another selector
    const hiddenInputSelector = quantityInput.getAttribute(
        "data-hidden-selector"
    );

    // HiddenInput
    const hiddenInputs = hiddenInputSelector
        ? document.querySelectorAll(hiddenInputSelector)
        : null;

    function changeHiddenInputsValue(change) {
        if (!hiddenInputs) return;

        hiddenInputs.forEach(function (hiddenInput) {
            hiddenInput.value = change;
        });
    }

    changeHiddenInputsValue(quantityInput.value);

    quantityInput.addEventListener("input", function () {
        changeHiddenInputsValue(quantityInput.value);
    });

    quantityInput.addEventListener("change", function () {
        changeHiddenInputsValue(quantityInput.value);
    });

    if (leftBtn) {
        leftBtn.addEventListener("click", function () {
            let min = parseInt(quantityInput.min) || 1;
            let value = parseInt(quantityInput.value) || min;
            value = Math.max(min, value - 1);
            quantityInput.value = value;
            changeHiddenInputsValue(quantityInput.value);
        });
    }

    if (rightBtn) {
        rightBtn.addEventListener("click", function () {
            let min = parseInt(quantityInput.min) || 1;
            let max = parseInt(quantityInput.max) || Infinity;
            let value = parseInt(quantityInput.value) || min;
            value = Math.min(max, value + 1);
            quantityInput.value = value;
            changeHiddenInputsValue(quantityInput.value);
        });
    }
});
