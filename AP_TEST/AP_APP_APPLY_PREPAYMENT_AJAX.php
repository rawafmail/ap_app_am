<script>
<!--------------------------------------------------------------------- -->
$(document).ready(function() {
    $('#recAPPGrid').DataTable( {
		//retrieve: true,
		"columnDefs": [  { "targets": [ 0],"width": "40%"},
						 { "targets": [ 1],"width": "40%"},
						 { "targets": [ 4],"visible": false}] ,
 
		paging:   false,
        ordering: false,
        info:     false,
		searching: false,
        scrollY: 200,
        scrollX: true
    } );
$('#recAPPGrid').on('key-focus', function (e, datatable, cell) {
    var inputFieldInSelectedTd = $(cell.node()).find('input');
    if (inputFieldInSelectedTd) {
      inputFieldInSelectedTd.focus();	  
   }
});	
	
} );
<!------------------------------------------------------------------------------------------------>
function UNAPPLY_PREPAYMENT(appid,rowid){
	if (JE_HEADER_ID !=0){
		document.getElementById("status").innerHTML = '<div class="alert-box errormsg">You could not update records which have a posted journal entry.</div>';
		return;	
	}
	var uid =  "<?php print $_REQUEST["uid"]; ?>";
	var ORG_ID = "<?php print $ORG_ID; ?>";
	var hr = new XMLHttpRequest();
    // Create some variables we need to send to our PHP file
    var url = "AP_APP_APPLY_PREPAYMENT_DB.php";
	var vars = "uid="+uid+"&ORG_ID="+ORG_ID+"&APPLICATION_ID="+appid+"&action=UNAPPLY_PREPAYMENT";
    hr.open("POST", url, true);
  
	hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded; charset=utf-8");
	hr.setRequestHeader("Content-length", vars.length);
	hr.setRequestHeader("Connection", "close");
    // Access the onreadystatechange event for the XMLHttpRequest object
    hr.onreadystatechange = function() {
	    if(hr.readyState == 4 && hr.status == 200) {
		    var return_data = hr.responseText;			
			document.getElementById("status").innerHTML=return_data;
			if (return_data.trim()=='UNAPPLIED'){
				var t = $('#recAPPGrid').DataTable();
				if (rowid >=1){t.row(eval(rowid)-1).remove().draw();}
				document.getElementById("status").innerHTML='';
			}		
	    }
    }
    // Send the data to PHP now... and wait for response
    hr.send(vars); // Actually execute the request
	document.getElementById("status").innerHTML = '<div class="alert-box warning"><span>UN-APPLY PRE-PAYMENT: </span>please wait .....</div>';

}
<!------------------------------------------------------------------------------------------------>
function APPLY_PREPAYMENT(){
	if (JE_HEADER_ID !=0){
		document.getElementById("status").innerHTML = '<div class="alert-box errormsg">You could not update records which have a posted journal entry.</div>';
		return;	
	}
	var uid =  "<?php print $_REQUEST["uid"]; ?>";
	var ORG_ID = "<?php print $ORG_ID; ?>";
	PREPAYMENT_ID=document.getElementById('prepayvendors').options[document.getElementById('prepayvendors').selectedIndex].value;
	APPY_TO_ID=document.getElementById('applytrans').options[document.getElementById('applytrans').selectedIndex].value;
	APPY_AMT=document.getElementById('appamount').value;
	 // Create our XMLHttpRequest object
    var hr = new XMLHttpRequest();
    // Create some variables we need to send to our PHP file
    var url = "AP_APP_APPLY_PREPAYMENT_DB.php";
	var vars = "uid="+uid+"&ORG_ID="+ORG_ID+"&APPY_TO_ID="+APPY_TO_ID+"&APPY_AMT="+APPY_AMT+"&PREPAYMENT_ID="+PREPAYMENT_ID+"&PAYMENT_ID="+PAYMENT_ID+"&action=APPLY_PREPAYMENT";
    hr.open("POST", url, true);
  
	hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded; charset=utf-8");
	hr.setRequestHeader("Content-length", vars.length);
	hr.setRequestHeader("Connection", "close");
    // Access the onreadystatechange event for the XMLHttpRequest object
    hr.onreadystatechange = function() {
	    if(hr.readyState == 4 && hr.status == 200) {
		    var return_data = hr.responseText;			
			var datarows = return_data.split("</tr>");
			var datarows_count = datarows.length-1;
			if (eval(datarows_count) <=0){
				document.getElementById("status").innerHTML=return_data;
			}else{
				document.getElementById("status").innerHTML='';
			}
			for(var i = 0 ; i < datarows_count; i++) {
    			var datacols =datarows[i].split("</td>");
				var t = $('#recAPPGrid').DataTable({retrieve: true});
						
				t.row.add( [datacols[0],
							datacols[1],
							datacols[2],
							datacols[3],
							datacols[4]] ).draw( false );

   			}
			  
			 
       //If table is in the tab, you need to adjust headers when tab becomes visible
			 // $($.fn.dataTable.tables(true)).DataTable()
			 // .columns.adjust();			
	    }
    }
    // Send the data to PHP now... and wait for response
    hr.send(vars); // Actually execute the request
	document.getElementById("status").innerHTML = '<div class="alert-box warning"><span>VALIDATE AMOUNT APPLICATION: </span>please wait .....</div>';
}
<!------------------------------------------------------------------------------------------------>
function GET_PREPAY_VENDORS_LOV(PAYMENT_ID){
	CURR_CODE=document.getElementById('cboCurr').options[document.getElementById('cboCurr').selectedIndex].value;
	 // Create our XMLHttpRequest object
    var hr = new XMLHttpRequest();
    // Create some variables we need to send to our PHP file
    var url = "AP_APP_APPLY_PREPAYMENT_DB.php";
	var vars = "CURR_CODE="+CURR_CODE+"&PAYMENT_ID="+PAYMENT_ID+"&action=GET_PREPAY_VENDORS_LOV";
	
    hr.open("POST", url, true);
	hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded; charset=utf-8");
	hr.setRequestHeader("Content-length", vars.length);
	hr.setRequestHeader("Connection", "close");
    // Access the onreadystatechange event for the XMLHttpRequest object
    hr.onreadystatechange = function() {
	    if(hr.readyState == 4 && hr.status == 200) {
		    var return_data = hr.responseText;
			document.getElementById("status").innerHTML='';
			document.getElementById("prepayvendors").innerHTML = return_data;
			var x = document.getElementById("prepayvendors").length;
			//document.getElementById("status").innerHTML = '<div class="alert-box success"><span>TOTAL FOUND NUMBER OF AVAILABLE PREPAYMENTS: </span>'+(eval(x)-1)+'</div>';
			//alert('TOTAL FOUND NUMBER OF AVAILABLE PREPAYMENTS: '+(eval(x)-1));
			if(confirm('TOTAL FOUND NUMBER OF AVAILABLE PREPAYMENTS:[ '+(eval(x)-1)+' ]\n\n Do you want to apply pre-payments automatically now?')){
				APPLY_PREPAYMENT_AUTO();
			}
			if ((ActiveTab=='PM')&&(eval(x)>1)){document.getElementById("PREAPPOpen").click();}
	    }
    }
    // Send the data to PHP now... and wait for response
    hr.send(vars); // Actually execute the request
	document.getElementById("status").innerHTML = '<div class="alert-box warning"><span>SEARCHING FOR AVAILABLE PREPAYMENTS: </span>please wait .....</div>';
}
<!------------------------------------------------------------------------------------------------>
function APPLY_PREPAYMENT_AUTO(){
	var uid =  "<?php print $_REQUEST["uid"]; ?>";
	var ORG_ID = "<?php print $ORG_ID; ?>";
	 // Create our XMLHttpRequest object
    var hr = new XMLHttpRequest();
    // Create some variables we need to send to our PHP file
    var url = "AP_APP_APPLY_PREPAYMENT_DB.php";
	var vars = "PAYMENT_ID="+PAYMENT_ID+"&ORG_ID="+ORG_ID+"&uid="+uid+"&action=APPLY_PREPAYMENT_AUTO";
	
    hr.open("POST", url, true);
	hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded; charset=utf-8");
	hr.setRequestHeader("Content-length", vars.length);
	hr.setRequestHeader("Connection", "close");
    // Access the onreadystatechange event for the XMLHttpRequest object
    hr.onreadystatechange = function() {
	    if(hr.readyState == 4 && hr.status == 200) {
		    var return_data = hr.responseText;
			document.getElementById("status").innerHTML=return_data;
			GET_PREPAY_VENDORS_LOV_AUTO(PAYMENT_ID);
			var myTable = $('#recAPPGrid').DataTable();
			myTable
				.clear()
				.draw();
		    searchForEditPrePayApp(PAYMENT_ID);		
	    }
    }
    // Send the data to PHP now... and wait for response
    hr.send(vars); // Actually execute the request
	document.getElementById("status").innerHTML = '<div class="alert-box warning"><span>SEARCHING FOR AVAILABLE PREPAYMENTS: </span>please wait .....</div>';
}
<!------------------------------------------------------------------------------------------------>
function GET_PREPAY_VENDORS_LOV_AUTO(PAYMENT_ID){
	CURR_CODE=document.getElementById('cboCurr').options[document.getElementById('cboCurr').selectedIndex].value;
	 // Create our XMLHttpRequest object
    var hr = new XMLHttpRequest();
    // Create some variables we need to send to our PHP file
    var url = "AP_APP_APPLY_PREPAYMENT_DB.php";
	var vars = "CURR_CODE="+CURR_CODE+"&PAYMENT_ID="+PAYMENT_ID+"&action=GET_PREPAY_VENDORS_LOV";
	
    hr.open("POST", url, true);
	hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded; charset=utf-8");
	hr.setRequestHeader("Content-length", vars.length);
	hr.setRequestHeader("Connection", "close");
    // Access the onreadystatechange event for the XMLHttpRequest object
    hr.onreadystatechange = function() {
	    if(hr.readyState == 4 && hr.status == 200) {
		    var return_data = hr.responseText;
			document.getElementById("status").innerHTML='';
			document.getElementById("prepayvendors").innerHTML = return_data;			
	    }
    }
    // Send the data to PHP now... and wait for response
    hr.send(vars); // Actually execute the request
	document.getElementById("status").innerHTML = '<div class="alert-box warning"><span>SEARCHING FOR AVAILABLE PREPAYMENTS: </span>please wait .....</div>';
}
<!------------------------------------------------------------------------------------------------>
function GET_TRANS_APPLICATIONS_LOV(PREPAYMENT_ID){
	
	 // Create our XMLHttpRequest object
    var hr = new XMLHttpRequest();
    // Create some variables we need to send to our PHP file
    var url = "AP_APP_APPLY_PREPAYMENT_DB.php";
	var vars = "PREPAYMENT_ID="+PREPAYMENT_ID+"&PAYMENT_ID="+PAYMENT_ID+"&action=GET_TRANS_APPLICATIONS_LOV";
    hr.open("POST", url, true);
  
	hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded; charset=utf-8");
	hr.setRequestHeader("Content-length", vars.length);
	hr.setRequestHeader("Connection", "close");
    // Access the onreadystatechange event for the XMLHttpRequest object
    hr.onreadystatechange = function() {
	    if(hr.readyState == 4 && hr.status == 200) {
		    var return_data = hr.responseText;
			document.getElementById("status").innerHTML='';
			document.getElementById("applytrans").innerHTML = return_data;
	    }
    }
    // Send the data to PHP now... and wait for response
    hr.send(vars); // Actually execute the request
	document.getElementById("status").innerHTML = '<div class="alert-box warning"><span>SEARCHING FOR TRANSACTIONS: </span>please wait .....</div>';
}

</script>
