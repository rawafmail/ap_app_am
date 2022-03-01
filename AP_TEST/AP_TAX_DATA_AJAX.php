<script>

<!--------------------------------------------------------------------------------------------->
function GET_VENDOR_TAX_DATA(VENDOR_NUM) {
    var uid =  "<?php print $uid ; ?>";
    var ORG_ID =  "<?php print $ORG_ID; ?>";
    // Create our XMLHttpRequest object
    var hr = new XMLHttpRequest();
    // Create some variables we need to send to our PHP file
    var url = "AP_TAX_DATA_DB.php";
    var vars = "VENDOR_NUM="+VENDOR_NUM+"&ORG_ID="+ORG_ID+"&uid="+uid+"&search_item="+search_item+"&action=GET_VENDOR_TAX_DATA";
    hr.open("POST", url, true);
    // Set content type header information for sending url encoded variables in the request
    //hr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=utf-8');
	hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded; charset=utf-8");
	hr.setRequestHeader("Content-length", vars.length);
	hr.setRequestHeader("Connection", "close");
    // Access the onreadystatechange event for the XMLHttpRequest object
    hr.onreadystatechange = function() {
	    if(hr.readyState == 4 && hr.status == 200) {
		    var return_data = hr.responseText;
			document.getElementById("VENDOR_TAX_DATA").innerHTML =return_data; 
	    }
    }
    // Send the data to PHP now... and wait for response to update the status div
    hr.send(vars); // Actually execute the request
}
<!--------------------------------------------------------------------------------------------->
<!--------------------------------------------------------------------------------------------->
function SAVE_VENDOR_TAX_DATA() {
    var uid =  "<?php print $uid ; ?>";
    var ORG_ID =  "<?php print $ORG_ID; ?>";
	TAX_FILE=document.getElementById("TAX_FILE").value;
	TAX_REG=document.getElementById("TAX_REG").value;
	VENDOR_ID=document.getElementById("VENDOR_ID").value;
	TAX_DEPT_ID=document.getElementById("TAX_DEPT_ID").options[document.getElementById("TAX_DEPT_ID").selectedIndex].value;
    // Create our XMLHttpRequest object
    var hr = new XMLHttpRequest();
    // Create some variables we need to send to our PHP file
    var url = "AP_TAX_DATA_DB.php";
    var vars = "TAX_DEPT_ID="+TAX_DEPT_ID+"&TAX_FILE="+TAX_FILE+"&TAX_REG="+TAX_REG+"&VENDOR_ID="+VENDOR_ID+"&ORG_ID="+ORG_ID+"&uid="+uid+"&action=SAVE_VENDOR_TAX_DATA";

    hr.open("POST", url, true);
    // Set content type header information for sending url encoded variables in the request
    //hr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=utf-8');
	hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded; charset=utf-8");
	hr.setRequestHeader("Content-length", vars.length);
	hr.setRequestHeader("Connection", "close");
    // Access the onreadystatechange event for the XMLHttpRequest object
    hr.onreadystatechange = function() {
	    if(hr.readyState == 4 && hr.status == 200) {
		    var return_data = hr.responseText;			
			alert(return_data);
	    }
    }
    // Send the data to PHP now... and wait for response to update the status div
    hr.send(vars); // Actually execute the request
}
<!--------------------------------------------------------------------------------------------->
</script>