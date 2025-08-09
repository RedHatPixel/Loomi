import "./wishlist.js";

const alertEl = document.querySelector(".alert-dismissible");
if (alertEl) {
    setTimeout(() => {
        const alert = bootstrap.Alert.getOrCreateInstance(alertEl);
        alert.close();
    }, 10000);
}

function changePrimaryImage(change) {
    const mainImage = document.getElementById("mainImage");
    mainImage.src = change;
}

window.changePrimaryImage = changePrimaryImage;

function changeQuantity(change) {
    const min = parseInt(quantityInput.min) || 1;
    const max = parseInt(quantityInput.max) || Infinity;

    let current = parseInt(quantityInput.value) || min;
    current += change;

    if (current < min) current = min;
    if (current > max) current = max;

    quantityInput.value = current;
    quantityHiddenInput.value = current;
}

function limitQuantity() {
    const min = parseInt(quantityInput.min) || 1;
    const max = parseInt(quantityInput.max) || Infinity;
    let current = parseInt(quantityInput.value) || min;

    if (current < min) current = min;
    if (current > max) current = max;

    quantityInput.value = current;
    quantityHiddenInput.value = current;
}

window.changeQuantity = changeQuantity;
window.limitQuantity = limitQuantity;

const quantityInput = document.getElementById("quantityInput");
const quantityHiddenInput = document.getElementById("quantityHiddenInput");

if (quantityInput) {
    quantityInput.addEventListener("input", () => {
        quantityHiddenInput.value = quantityInput.value;
    });
}

if (quantityHiddenInput) {
    window.addEventListener("DOMContentLoaded", () => {
        quantityHiddenInput.value = quantityInput.value;
    });
}
