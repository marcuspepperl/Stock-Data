// Update the date and time
function setDate() {
	let date = new Date();
	document.getElementById("date").innerHTML = correctVal(date.getMonth()) + "/" + correctVal(date.getDate()) + "/" + correctVal((date.getFullYear() % 1000)) + " " + 
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

// Update the date and time each second
function startup() {
	window.setInterval(setDate, 1000);
	setDate();
}
