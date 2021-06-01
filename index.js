// Update the date and time
function setDate() {
	let date = new Date();
	document.getElementById("date").innerHTML = correctVal(date.getMonth() + 1) + "/" + correctVal(date.getDate()) + "/" + correctVal((date.getFullYear() % 1000)) + " " + 
	correctVal(date.getHours()) + ":" + correctVal(date.getMinutes()) + ":" + correctVal(date.getSeconds());
}

function correctVal(val) {
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
	
	// Set the current date and time
	window.setInterval(setDate, 1000);
	setDate();
}

function getCookie(cname) {
  var name = cname + "=";
  var decodedCookie = decodeURIComponent(document.cookie);
  var ca = decodedCookie.split(';');
  for(var i = 0; i <ca.length; i++) {
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
