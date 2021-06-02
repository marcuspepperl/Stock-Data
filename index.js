// Update the date and time
function setDate() {
	let date = new Date();
	document.getElementById("dateText").innerHTML = adjustVal(date.getMonth() + 1) + "/" + adjustVal(date.getDate()) + "/" + adjustVal((date.getFullYear() % 1000)) + " " + 
	adjustVal(date.getHours()) + ":" + adjustVal(date.getMinutes()) + ":" + adjustVal(date.getSeconds());
}

function adjustVal(val) {
	if (val < 10) {
		return "0" + val;
	} else {
		return String(val);
	}
}

function changeBackgroundColor() {
	document.body.style.backgroundColor = document.getElementById("colorInput").value;
}

function reqListener () {
      console.log(this.responseText);
    }

// Run each time the current page is loaded
function startup() {
	let prefColor = getCookie('prefColor');
	if (prefColor == null || prefColor == "") {
		console.log("The preferred color is not set\n");
	} else {
		document.body.style.backgroundColor = prefColor;
	}
	console.log("The preferred value is " + getCookie('prefValue'));
	
	let username = getCookie('username');
	if (username == null || username == "") {
		console.log("The username is not set\n");
		document.getElementById("loginText").innerHTML = "Not logged in";
	} else {
		document.getElementById("loginText").innerHTML = "Logged in as " + username;
	}
	
	// Set the current date and time
	window.setInterval(setDate, 1000);
	setDate();
}

function getCookie(cname) {
  var name = cname + "=";
  var decodedCookie = decodeURIComponent(document.cookie);
  var ca = decodedCookie.split(';');
  for(var i = 0; i < ca.length; i++) {
    var c = ca[i];
    while (c.charAt(0) == ' ') {
      c = c.substring(1);
    }
    if (c.indexOf(name) == 0) {
      return c.substring(name.length, c.length);
    }
  }
  return "";
}

function getData() {
	let stockTicker = document.getElementById("stockTicker").value;
	let startDate = document.getElementById("startDate").value;
	let endDate = document.getElementById("endDate").value;
	
	if (isNullOrEmpty(stockTicker) || isNullOrEmpty(startDate) || isNullOrEmpty(endDate)) {
		writeStockError("All form values must be set");
		return;
	}
	
	if (new Date(endDate) < new Date(startDate)) {
		writeStockError("The end date cannot precede the start date");
		return;
	}
	
	clearStockError();
	
	let xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			if (this.responseText == "error") {
				writeStockError("There was a fatal server error");
			} else {
				let data = this.responseText.split("&");
				addStockTableEntry(data[0], data[1], data[2], startDate, endDate);
			}
		}
	};
	
	xmlhttp.open("GET", generateGetRequest(stockTicker, startDate, endDate), true);	
	xmlhttp.send();
}

function generateGetRequest(stockTicker, startDate, endDate) {
	return "http://127.0.0.1:5000/?stockTicker=" + stockTicker + "&startDate=" + startDate + "&endDate=" + endDate;
}

function isNullOrEmpty(str) {
	return str == null || str == "";
}

function writeStockError(str) {
	document.getElementById("errorText").innerHTML = "*" + str;
}

function clearStockError() {
	document.getElementById("errorText").innerHTML = "";
}

function addStockTableEntry(stockTicker, lowPrice, highPrice, startDate, endDate) {
	let tableElem = document.getElementById("stockTable");
	let newRowElem = document.createElement("tr");
	tableElem.appendChild(newRowElem);
	
	let stockTickerElem = document.createElement("td");
	stockTickerElem.appendChild(document.createTextNode(stockTicker));
	
	let lowElem = document.createElement("td");
	lowElem.appendChild(document.createTextNode(lowPrice));
	
	let highElem = document.createElement("td");
	highElem.appendChild(document.createTextNode(highPrice));
	
	let startElem = document.createElement("td");
	startElem.appendChild(document.createTextNode(startDate));
	
	let endElem = document.createElement("td");
	endElem.appendChild(document.createTextNode(endDate));
	
	newRowElem.appendChild(stockTickerElem);
	newRowElem.appendChild(lowElem);
	newRowElem.appendChild(highElem);	
	newRowElem.appendChild(startElem);	
	newRowElem.appendChild(endElem);	
}

function clearStockTable() {
	// Do something
	let tableElem = document.getElementById("stockTable");
	while (tableElem.childElementCount > 1) {
		tableElem.removeChild(tableElem.lastElementChild);
	}
}
