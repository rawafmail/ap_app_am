<script>
<!--------------------------------------------------------------------- -->
function btnPVActionPerformedTest(){
var myLink;
//if( JE_HEADER_ID != 0 ) { 
	myLink = 'http://192.168.100.211/IIS_web/cheque_test/index.php';
	myLink += '?JE_ID=' + JE_HEADER_ID;
	console.log(myLink);
	//window.open(myLink, '_blank');
	
	var PAY_METHOD = document.getElementById('cbopay_mthd').options[document.getElementById('cbopay_mthd').selectedIndex].value;
	var CASH_GLACCOUNT = document.getElementById('cbocashacc').options[document.getElementById('cbocashacc').selectedIndex].value;
	if (eval(PAY_METHOD)==1){
	var JE_RPT_URL ='<?php
	$SQL = "SELECT 'http://'||NVL(REPORT_LINK,'INVALID_APP_HOST')||'/IIS_WEB/AP_PAY_DOC/print_cash.php?PBCommandParm=313&Ledger_id=".$LEDGER_ID."' FROM PHARCO_REPORTS_MASTER WHERE REPORT_CATEGORY = 'APP_HOST' AND REPORT_LINK IS NOT NULL AND ROWNUM = 1";
$RS  = odbc_exec($CONNECT , $SQL);
while(odbc_fetch_row($RS)){print odbc_result($RS, 1);}?>';
	JE_RPT_URL = JE_RPT_URL+'&s='+CASH_GLACCOUNT.trim()+'&VoucherNumber='+eval(PAYMENT_ID);
	window.open(JE_RPT_URL, '_blank');
	}else{
	var JE_RPT_URL ='<?php
	$SQL = "SELECT 'http://'||NVL(REPORT_LINK,'INVALID_APP_HOST')||'/IIS_WEB/cheque_test/index.php?v=Y&PBCommandParm=313&s=Y&Ledger_id=".$LEDGER_ID."' FROM PHARCO_REPORTS_MASTER WHERE REPORT_CATEGORY = 'APP_HOST' AND REPORT_LINK IS NOT NULL AND ROWNUM = 1";
$RS  = odbc_exec($CONNECT , $SQL);
while(odbc_fetch_row($RS)){print odbc_result($RS, 1);}?>';
	if (eval(JE_HEADER_ID)<=0){
	JE_RPT_URL = JE_RPT_URL+'&JE_ID='+eval(PAYMENT_ID)+'&CheckNumber='+document.getElementById("txtChequeNum").value.trim();	
	}else{
	JE_RPT_URL = JE_RPT_URL+'&JE_ID='+eval(JE_HEADER_ID)+'&CheckNumber='+document.getElementById("txtChequeNum").value.trim();
	}
	
	window.open(JE_RPT_URL, '_blank');
	}

//} else { console.log('HEADER 0')}

}
<!--------------------------------------------------------------------- -->
function btnPCHQActionPerformed(){
	var CASH_GLACCOUNT = document.getElementById('cbocashacc').options[document.getElementById('cbocashacc').selectedIndex].value;
	var JE_RPT_URL ='<?php
	$SQL = "SELECT 'http://'||NVL(REPORT_LINK,'INVALID_APP_HOST')||'/IIS_WEB/".$RPT_FOLDER."/print_checks/print_check.php?v=C&PBCommandParm=313&Ledger_id=".$LEDGER_ID."' FROM PHARCO_REPORTS_MASTER WHERE REPORT_CATEGORY = 'APP_HOST' AND REPORT_LINK IS NOT NULL AND ROWNUM = 1";
$RS  = odbc_exec($CONNECT , $SQL);
while(odbc_fetch_row($RS)){print odbc_result($RS, 1);}?>';
	JE_RPT_URL = JE_RPT_URL+'&JE_ID='+JE_HEADER_ID+'&CheckNumber='+document.getElementById("txtChequeNum").value;
	JE_RPT_URL = JE_RPT_URL+'&s='+CASH_GLACCOUNT;
	console.log(JE_RPT_URL);
	window.open(JE_RPT_URL, '_blank');
}
<!------------------------------------------------------------------------------------------------> 
function GET_CHEQUE_SIGNS() {
	
	var uid =  "<?php print $_REQUEST["uid"]; ?>";
	 // Create our XMLHttpRequest object
    var hr = new XMLHttpRequest();
    // Create some variables we need to send to our PHP file
    var url = "AP_APP_VOUCHER_DB.php";
	var vars = "uid="+uid+"&action=GET_CHEQUE_SIGNS";
    hr.open("POST", url, true);
  
	hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded; charset=utf-8");
	hr.setRequestHeader("Content-length", vars.length);
	hr.setRequestHeader("Connection", "close");
    // Access the onreadystatechange event for the XMLHttpRequest object
    hr.onreadystatechange = function() {
	    if(hr.readyState == 4 && hr.status == 200) {
		    var return_data = hr.responseText;
			document.getElementById("cboAppv1").innerHTML = return_data;
			document.getElementById("cboAppv2").innerHTML = return_data;
	    }
    }
    // Send the data to PHP now... and wait for response
    hr.send(vars); // Actually execute the request
				
}
<!------------------------------------------------------------------------------------------------> 
function PROCESS_CHEQUE() {
	if (JE_HEADER_ID==0){
			alert('The payment journal is not posted');
			return;
	}
	var uid =  "<?php print $_REQUEST["uid"]; ?>";
	var ORG_ID = "<?php print $ORG_ID; ?>";
	var JE_DATA ='';
	var M_DETAIL_DATA='';
	var HEADER_DATA =BuildHeaderSQL();
	 // Create our XMLHttpRequest object
    var hr = new XMLHttpRequest();
    // Create some variables we need to send to our PHP file
    var url = "AP_APP_VOUCHER_DB.php";
	var vars = "ORG_ID="+ORG_ID+"&PAYMENT_ID="+PAYMENT_ID+"&uid="+uid+"&action=PROCESS_CHEQUE&JE_DATA="+JE_DATA+"&M_DETAIL_DATA="+M_DETAIL_DATA+"&HEADER_DATA="+HEADER_DATA;    
	
	hr.open("POST", url, true);  
	hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded; charset=utf-8");
	hr.setRequestHeader("Content-length", vars.length);
	hr.setRequestHeader("Connection", "close");
    // Access the onreadystatechange event for the XMLHttpRequest object
    hr.onreadystatechange = function() {
	    if(hr.readyState == 4 && hr.status == 200) {			
		    var return_data = hr.responseText;			
			document.getElementById("status").innerHTML = '';											
	    }
    }
    // Send the data to PHP now... and wait for response
    hr.send(vars); // Actually execute the request
	document.getElementById("status").innerHTML = '<div class="alert-box warning"><span>Initiating Work Flow: </span>please wait .....</div>';
}
<!------------------------------------------------------------------------------------------------> 
<!------------------------------------------------------------------------------------------------> 
function UPDATE_CHEQUE_DATA() {
	if (JE_HEADER_ID==0){
			alert('The payment journal is not posted');
			return;
	}
	var uid =  "<?php print $_REQUEST["uid"]; ?>";
	var ORG_ID = "<?php print $ORG_ID; ?>";
	var HEADER_DATA =BuildHeaderSQL();
	
	 // Create our XMLHttpRequest object
    var hr = new XMLHttpRequest();
    // Create some variables we need to send to our PHP file
    var url = "AP_APP_VOUCHER_DB.php";
	var vars = "ORG_ID="+ORG_ID+"&PAYMENT_ID="+PAYMENT_ID+"&uid="+uid+"&action=UPDATE_CHEQUE_DATA&HEADER_DATA="+HEADER_DATA;    
	
	
	hr.open("POST", url, true);  
	hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded; charset=utf-8");
	hr.setRequestHeader("Content-length", vars.length);
	hr.setRequestHeader("Connection", "close");
    // Access the onreadystatechange event for the XMLHttpRequest object
    hr.onreadystatechange = function() {
	    if(hr.readyState == 4 && hr.status == 200) {			
		    var return_data = hr.responseText;			
			document.getElementById("status").innerHTML = '';
			alert(return_data);											
	    }
    }
    // Send the data to PHP now... and wait for response
    hr.send(vars); // Actually execute the request
	document.getElementById("status").innerHTML = '<div class="alert-box warning"><span>UPDATE DATA: </span>please wait .....</div>';
}
<!------------------------------------------------------------------------------------------------> 

</script>
