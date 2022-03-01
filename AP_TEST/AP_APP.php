<?php


/*
*
* Friday, April 10, 2020
* @author Sherif Abdel Moneom

||||||||||||||||||||||||||==== CAUTION ==== CAUTION ==== CAUTION ==== |||||||||||||||||||||||||
===============================================================================================
---			   IF YOU WANT TO EDIT/MODIFY THIS SOURCE FILE							  ---------
---            PLEASE BE A CAREFUL WHEN YOU GO TO MODIFY THE [PHP AJAX] SECTION       ---------
---            PLEASE BE A CAREFUL WHEN YOU GO TO MODIFY THE [JavaScript] SECTION     ---------
---            PLEASE BE A CAREFUL WHEN YOU GO TO MODIFY THE [CSS] SECTION     		  ---------
---			   IT IS A VERY STRONGLY RECOMMENDED TO MAKE A BACKUP COPY FROM THIS SOURCE FILE  -
===============================================================================================
||||||||||||||||||||||||||==== CAUTION ==== CAUTION ==== CAUTION ==== |||||||||||||||||||||||||

*/
?>
<?php

set_time_limit(50000);
header("Expires: 0");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

ini_set("SMTP", "192.168.100.10");
ini_set("sendmail_from", "ir.admin@pharcoerp.com");
$headers = "Content-Type: text/html; charset=windows-1256";
//print  '<meta http-equiv="Content-type" content="text/html; charset=windows-1256" />';
//-- MAIN VARIABLES
$RPT_FOLDER ='amr_gl';
$ORGANIZATION_ID = 112;
$COMPANY = '01';
$ORG_ID = 91 ;
$LEDGER_ID = 2023 ;
$LEGAL_ENTITY_ID =23273;
$LOCATION_ID = 142;
$uid = $_REQUEST["uid"];
$action = $_POST["action"];
//
//-- DATABASE CONNECTION 
$CONNECT = odbc_connect('PROD', 'apps', 'apps');
?>


<!DOCTYPE html>
<html>

<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-type" content="text/html; charset=windows-1256" />
<title>Payments Workbench</title>
<link rel="icon" href="./favicon.png">
<link href="jquery.dataTables.min.css" rel="stylesheet">
<link href="keyTable.dataTables.min.css" rel="stylesheet">
<link href="style.css" rel="stylesheet">
<link rel="stylesheet" href="../Lib/select2.min.css">
<link rel="stylesheet" href="../Lib/select2.css">
<style>
table.dataTable td.dataTables_empty{
  text-align:left;
}
div.dataTables_wrapper {
        width: 100%;
        margin: 0 auto;
		font-size: 11px; 
		font-weight:normal;
    }
</style>
<script  type="text/javascript" src="jquery-3.3.1.js"></script>
<script  type="text/javascript" src="jquery.dataTables.min.js"></script>
<script  type="text/javascript" src="dataTables.keyTable.min.js"></script>
<script  type="text/javascript" src="dataTables.scroller.min.js"></script>
<script src="../Lib/select2.min.js"></script>
	<script>
		$(document).ready(function() {
			$('#cboJEPO').select2({ 
				allowClear:true
				//,width: '200px'
			});
		});
	</script>
<script>
<!--------------------------------------------------------------------- -->
//Public Variables Declaration
var frmMode = 0; // 1=New Insert     2=Edit      3=Search
var PAYMENT_ID = 0;
var JE_HEADER_ID = 0;
var ValidationPass = 0;
var ActiveTab ='';
var EditJE_TYPE = '';
var MatchedJE_FLAG = '';
var STATUS_CODE_V ='';
<!--------------------------------------------------------------------- -->
<!---------searchForEdit (0)------------------------------------------- -->
function searchForEditHeader(payid){
	searchForEditClear();
	SEARCH_PAYMENT_OVERLAY();
	var uid =  "<?php print $_REQUEST["uid"]; ?>";
	var ORG_ID = "<?php print $ORG_ID; ?>";
	var LEDGER_ID = "<?php print $LEDGER_ID; ?>";	
	var ORGANIZATION_ID =  "<?php print $ORGANIZATION_ID; ?>";
	 // Create our XMLHttpRequest object
    var hr = new XMLHttpRequest();
    // Create some variables we need to send to our PHP file
    var url = "AP_APP_MAIN_DB.php";
	var vars = "ORGANIZATION_ID="+ORGANIZATION_ID+"&LEDGER_ID="+LEDGER_ID+"&PAYMENT_ID="+payid+"&ORG_ID="+ORG_ID+"&uid="+uid+"&action=searchForEditHeader";
    
	hr.open("POST", url, true);
  
	hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded; charset=utf-8");
	hr.setRequestHeader("Content-length", vars.length);
	hr.setRequestHeader("Connection", "close");
    // Access the onreadystatechange event for the XMLHttpRequest object
    hr.onreadystatechange = function() {
	    if(hr.readyState == 4 && hr.status == 200) {
			document.getElementById("status").innerHTML ='';
			var return_data = hr.responseText;	    
			var datarows = return_data.split("</tr>");
			var datarows_count = datarows.length-1;
			if (datarows_count <= 0){document.getElementById("status").innerHTML ='<div class="alert-box errormsg">No Data Found</div>';return;}
			frmMode =2;
			var datacols =datarows[0].split("</td>");
			PAYMENT_ID = datacols[0];
			JE_HEADER_ID = datacols[16];
			document.getElementById("cboCurr").value = datacols[14];
			GET_BANK_ACCOUNTS_LOV(datacols[14],datacols[5]);
			document.getElementById("cbopay_mthd").value = datacols[18];
			document.getElementById("txtCurrRate").value = datacols[15];
			document.getElementById("txtAmount").value = datacols[9];
			document.getElementById("cbocashacc").value = datacols[5];
			document.getElementById("txtDocNum").value = datacols[2];
			document.getElementById("txtDocDate").value = datacols[3];
			document.getElementById("txtPayeeNum").value = datacols[17];
			document.getElementById("txtPayeeName").value = datacols[4];
			document.getElementById("txtNotes").value = datacols[13];
			document.getElementById("txtGLDATE").value = datacols[27];
			STATUS_CODE_V =  datacols[26];
			
			//--
			document.getElementById("nonE").value = datacols[8];
			if (document.getElementById('nonE').selectedIndex==-1){document.getElementById('nonE').selectedIndex=0;}
			document.getElementById("txtChequeNum").value = datacols[6];
			document.getElementById("txtChequeDate").value = datacols[7];
			document.getElementById("cboAppv1").value = datacols[11];
			document.getElementById("cboAppv2").value = datacols[12];
			if (document.getElementById('cboAppv1').selectedIndex==-1){document.getElementById('cboAppv1').selectedIndex=0;}			
			if (document.getElementById('cboAppv2').selectedIndex==-1){document.getElementById('cboAppv2').selectedIndex=0;}
			//--
			EnableDisableButtons();			
			GET_PREPAY_VENDORS_LOV(payid);  
			searchForEditLines(payid);
			searchForEditPrePayApp(payid);			
			searchForEditJE(payid);
	    }
    }
    // Send the data to PHP now... and wait for response
    hr.send(vars); // Actually execute the request
	document.getElementById("status").innerHTML = '<div class="alert-box warning"><span>GET TRANSACTION MAIN DATA: </span>please wait .....</div>';
			
}
<!--------------------------------------------------------------------- -->
function searchForEditLines(payid){
	var uid =  "<?php print $_REQUEST["uid"]; ?>";
	var ORG_ID = "<?php print $ORG_ID; ?>";
	var LEDGER_ID = "<?php print $LEDGER_ID; ?>";	
	var ORGANIZATION_ID =  "<?php print $ORGANIZATION_ID; ?>";
	MatchedJE_FLAG='';
	 // Create our XMLHttpRequest object
    var hr = new XMLHttpRequest();
    // Create some variables we need to send to our PHP file
    var url = "AP_APP_MAIN_DB.php";
	var vars = "ORGANIZATION_ID="+ORGANIZATION_ID+"&LEDGER_ID="+LEDGER_ID+"&PAYMENT_ID="+payid+"&ORG_ID="+ORG_ID+"&uid="+uid+"&action=searchForEditLines";
    
	hr.open("POST", url, true);
  
	hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded; charset=utf-8");
	hr.setRequestHeader("Content-length", vars.length);
	hr.setRequestHeader("Connection", "close");
    // Access the onreadystatechange event for the XMLHttpRequest object
    hr.onreadystatechange = function() {
	    if(hr.readyState == 4 && hr.status == 200) {	
				    
			var return_data = hr.responseText;
			document.getElementById("status").innerHTML ='';	
			var datarows = return_data.split("</tr>");
			var datarows_count = datarows.length-1;
			var rowexist =0;
			
			for(var i = 0 ; i < datarows_count; i++) {
				if (i==0){MatchedJE_FLAG='Y';}
    			var datacols =datarows[i].split("</td>");
				var t = $('#recRCTGrid').DataTable();
				var errorscount=0;			
				
				t.column(24).nodes().each(function (cell, j) {
					if (t.cell(j, 24).data()==datacols[24]){
						rowexist = eval(rowexist)+1;					
						errorscount =1;
					}
				});
				if (errorscount ==1){continue;}
				
				t.row.add( [datacols[0],
							datacols[1],
							datacols[2],
							datacols[3],
							datacols[4],
							datacols[5],
							datacols[6],
							datacols[7],
							datacols[8],
							datacols[9],
							datacols[10],
							datacols[11],
							datacols[12],
							datacols[13],
							datacols[14],
							datacols[15],
							datacols[16],
							datacols[17],
							datacols[18],
							datacols[19],
							datacols[20],
							datacols[21],
							datacols[22],
							datacols[23],
							datacols[24]
							 ] ).draw( false );

   			}
			  
			  if (rowexist >0){alert('Found: '+rowexist+' rows already exists');}
       //If table is in the tab, you need to adjust headers when tab becomes visible
			  $($.fn.dataTable.tables(true)).DataTable()
			  .columns.adjust();
			  if (datarows_count>0){UpdateSummaryGrid();}		  
	    }			
    }
    // Send the data to PHP now... and wait for response
    hr.send(vars); // Actually execute the request
	document.getElementById("status").innerHTML = '<div class="alert-box warning"><span>GET TRANSACTION DETAILS/LINES: </span>please wait .....</div>';
			
}
<!--------------------------------------------------------------------- -->
function searchForEditPrePayApp(payid){
	var uid =  "<?php print $_REQUEST["uid"]; ?>";
	var ORG_ID = "<?php print $ORG_ID; ?>";
	var LEDGER_ID = "<?php print $LEDGER_ID; ?>";	
	var ORGANIZATION_ID =  "<?php print $ORGANIZATION_ID; ?>";
	 // Create our XMLHttpRequest object
    var hr = new XMLHttpRequest();
    // Create some variables we need to send to our PHP file
    var url = "AP_APP_MAIN_DB.php";
	var vars = "ORGANIZATION_ID="+ORGANIZATION_ID+"&LEDGER_ID="+LEDGER_ID+"&PAYMENT_ID="+payid+"&ORG_ID="+ORG_ID+"&uid="+uid+"&action=searchForEditPrePayApp";
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
	    }
    }
    // Send the data to PHP now... and wait for response
    hr.send(vars); // Actually execute the request
	document.getElementById("status").innerHTML = '<div class="alert-box warning"><span>GET TRANSACTION APPLICATIONS: </span>please wait .....</div>';
			
}
<!--------------------------------------------------------------------- -->

function searchForEditJE(payid){
	var uid =  "<?php print $_REQUEST["uid"]; ?>";
	var ORG_ID = "<?php print $ORG_ID; ?>";
	var LEDGER_ID = "<?php print $LEDGER_ID; ?>";	
	var ORGANIZATION_ID =  "<?php print $ORGANIZATION_ID; ?>";
	 // Create our XMLHttpRequest object
    var hr = new XMLHttpRequest();
    // Create some variables we need to send to our PHP file
    var url = "AP_APP_MAIN_DB.php";
	var vars = "ORGANIZATION_ID="+ORGANIZATION_ID+"&LEDGER_ID="+LEDGER_ID+"&PAYMENT_ID="+payid+"&ORG_ID="+ORG_ID+"&uid="+uid+"&action=searchForEditJE";
    hr.open("POST", url, true);
  
	hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded; charset=utf-8");
	hr.setRequestHeader("Content-length", vars.length);
	hr.setRequestHeader("Connection", "close");
    // Access the onreadystatechange event for the XMLHttpRequest object
    hr.onreadystatechange = function() {
	    if(hr.readyState == 4 && hr.status == 200) {
			var return_data = hr.responseText;
			var datarows = return_data.split("</tr>");
			document.getElementById("status").innerHTML = '';
			var t = $('#recJEGrid').DataTable();
			t
			.clear()
			.draw();
			var datarows_count = datarows.length-1;
			for(var i = 0 ; i < datarows_count; i++) {
    			var datacols =datarows[i].split("</td>");
				t.row.add( [datacols[0],
							datacols[1],
							datacols[2],
							datacols[3],
							datacols[4],
							datacols[5],
							datacols[6],
							datacols[7],
							datacols[8]
							 ] ).draw( false );

   			}
			  
			  
       //If table is in the tab, you need to adjust headers when tab becomes visible
			  $($.fn.dataTable.tables(true)).DataTable()
			  .columns.adjust();
			  UpdateJEntrySummaryGrid();
	    }
    }
    // Send the data to PHP now... and wait for response
    hr.send(vars); // Actually execute the request
	document.getElementById("status").innerHTML = '<div class="alert-box warning"><span>GET TRANSACTION JOURNAL: </span>please wait .....</div>';
			
}
<!--------------------------------------------------------------------- -->

function searchForEditClear(){
	
	PAYMENT_ID = 0;
	frmMode = 0;
	EditJE_TYPE = '';
	JE_HEADER_ID = 0;
    ValidationPass = 0;
	
	var myTable = $('#recJEGrid').DataTable();
 	myTable
		.clear()
		.draw();
	//
	ClearPaymentsDetailsControls();
	var oForm = document.getElementById("AP_FORM");
	var frm_elements = oForm.elements;
	for (i = 0; i < frm_elements.length; i++){
		field_type = frm_elements[i].type.toLowerCase();
		switch (field_type)
		{
		case "text":
		case "password":
		case "textarea":
		case "hidden":
			frm_elements[i].value = "";
			break;		
		case "select-one":
		case "select-multi":
			frm_elements[i].selectedIndex = 0;
			break;
		default:
			break;
		}
	}
	//
	document.getElementById("cboSHIP").innerHTML='';
	document.getElementById("cboMI").innerHTML='';
	document.getElementById("cboCurr").value = "";
	GET_BANK_ACCOUNTS_LOV('','');
	document.getElementById("invje").value = '';
	document.getElementById("txtCurrRate").value = 1;
	document.getElementById("txtAmount").value = 0;
	document.getElementById("dr").value = 0;
	document.getElementById("cr").value = 0;
	//
	document.getElementById("defaultOpen").click();
		
 }
<!---------searchForEdit (1)------------------------------------------- -->
<!--------------------------------------------------------------------- -->

function btnPDTLActionPerformed(){
	var PAY_METHOD = document.getElementById('cbopay_mthd').options[document.getElementById('cbopay_mthd').selectedIndex].value;
	var CASH_GLACCOUNT = document.getElementById('cbocashacc').options[document.getElementById('cbocashacc').selectedIndex].value;	
	var JE_RPT_URL ='<?php
	$SQL = "SELECT 'http://'||NVL(REPORT_LINK,'INVALID_APP_HOST')||'/IIS_WEB/AP_PAY_DOC/AP_PAY_DOC_DETAILS.php?PBCommandParm=313&Ledger_id=".$LEDGER_ID."' FROM PHARCO_REPORTS_MASTER WHERE REPORT_CATEGORY = 'APP_HOST' AND REPORT_LINK IS NOT NULL AND ROWNUM = 1";
$RS  = odbc_exec($CONNECT , $SQL);
	while(odbc_fetch_row($RS)){print odbc_result($RS, 1);}?>';	
	JE_RPT_URL = JE_RPT_URL+'&JE_ID='+eval(PAYMENT_ID);
	window.open(JE_RPT_URL, '_blank');
	
	
}
<!--------------------------------------------------------------------- -->
function btnPVActionPerformed(){
	
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
	$SQL = "SELECT 'http://'||NVL(REPORT_LINK,'INVALID_APP_HOST')||'/IIS_WEB/".$RPT_FOLDER."/print_checks/print_check.php?v=Y&PBCommandParm=313&s=Y&Ledger_id=".$LEDGER_ID."' FROM PHARCO_REPORTS_MASTER WHERE REPORT_CATEGORY = 'APP_HOST' AND REPORT_LINK IS NOT NULL AND ROWNUM = 1";
$RS  = odbc_exec($CONNECT , $SQL);
while(odbc_fetch_row($RS)){print odbc_result($RS, 1);}?>';
	if (eval(JE_HEADER_ID)<=0){
	JE_RPT_URL = JE_RPT_URL+'&JE_ID='+eval(PAYMENT_ID)+'&CheckNumber='+document.getElementById("txtChequeNum").value.trim();	
	}else{
	JE_RPT_URL = JE_RPT_URL+'&JE_ID='+eval(JE_HEADER_ID)+'&CheckNumber='+document.getElementById("txtChequeNum").value.trim();
	}
	
	window.open(JE_RPT_URL, '_blank');
	}

}



<!--------------------------------------------------------------------- -->
function btnJEActionPerformed(){
	var JE_RPT_URL ='<?php
	$SQL = "SELECT    'http://'|| NVL (REPORT_LINK, 'INVALID_APP_HOST')|| '/IIS_WEB/".$RPT_FOLDER."/journal_voucher/Journal_voucher.php?PBCommandParm=101&JE_ID=' FROM PHARCO_REPORTS_MASTER WHERE REPORT_CATEGORY = 'APP_HOST' AND REPORT_LINK IS NOT NULL AND ROWNUM = 1";
$RS  = odbc_exec($CONNECT , $SQL);
while(odbc_fetch_row($RS)){print odbc_result($RS, 1);}?>';
if (eval(JE_HEADER_ID)<=0){
	//JE_RPT_URL = JE_RPT_URL+'&JE_ID='+eval(PAYMENT_ID)+'&CheckNumber='+document.getElementById("txtChequeNum").value.trim();	
	JE_RPT_URL = JE_RPT_URL+'&JE_ID='+eval(PAYMENT_ID);
}else{
    JE_RPT_URL = JE_RPT_URL+JE_HEADER_ID;	}
	//JE_RPT_URL = JE_RPT_URL+JE_HEADER_ID;
	window.open(JE_RPT_URL, '_blank');
}
<!--------------------------------------------------------------------- -->
function btnPostActionPerformed(){
	if (confirm('You could not undo [POST] action. Are you sure?')){
	if ((ActiveTab=='JE')&&(JE_HEADER_ID==0)&&(frmMode==2)){		
		POST_JE();
	}else{
		alert('The journal entry need to be reviewed or it might be posted before.\n Please, be sure.');
	}
	}
}
<!--------------------------------------------------------------------- -->
function btnReversePerformed(){
	if (confirm('You could not undo [REVERSE] action. Are you sure?')){
	if ((eval(JE_HEADER_ID)>0)&&(frmMode==2)){		
		REVERSE_JE();
	}else{
		alert('Please, make sure if the journal entry was posted before or not.');
	}
	}
}
<!--------------------------------------------------------------------- -->
function btnSaveActionPerformed(event){
	
	if (!VaildateHeader()){return;}
	
	if (ActiveTab=='JE'){
		var tJE = $('#recJEGrid').DataTable();
		if (tJE.data().count()){
			if (!ValidateJELinesErrors()){return;}		
			//if (!VaildateSumofAmt()){return;}
			if (Math.trunc(eval(document.getElementById("JEVariance").value.replace(/,/gi, ''))) != 0){
			alert('Debit not equal Credit');
			return;	
			}
		}
	}
	
	
	
	if (frmMode==1){
		UPDATE_DATA_NEW();
		return;		
	}
				
	if (frmMode==2){
		if (ActiveTab=='JE'){UPDATE_DATA(PAYMENT_ID ,BuildHeaderSQL(),'',BuildGLJELines());}
		if (ActiveTab=='PM'){UPDATE_DATA(PAYMENT_ID ,BuildHeaderSQL(),BuildMSQL(),'');}	
		
	}
	event.stopPropagation();
}
<!--------------------------------------------------------------------- -->
function btnNewActionPerformed(){
	//
	frmMode = 1;
    PAYMENT_ID = -1;
	EditJE_TYPE = '';
	STATUS_CODE_V='';
	JE_HEADER_ID = 0;
    ValidationPass = 0;
    ClearPaymentsVoucherControls();
    ClearPaymentsDetailsControls();
    EnableDisableButtons();
	//
	var d = new Date().toISOString().slice(0,10).replace(/-/g,"");
	document.getElementById("txtDocDate").value = d;
	document.getElementById("txtGLDATE").value = d;

	//to be removed in production
	document.getElementById("btnPost").disabled = false;
}
<!--------------------------------------------------------------------- -->
function ClearPaymentsVoucherControls(){
	EnableDisableDateFields();
	//
	var myTable = $('#recJEGrid').DataTable();
 	myTable
		.clear()
		.draw();
	//
	var oForm = document.getElementById("AP_FORM");
	var frm_elements = oForm.elements;
	for (i = 0; i < frm_elements.length; i++){
		field_type = frm_elements[i].type.toLowerCase();
		switch (field_type)
		{
		case "text":
		case "password":
		case "textarea":
		case "hidden":
			frm_elements[i].value = "";
			break;		
		case "select-one":
		case "select-multi":
			frm_elements[i].selectedIndex = 0;
			break;
		default:
			break;
		}
	}
	//
	document.getElementById("cboSHIP").innerHTML='';
	document.getElementById("cboMI").innerHTML='';
	document.getElementById("cboCurr").value = "EGP";
	GET_BANK_ACCOUNTS_LOV('EGP','');
	document.getElementById("invje").value = '';
	document.getElementById("txtCurrRate").value = 1;
	document.getElementById("txtAmount").value = 0;
	document.getElementById("dr").value = 0;
	document.getElementById("cr").value = 0;
	//
	document.getElementById("defaultOpen").click();
		
}
<!--------------------------------------------------------------------- -->
function ClearPaymentsDetailsControls(){
	var myTable = $('#recRCTGrid').DataTable();
 	myTable
		.clear()
		.draw();
		
	var myTable1 = $('#recAPPGrid').DataTable();
 	myTable1
		.clear()
		.draw();	
}

<!--------------------------------------------------------------------- -->
function EnableDisableButtons(){
	document.getElementById("cboWAT").disabled=false;
	document.getElementById("vendorid").disabled=false;
	document.getElementById("cboirrsal").disabled=false;
	document.getElementById("cboVAT").disabled=false;
	document.getElementById("cboSAL").disabled=false;
	document.getElementById("search_vend").disabled=false;
	document.getElementById("invje").readOnly=false;
	document.getElementById("dr").readOnly=false;
	document.getElementById("cr").readOnly=false;
	document.getElementById("accnumid").readOnly=false;
	document.getElementById("search_acc").disabled=false;
	document.getElementById("ccnumid").readOnly=false;
	document.getElementById("search_cc").disabled=false;
	document.getElementById("create_m_je").disabled=false;
	EditJE_TYPE='';
	if (STATUS_CODE_V =='POSTED'){
		document.getElementById("btnPost").disabled = true;
		document.getElementById("btnAPRV").disabled = true;
		document.getElementById("btnPAY").disabled = true;
        document.getElementById("btnSave").disabled = true;
	}
	if (STATUS_CODE_V =='NEW'){
		document.getElementById("btnPost").disabled = false;
		document.getElementById("btnAPRV").disabled = false;
		document.getElementById("btnPAY").disabled = true;
        document.getElementById("btnSave").disabled = false;
	}
	if (STATUS_CODE_V =='APPROVE'){
		document.getElementById("btnPost").disabled = true;
		document.getElementById("btnAPRV").disabled = true;
		document.getElementById("btnPAY").disabled = false;
        document.getElementById("btnSave").disabled = true;
	}
	if (STATUS_CODE_V =='PAY'){
		document.getElementById("btnPost").disabled = false;
		document.getElementById("btnAPRV").disabled = true;
		document.getElementById("btnPAY").disabled = true;
        document.getElementById("btnSave").disabled = true;
	}
	if (STATUS_CODE_V ==''){
		document.getElementById("btnPost").disabled = true;
		document.getElementById("btnAPRV").disabled = true;
		document.getElementById("btnPAY").disabled = true;
        document.getElementById("btnSave").disabled = false;
	}
	return;
		if (frmMode == 1) {
            document.getElementById("btnDelete").disabled = true;
            //document.getElementById("btnNew").disabled = true;
            //document.getElementById("btnPD").disabled = true;
            //document.getElementById("btnPV").disabled = true;
			//document.getElementById("btnJE").disabled = true;
			document.getElementById("create_m_je").disabled = false;
            //document.getElementById("btnPC").disabled = true;
            document.getElementById("btnPost").disabled = true;
			document.getElementById("btnAPRV").disabled = true;
			document.getElementById("btnPAY").disabled = true;
            document.getElementById("btnSave").disabled = false;
        }
        if (frmMode == 0) {
            document.getElementById("btnDelete").disabled = true;
            document.getElementById("btnNew").disabled = false;
            //document.getElementById("btnPD").disabled = true;
            //document.getElementById("btnPV").disabled = true;
			//document.getElementById("btnJE").disabled = true;
            //document.getElementById("btnPC").disabled = true;
            document.getElementById("btnPost").disabled = true;
			document.getElementById("btnAPRV").disabled = true;
			document.getElementById("btnPAY").disabled = true;
            document.getElementById("btnSave").disabled = false;
        }
		if (frmMode == 2) {
            document.getElementById("btnDelete").disabled =false;
            document.getElementById("btnNew").disabled =false;
            //document.getElementById("btnPD").disabled =false;
            document.getElementById("btnPV").disabled =false;
            //document.getElementById("btnPC").disabled =false;
            document.getElementById("btnPost").disabled =false;
            document.getElementById("btnSave").disabled =false;
        }
        if (frmMode == 3) {
            document.getElementById("btnDelete").disabled =true;
            document.getElementById("btnNew").disabled =false;
            //document.getElementById("btnPD").disabled =true;
            document.getElementById("btnPV").disabled =true;
            //document.getElementById("btnPC").disabled =true;
            document.getElementById("btnPost").disabled =true;
            document.getElementById("btnSave").disabled =true;
        }
		
        if (JE_HEADER_ID == 0) {
            document.getElementById("btnPV").disabled =true;
			document.getElementById("btnJE").disabled = true;
            //document.getElementById("btnPC").disabled =true;
        } else {
            document.getElementById("btnPV").disabled =false;
            //document.getElementById("btnPC").disabled =false;
			document.getElementById("btnJE").disabled = false;
        }
}
<!--------------------------------------------------------------------- -->
function EnableDisableDateFields(){
	// frmMode = 0; // 1=New Insert     2=Edit      3=Search
        if (frmMode == 0) {
            document.getElementById("txtDocDate").disabled =true;
            document.getElementById("txtChequeDate").disabled =true;
            document.getElementById("txtInvDate").disabled =true;
        } else {
            document.getElementById("txtDocDate").disabled =false;
            document.getElementById("txtChequeDate").disabled =false;
            document.getElementById("txtInvDate").disabled =false;
        }
}
<!--------------------------------------------------------------------- -->
function LoadDefaults() {
GET_CHEQUE_SIGNS();
GET_SUBINVENTORY_PERIOD();
GET_SUBINVENTORIES();
GET_PAY_TYPES();
GET_PO_JE_LIST('0');
//GET_LOOKUP("transtype","transtype");
GET_LOOKUP("get_exp","cboEXP");
GET_LOOKUP("get_major","cboMJ");
GET_LOOKUP("get_wat","cboWAT");
GET_LOOKUP("get_vat","cboVAT");
GET_LOOKUP("get_sal","cboSAL");
GET_LOOKUP("get_sal","M_SAL_TAX");
GET_LOOKUP("get_vat","M_VAT");
GET_LOOKUP("get_wat","M_WAT");
GET_LOOKUP("get_M_GL_VENDOR","M_GL_VENDOR");
GET_LOOKUP("get_M_GL_CC","M_GL_CC");
GET_LOOKUP("get_M_GL_VENDOR","M_GL_PENALTY");
GET_LOOKUP("get_M_GL_CC","M_GL_CC_PENALTY");
GET_LOOKUP("get_PENALTY","M_PENALTY_P");



 // Get the element with id="defaultOpen" and click on it
document.getElementById("defaultOpen").click();
//
EnableDisableButtons();

//
<?php
if (!isset($uid)||(trim($uid)=='')){
	print "document.getElementById(\"status1\").innerHTML = '<td colspan=\"2\"><div class=\"alert-box errormsg\">No user logged-in. Please re-log in.</div></td>';";
}
?>
}

 

<!--------------------------------------------- TABS -->
function openAP(evt, tabName) {
  ActiveTab=tabName;
  var i, tabcontent, tablinks;
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }
  document.getElementById(tabName).style.display = "block";
  evt.currentTarget.className += " active";
  
  
  $($.fn.dataTable.tables(true)).DataTable()
  .columns.adjust();
  
//	 
}

</script>
<!--------------------------------------------- AP_APP_MAIN_AJAX -->
<?php require('AP_APP_MAIN_AJAX.php'); ?>
<!--------------------------------------------- AP_APP_VOUCHER_AJAX -->
<?php require('AP_APP_VOUCHER_AJAX.php'); ?>
<!--------------------------------------------- AP_APP_MATCH_AJAX -->
<?php require('AP_APP_MATCH_AJAX.php'); ?>
<!--------------------------------------------- AP_APP_JE_AJAX -->
<?php require('AP_APP_JE_AJAX.php'); ?>
<!--------------------------------------------- AP_APP_APPLY_PREPAYMENT_AJAX -->
<?php require('AP_APP_APPLY_PREPAYMENT_AJAX.php'); ?>
<!--------------------------------------------- AP_PS_DTL_AJAX -->
<?php require('AP_PS_DTL_AJAX.php'); ?>
<!--------------------------------------------- AP_TAX_DATA_AJAX -->
<?php require('AP_TAX_DATA_AJAX.php'); ?>

</head>
<body  onLoad="LoadDefaults();" >
<form name="AP_FORM" id="AP_FORM" class="frmcss">
<?php require('AP_APP_TOOLBAR.php'); ?>
<?php require('AP_APP_MAIN.php'); ?>

<fieldset>
<fieldset class="tab">
<button type="button" class="tablinks" onclick="openAP(event, 'PM')" id="defaultOpen">Match PO/Receipt</button>
<button type="button" class="tablinks" onclick="openAP(event, 'PREAPP')" id="PREAPPOpen">Apply Prepayment</button>
<button type="button" class="tablinks" onclick="openAP(event, 'JE')" id="JEOpen">Journal Entry</button>
<button type="button" class="tablinks" onclick="openAP(event, 'PV')" id="PVOpen">Payment Cheque</button>
</fieldset>
<?php require('AP_APP_MATCH.php'); ?>
<?php require('AP_APP_APPLY_PREPAYMENT.php'); ?>
<?php require('AP_APP_JE.php'); ?>
<?php require('AP_APP_VOUCHER.php'); ?>

</fieldset>
</form>

<p>
<div style="clear: both;border-top: 1px solid #cccccc;font-size: 8pt;color: #cccccc;padding: 10px 10px 10px 10px;margin-top: 0px;">&copy;2020 Pharco Corporation - Information and Decision Support Division - IT Financial
</div>
</body>
</html>
