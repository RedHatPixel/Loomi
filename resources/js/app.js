import "./quantityInput.js";
import "./cart.js";

// Alert dismissal
const alertEl = document.querySelector(".custom-alert");
if (alertEl) {
    setTimeout(() => {
        const alert = bootstrap.Alert.getOrCreateInstance(alertEl);
        alert.close();
    }, 10000);
}

// Change primary image function
function changePrimaryImage(change) {
    const mainImage = document.getElementById("mainImage");
    mainImage.src = change;
}

document.querySelectorAll(".product-image").forEach((img) => {
    img.addEventListener("click", function () {
        changePrimaryImage(this.src);
    });
});
