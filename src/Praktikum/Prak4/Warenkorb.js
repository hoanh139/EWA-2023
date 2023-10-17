var cartData = [];

function addToCart(pizzaID, pizzaName, pizzaPrice) {
    "use strict";
    var cartItem = document.createElement('div');
    cartItem.classList.add('cart-item');

    var itemContent = document.createElement('span');
    itemContent.textContent = pizzaName;
    itemContent.className = "name";

    var priceContent = document.createElement('span');
    priceContent.className = 'price';
    priceContent.setAttribute('data-price', pizzaPrice);
    priceContent.textContent = pizzaPrice;

    var deleteButton = document.createElement('button');
    deleteButton.textContent = 'Löschen';
    deleteButton.addEventListener('click', function () {
        deleteCartItem(cartItem);
        updateCartPrice();
        checkOrderButton();
    });

    cartItem.appendChild(itemContent);
    //cartItem.appendChild(document.createTextNode(' '));
    cartItem.appendChild(priceContent);
    //cartItem.appendChild(document.createTextNode(' '));
    cartItem.appendChild(deleteButton);

    var cartContainer = document.getElementById('cart-container');
    cartContainer.appendChild(cartItem);
}

function updateCartPrice() {
    "use strict";
    var cartItems = document.getElementsByClassName('cart-item');
    var totalPrice = 0;

    for (var i = 0; i < cartItems.length; i++) {
        var item = cartItems[i];
        var price = parseFloat(item.querySelector('.price').getAttribute('data-price'));
        totalPrice += price;
    }

    var totalPriceElement = document.getElementById('total-price');
    totalPriceElement.textContent = 'Total: ' + totalPrice.toFixed(2);
}

function deleteCartItem(cartItem) {
    "use strict";
    cartItem.remove();
    checkOrderButton();
}

function deleteAllCartItems() {
    "use strict";
    var cartItems = document.getElementsByClassName('cart-item');

    while (cartItems.length > 0) {
        cartItems[0].remove();
    }
    updateCartPrice();
}

function checkOrderButton() {
    "use strict";
    var deliveryAddress = document.getElementById('address').value;
    var cartItems = document.getElementsByClassName('cart-item');
    var orderButton = document.getElementById('order-button');

    if (deliveryAddress !== '' && cartItems.length > 0) {
        orderButton.disabled = false;
    } else {
        orderButton.disabled = true;
    }
}

document.addEventListener('DOMContentLoaded', function () {
    var pizzaImages = document.querySelectorAll('.pizza-image');
    pizzaImages.forEach(function (pizzaImage) {
        pizzaImage.addEventListener('click', function () {
            var pizzaID = pizzaImage.dataset.article_id;
            var pizzaName = pizzaImage.nextElementSibling.getAttribute('data-name');
            var pizzaPrice = parseFloat(pizzaImage.nextElementSibling.getAttribute('data-price'));
            addToCart(pizzaID, pizzaName, pizzaPrice);
            updateCartPrice();
            checkOrderButton();
        });
    });
});

function sendData() {
    var itemContents = document.getElementById("cart-container").querySelectorAll(".cart-item");
    for (var i = 0; i < itemContents.length; i++) {
        cartData.push(itemContents[i].querySelector(".name").textContent);
    }

    var hiddenInput = document.createElement('input');
    hiddenInput.setAttribute('type', 'hidden');
    hiddenInput.setAttribute('name', 'cartData');
    hiddenInput.setAttribute('value', JSON.stringify(cartData));

    var form = document.getElementById('BestellungInfos');
    form.appendChild(hiddenInput);
    form.submit();
}
