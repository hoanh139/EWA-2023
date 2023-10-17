function createRadioButton(pizzaId, value, status){
    "use strict";
    var radioInput = document.createElement('input');

    radioInput.type = 'radio';
    radioInput.name = pizzaId;
    radioInput.value = value;
    radioInput.checked = status;
    radioInput.disabled = true;

    return radioInput;
}

function createLabel(value) {
    var label = document.createElement("label");
    label.innerText = value;

    return label;
}

function appendRadioInputWithLabel(parentElement, radioInput, label) {
    parentElement.appendChild(radioInput);
    parentElement.appendChild(label);
    parentElement.appendChild(document.createElement('br'));
}

function insertCheckStatus(status){
    "use strict";
    var statusMap = {0: false, 1: false, 2: false, 3: false, 4: false};
    switch (status) {
        case "0":
            statusMap["0"]=true;
            break;
        case "1":
            statusMap["1"]=true;
            break;
        case "2":
            statusMap["2"]=true;
            break;
        case "3":
            statusMap["3"]=true;
            break;
        case "4":
            statusMap["4"]=true;
            break;
    }
    return statusMap;
}

function createNewOrder(article){
    "use strict";
    var cusOrder = document.createElement('section');
    cusOrder.classList.add('myDiv');

    var cusOrderPizzaName = document.createElement('h2');
    cusOrderPizzaName.textContent = article["Name"];
    cusOrderPizzaName.className = "pizza_name";

    var pizza_id = document.createElement("h3");
    pizza_id.classname= "pizza_id";
    pizza_id.setAttribute('value', article["OrderedArticleID"]);
    pizza_id.textContent = "Order ID: " + article["OrderedArticleID"];

    var cusOrderButton = document.createElement('div');
    cusOrderButton.classList.add('statusDiv');

    var pizzaStatusArray = insertCheckStatus(article["Status"]);
    var radioInputBestellt = createRadioButton(article["OrderedArticleID"], "Bestellt", pizzaStatusArray["0"]);
    var radioInputImOfen = createRadioButton(article["OrderedArticleID"], "Im Ofen", pizzaStatusArray["1"]);
    var radioInputFertig = createRadioButton(article["OrderedArticleID"], "Fertig", pizzaStatusArray["2"]);
    var radioInputUnterwegs = createRadioButton(article["OrderedArticleID"], "Unterwegs", pizzaStatusArray["3"]);
    var radioInputGeliefert = createRadioButton(article["OrderedArticleID"], "Geliefert", pizzaStatusArray["4"]);

    appendRadioInputWithLabel(cusOrderButton, radioInputBestellt, createLabel("Bestellt"));
    appendRadioInputWithLabel(cusOrderButton, radioInputImOfen, createLabel("Im Ofen"));
    appendRadioInputWithLabel(cusOrderButton, radioInputFertig, createLabel("Fertig"));
    appendRadioInputWithLabel(cusOrderButton, radioInputUnterwegs, createLabel("Unterwegs"));
    appendRadioInputWithLabel(cusOrderButton, radioInputGeliefert, createLabel("Geliefert"));

    cusOrder.appendChild(cusOrderPizzaName);
    cusOrder.appendChild(pizza_id);
    cusOrder.appendChild(cusOrderButton);

    return cusOrder;
}

function updateStatus(cusOrder, OrderedArticleID, status){
    "use strict";
    var buttonStatus = insertCheckStatus(status);
    var selector = 'input[name="' + OrderedArticleID + '"]';
    const radioButtons = cusOrder.querySelectorAll(selector);
    for(var i = 0; i < radioButtons.length; i++){
        radioButtons[i].checked=buttonStatus[i];
    }
}

function checkExistingOrder(orderID){
    "use strict";
    var allOrders = document.getElementById("customer-container").querySelectorAll("section");
    for(var i = 0; i < allOrders.length; i++){
        var order = allOrders[i];
        if (order.querySelector("input[name=pizza_id]") === null){
            return;
        }
        if(order.querySelector("input[name=pizza_id]").getAttribute("value") === orderID){
            return allOrders[i];
        }
    }
    return null;
}

function process(intext) { // Text ins DOM einfuegen
    "use strict";
    var orderingArticles = JSON.parse(intext);
    var customerContainer = document.getElementById('customer-container');

    if(orderingArticles.length === 0){
        return;
    }

    orderingArticles.forEach(article =>{
        var cusOrder;
        if((cusOrder = checkExistingOrder(article["OrderedArticleID"])) != null){
            updateStatus(cusOrder, article["OrderedArticleID"], article["Status"]);
        }
        else{
            var orderSections = customerContainer.querySelectorAll('section');
            orderSections.forEach(function (section) {
                section.remove();
            });

            cusOrder = createNewOrder(article);
            customerContainer.appendChild(cusOrder);
        }
    })
}


function processData() {
    "use strict";
    if (request.readyState === 4) {			// Übertragung = DONE
        if (request.status === 200) {	   		// HTTP-Status = OK
            if (request.responseText != null)
                process(request.responseText);		// Daten weiterverarbeiten
            else console.error("Dokument ist leer");
        } else console.error("Uebertragung fehlgeschlagen:" + request.status);
    }	// else // Übertragung läuft noch
}

var request = new XMLHttpRequest(); // für Firefox & IE7
//request = new ActiveXObject("Microsoft.XMLHTTP"); // für <= IE 6

function requestData() {
    request.open("GET", "KundenStatus.php");	// URL für HTTP-GET festlegen
    request.onreadystatechange = processData;	// Callback-Handler zuordnen (fuer IE7 erst NACH open!!!
    request.send(null);	// Request abschicken
}
document.addEventListener("DOMContentLoaded", function() {
    window.setInterval(requestData, 2000);
});