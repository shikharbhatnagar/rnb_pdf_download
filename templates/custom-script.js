function showTab(n) {
	console.log("showTab");
	// This function will display the specified tab of the form...
	var x = document.getElementsByClassName("tab");
	x[n].style.display = "block";
	//... and fix the Previous/Next buttons:
	if (n == 0) {
	document.getElementById("prevBtn").style.display = "none";
	} else {
	document.getElementById("prevBtn").style.display = "inline";
	}
	if (n == (x.length - 1)) {
	document.getElementById("nextBtn").innerHTML = "Submit";
	} else {
		console.log("here next");
	document.getElementById("nextBtn").innerHTML = "Next";
	}
	//... and run a function that will display the correct step indicator:
	fixStepIndicator(n)
}

function nextPrev(n) {
	console.log("N:",n);
	// This function will figure out which tab to display
  var x = document.getElementsByClassName("tab");

  console.log("X:",x);
  console.log("currentTab:",currentTab);
  // Exit the function if any field in the current tab is invalid:
  //if (n == 1 && !validateForm()) return false;
  
  // Hide the current tab:
  x[currentTab].style.display = "none";
  // Increase or decrease the current tab by 1:
  currentTab = currentTab + n;
  // if you have reached the end of the form...
  if (currentTab >= x.length) {
    // ... the form gets submitted:
    //document.getElementById("regForm").submit();
    console.log("Submit button clicked");
    return false;
  }
  // Otherwise, display the correct tab:
  showTab(currentTab);
}

function fixStepIndicator(n) {
  // This function removes the "active" class of all steps...
  var i, x = document.getElementsByClassName("step");
  for (i = 0; i < x.length; i++) {
    x[i].className = x[i].className.replace(" active", "");
  }
  //... and adds the "active" class on the current step:
  x[n].className += " active";
}

var currentTab = 0; // Current tab is set to be the first tab (0)
showTab(currentTab); // Display the current tab

function correctURL(url){
	alert(url);
	url	=	url.replace(/^http(s?):\/\//i, ''); 	//replace('http', ''); 
	url	= 	url.replace(/:/g, ''); 		//url.replace(':', ''); 
	url	= 	url.replace(/\//g, ''); 	//url.replace('/', ''); 
	url	= 	url.replace(/www./g, ''); 	//url.replace('/', ''); 
	if(url.includes("myshopify.com")){
		document.getElementById('txtshopurl').value	=	url;
	}
	else{
		document.getElementById('txtshopurl').value	=	'';
	}
}
function isNumber(evt) {
    var iKeyCode = (evt.which) ? evt.which : evt.keyCode
    if (iKeyCode != 46 && iKeyCode > 31 && (iKeyCode < 48 || iKeyCode > 57)) {
        return false;
    } else {
        return true;
    }

}