
function openCity(evt, cityName) {
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    document.getElementById(cityName).style.display = "block";
    evt.currentTarget.className += " active";
}
document.getElementById("defaultOpen").click();

document.getElementById('fasa_id').disabled = true;
document.getElementById('fasa_co_id').disabled = true;
document.getElementById('fasa_com').disabled = true;
function clicked(){
	if (document.getElementById('sandbox_mode').checked) {
    	document.getElementById('fasa_id').disabled = false;
	 	document.getElementById('fasa_co_id').disabled = true;
		document.getElementById('fasa_com').disabled = true;
	}else{
		document.getElementById('fasa_id').disabled = true;
	 	document.getElementById('fasa_co_id').disabled = false;
		document.getElementById('fasa_com').disabled = false;
	}
}
if (document.getElementById('sandbox_mode').checked) {
    	document.getElementById('fasa_id').disabled = false;
	 	document.getElementById('fasa_co_id').disabled = true;
		document.getElementById('fasa_com').disabled = true;
	}else if((document.getElementById('live_mode').checked)){
		document.getElementById('fasa_id').disabled = true;
	 	document.getElementById('fasa_co_id').disabled = false;
		document.getElementById('fasa_com').disabled = false;
	}
