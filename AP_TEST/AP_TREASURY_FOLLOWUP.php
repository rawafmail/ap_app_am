<!---------------------------------------------------------------------------------------------> 
<?php
print  '<meta http-equiv="Content-type" content="text/html; charset=windows-1256" />';//-- VARIABLES
$ORGANIZATION_ID = 112;
$ORG_ID = 91 ;
$LEDGER_ID = 2023 ;

$sid = $_REQUEST["sid"];
//-- DATABASE CONNECTION 
$CONNECT = odbc_connect('PROD', 'apps', 'apps');
?>
<!--
=================================================================================================
=================================================================================================
|||||||||||||||||||||| AJAX PHP  ||||||||||||||||||||||||||||||||||||||||||||||| START  |||||||||
=================================================================================================
=================================================================================================
-->
<?php
//\\|||||||||||||||||||||| AJAX DATABASE CALL BACK ----------- START
// get the q parameter from URL
$CashAccount = $_REQUEST["CashAccount"];
$Status = $_REQUEST["Status"];
$action = $_POST["action"];
$uid = $_REQUEST["uid"];
$page_num = $_REQUEST["page_num"];
$PAYMENT_ID = $_REQUEST["PAYMENT_ID"];
$NEW_Status = $_REQUEST["NEW_Status"];
$CH_NUM = $_REQUEST["CH_NUM"];
//$DescriptionTxt = iconv('utf-8', 'windows-1256', $DescriptionTxt);
?>




<!---------------------------------------------------------------------------------------------> 
<?php
if (isset($action) && ($action =="saverow")) {
header('Content-Type:text/html; charset=windows-1256');	
//header('Content-Type:text/html; charset=utf-8');	
//print  '<meta http-equiv="Content-type" content="text/html; charset=windows-1256" />'; 
?>
<?php  
//-- PROCESS DATA
//-- ADD TO DB

if ($NEW_Status == "Approve"){
$SQL    = odbc_prepare($CONNECT, 'CALL AP_APPS.UPDATE_PAYMENT_STATUS(?,?,?,?,?)');
$success = odbc_execute($SQL, array($PAYMENT_ID,$uid,'APPROVE',$LEDGER_ID,$JENUM));	

if (!$success) {
	print(odbc_errormsg($CONNECT)."\n");		
}else{
	print "<img src='../Lib/images/Cute-Ball-Go-icon.png' /><b> Approval DONE.</b><hr> ";
	print '<input name="result_code" id="result_code" type="hidden" value="'.odbc_num_rows($res).'">';
}

}

if ($NEW_Status == "POST"){

$SQL = "UPDATE AP_APPS.AP_APP_PAYMENTS_HEADERS SET CHEQUE_NUM = $CH_NUM  WHERE PAYMENT_ID = $PAYMENT_ID ";
$RS  = odbc_exec($CONNECT , $SQL);
ODBC_EXEC($CONNECT , 'COMMIT');
//print $SQL;
//print $CH_NUM;
$SQL    = odbc_prepare($CONNECT, 'CALL AP_APPS.UPDATE_PAYMENT_STATUS(?,?,?,?,?)');
$success = odbc_execute($SQL, array($PAYMENT_ID,$uid,'POSTED',$LEDGER_ID,$JENUM));	




if (!$success) {
	print(odbc_errormsg($CONNECT)."\n");		
}else{
	print "<img src='../Lib/images/Cute-Ball-Go-icon.png' /><b> Post DONE.</b><hr> ";
	print '<input name="result_code" id="result_code" type="hidden" value="1">';
}


}


?>
<!---------------------------------------------------------------------------------------------> 
<?php  
//-- CLOSE DATABASE CONNECTION
odbc_close($CONNECT);
?>
<!---------------------------------------------------------------------------------------------> 
<?php
exit();
}
?>

<!---------------------------------------------------------------------------------------------> 
<?php
if (isset($action) && ($action =="getdata_details")) {
header('Content-Type:text/html; charset=windows-1256');	
?>
<?php  
//-- GET DATA
//-- getdata_details

$max_row_num = ABS($page_num) * 10;
$min_row_num = $max_row_num - 9;


$SQL = "SELECT * FROM(
		SELECT A.* ,ROWNUM AS R
		FROM(
		SELECT A.PAYMENT_ID,
				A.PAYMENT_DOC_NUM,
				TO_CHAR(A.PAYMENT_DOC_DATE,'YYYY-MM-DD'),
				A.PAYEE_NAME,
				A.BANK_ACCOUNT_NUM,
				A.SUM_AMOUNT,
				A.NOTES,
				NVL(B.CURRENCY_CONVERSION_RATE,A.CURRENCY_CONVERSION_RATE) AS CURRENCY_CONVERSION_RATE,
				NVL(B.CURRENCY_CODE,'-') AS CURRENCY_CODE,
				A.STATUS_CODE,
				A.GL_JE_NAME,
				B.NAME,
				A.POST_DATE,
				A.CHEQUE_NUM				
		FROM AP_APPS.AP_APP_PAYMENTS_HEADERS A
		LEFT OUTER JOIN GL.GL_JE_HEADERS B ON A.JE_HEADER_ID = B.JE_HEADER_ID
		WHERE  A.CASH_FLAG=1 AND  A.BANK_ACCOUNT_NUM ='".$CashAccount."'";
		
if ($Status!='ALL'){$SQL =$SQL ." AND A.STATUS_CODE = '".$Status."'";}		
		
$SQL =$SQL ." AND A.ORG_ID = ".$ORG_ID."
		ORDER BY A.PAYMENT_DOC_NUM DESC
		)A
		) WHERE R >= ".$min_row_num." AND R <= ".$max_row_num;
		
if (ABS($page_num)==0){
$SQL = "SELECT * FROM(
		SELECT A.* ,ROWNUM AS R
		FROM(
		SELECT A.PAYMENT_ID,
				A.PAYMENT_DOC_NUM,
				TO_CHAR(A.PAYMENT_DOC_DATE,'YYYY-MM-DD'),
				A.PAYEE_NAME,
				A.BANK_ACCOUNT_NUM,
				A.SUM_AMOUNT,
				A.NOTES,
				NVL(B.CURRENCY_CONVERSION_RATE,A.CURRENCY_CONVERSION_RATE) AS CURRENCY_CONVERSION_RATE,
				NVL(B.CURRENCY_CODE,'-') AS CURRENCY_CODE,
				A.STATUS_CODE,
				A.GL_JE_NAME,
				B.NAME,
				A.POST_DATE,
				A.CHEQUE_NUM			
		FROM AP_APPS.AP_APP_PAYMENTS_HEADERS A
		LEFT OUTER JOIN GL.GL_JE_HEADERS B ON A.JE_HEADER_ID = B.JE_HEADER_ID
		WHERE  A.CASH_FLAG=1 AND A.BANK_ACCOUNT_NUM ='".$CashAccount."'";
		
if ($Status!='ALL'){$SQL =$SQL ." AND A.STATUS_CODE = '".$Status."'";}		
		
$SQL =$SQL ." AND A.ORG_ID = ".$ORG_ID."
		ORDER BY A.PAYMENT_DOC_NUM DESC
		)A
		) ";	
}	

	
$res = odbc_exec($CONNECT, $SQL);

if (!$res) {
	print("SQL statement failed with error: Update 1.1\n");
	print(odbc_error($CONNECT).": ".odbc_errormsg($CONNECT)."\n");		
}



while(odbc_fetch_row($res)){
print '<tr>';
print '<td align="right">'.odbc_result($res, 14).' - <input type="checkbox" name="PAYMENT_ID[]" id="PAYMENT_ID[]" value="'.odbc_result($res, 1).'" /></td>';	
print '<td><a href="http://'.$_SERVER['SERVER_NAME'].'/IIS_WEB/AP_PAY_DOC/print_cash.php?uid='.$uid.'&sid='.$sid.'&VoucherNumber='.odbc_result($res, 1).'&Ledger_id='.$LEDGER_ID.'&s='.$CashAccount.'">'.odbc_result($res, 2).'</a></td>';
print '<td>'.odbc_result($res, 3).'</td>';
print '<td>'.odbc_result($res, 4).'</td>';
print '<td>'.odbc_result($res, 5).'</td>';
print '<td>'.odbc_result($res, 6).'</td>';
print '<td>'.odbc_result($res, 7).'</td>';
print '<td>'.odbc_result($res, 8).'</td>';
print '<td>'.odbc_result($res, 9).'</td>';
print '<td>'.odbc_result($res, 10).'</td>';
print '<td>'.odbc_result($res, 11).'</td>';
print '<td>'.odbc_result($res, 12).'</td>';
print '<td>'.odbc_result($res, 13).'</td>';
print '<td>'.'<input type="text" name="CH_NUM[]" id="CH_NUM[]" value="'.odbc_result($res, 14).'"/> '.'</td>';
print '<td>-</td>';
print '</tr>';
}
?>
<!---------------------------------------------------------------------------------------------> 
<?php  
//-- CLOSE DATABASE CONNECTION
odbc_close($CONNECT);
?>
<!---------------------------------------------------------------------------------------------> 
<?php
exit();
}
?>
<!---------------------------------------------------------------------------------------------> 
<?php
if (isset($action) && ($action =="get_paging")) {
header('Content-Type:text/html; charset=windows-1256');	
?>
<?php  
//-- GET DATA
//-- getdata_details

$SQL = "SELECT NVL((SELECT CEIL(MAX(rownum)/10) FROM AP_APPS.AP_APP_PAYMENTS_HEADERS 
		WHERE CASH_FLAG=1 AND BANK_ACCOUNT_NUM ='".$CashAccount."'";
		
if ($Status!='ALL'){$SQL =$SQL ." AND STATUS_CODE = '".$Status."'";}		
		
$SQL =$SQL ." 
		AND ORG_ID = ".$ORG_ID."),0) FROM DUAL";
$res = odbc_exec($CONNECT, $SQL);

if (!$res) {
	print("SQL statement failed with error: Update 1.1\n");
	print(odbc_error($CONNECT).": ".odbc_errormsg($CONNECT)."\n");		
}

while(odbc_fetch_row($res)){
	if (odbc_result($res, 1) == 0){
		$f_row = 0;
	}else{
		$f_row = 1;
	}
	print '<tr>';
	print '    <th>Page #</th>';
	print '    <th><input name="pcurrent" id="pcurrent" type="text" disabled value="'.$f_row.'" readonly></th>';
	print '    <th>of</th>';
	print '    <th><input name="ptotal" id="ptotal" type="text" disabled value="'.odbc_result($res, 1).'" readonly></th>';
	print '    <th><input name="First" id="First" type="button" value="First" ></th>';
	print '    <th><input name="previous" id="previous" type="button" value="Previous"></th>';
	print '    <th><input name="next" id="next" type="button" value="Next" ></th>';
	print '    <th><input name="last" id="last" type="button" value="Last" ></th>';
	print '    <th><input name="all" id="all" type="button" value="View All Records" ></th>';
	print '  </tr>';
	
	
}
?>
<!---------------------------------------------------------------------------------------------> 
<?php  
//-- CLOSE DATABASE CONNECTION
odbc_close($CONNECT);
?>
<!---------------------------------------------------------------------------------------------> 
<?php
exit();
}
?>
<!---------------------------------------------------------------------------------------------> 
<?php
//\\|||||||||||||||||||||| AJAX DATABASE CALL BACK ----------- END
?>
<!--
=================================================================================================
=================================================================================================
|||||||||||||||||||||| AJAX PHP  ||||||||||||||||||||||||||||||||||||||||||||||| END    |||||||||
=================================================================================================
=================================================================================================
-->
<?php //require('../lib/serheader.php'); ?>
<style type="text/css">
fieldset {
    border: 2px solid #1F497D;
    background: #ddd;
    border-radius: 5px;
    padding: 8px;
}


table.imagetable {
	font-family: verdana,arial,sans-serif;
	font-size:14px;
	color:#333333;
	border-width: 1px;
	border-color: #999999;
	border-collapse: collapse;
	
}

		
table.imagetable th {
	background:#b5cfd2 url('../Libfn/images/cell-blue.jpg');
	border-width: 1px;
	padding: 8px;
	border-style: solid;
	border-color: #999999;
	
}
table.imagetable td {
	background:#dcddc0 url('../Libfn/images/cell-grey.jpg');
	border-width: 1px;
	padding: 8px;
	border-style: solid;
	border-color: #999999;
	
}
</style>
<!--
=================================================================================================
=================================================================================================
|||||||||||||||||||||| AJAX JAVASCRIPT ||||||||||||||||||||||||||||||||||||||||| START  |||||||||
=================================================================================================
=================================================================================================
-->
<script>

<!---------------------------------------------------------------------------------------------> 		
function save_rows1(){
	//save_rows();	
	var uid =  "<?php print $_REQUEST["uid"]; ?>";
	var myparams = "";
	//get_data_details();
	var save = document.getElementById("save").value;
	var table = document.getElementById('tblDtails');
	var rowCount = table.rows.length;
	var Status = document.getElementById("Status").value;
	//var CH_NUM = document.getElementById("CH_NUM").value;
	var pcurrent = eval(document.getElementById("pcurrent").value);
	
	for(var i=1; i<rowCount; i++) {
		var row = table.rows[i];
		var PAYMENT_ID = row.cells[0].childNodes[1];			
		var CH_NUM = row.cells[13].childNodes[0].value;
		if ( PAYMENT_ID.checked ){
			myparams="uid="+uid+"&PAYMENT_ID="+PAYMENT_ID.value+"&CH_NUM="+CH_NUM+"&Status="+Status+"&NEW_Status="+save+"&action=saverow";
			save_row(myparams);
		}
	}
	get_data_details(-1);
}
<!---------------------------------------------------------------------------------------------> 		
function selectall(){
	var selectall1 = document.getElementById("select_all"); 
	var table = document.getElementById('tblDtails');
	var rowCount = table.rows.length;
	
	if ( selectall1.checked ){ 
		for(var i=1; i<rowCount; i++) {
			var row = table.rows[i];
			var PAYMENT_ID = row.cells[0].childNodes[1];
			PAYMENT_ID.checked = true;
		}
	}else{
		for(var i=1; i<rowCount; i++) {
			var row = table.rows[i];
			var PAYMENT_ID = row.cells[0].childNodes[1];
			PAYMENT_ID.checked = false;
		}
	}
	
	
}
<!---------------------------------------------------------------------------------------------> 
function save_row(params){
    // Create our XMLHttpRequest object
    var hr = new XMLHttpRequest();
    // Create some variables we need to send to our PHP file
    var url = "AP_TREASURY_FOLLOWUP.php";
    
	
	hr.open("POST", url, true);
	// Set content type header information for sending url encoded variables in the request
	//hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	//hr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=utf-8');
	hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded; charset=utf-8");
	hr.setRequestHeader("Content-length", params.length);
	hr.setRequestHeader("Connection", "close");
	// Access the onreadystatechange event for the XMLHttpRequest object
	hr.onreadystatechange = function() {
		if(hr.readyState == 4 && hr.status == 200) {
			var return_data = hr.responseText;
			document.getElementById("status").innerHTML = return_data;
			/*
			if ((document.getElementById("result_code").value == "")||(!document.getElementById("result_code").value >= 1)){
				alert("Error has been occurred");
				document.getElementById("return_code").value = 0;
			}else{
				document.getElementById("return_code").value = 1;
			}
			*/
		}
	}
			// Send the data to PHP now... and wait for response to update the status div
			hr.send(params); // Actually execute the request
			document.getElementById("status").innerHTML = "Processing...";	    
}
<!---------------------------------------------------------------------------------------------> 		
		
function navigation(btn_val){
	var pcurrent = eval(document.getElementById("pcurrent").value);
	var ptotal = eval(document.getElementById("ptotal").value);
		
	var new_p_num = 0;
	document.getElementById("status").innerHTML = "";
	if (ptotal >= 1){
		switch(btn_val) {
		case "First":
			new_p_num = 1;
			break;
		case "Previous":
			if (pcurrent > 1){ new_p_num = pcurrent - 1;}
			if (pcurrent == 1){return;}
			break;
		case "Next":
			if (pcurrent == ptotal){return;}
			new_p_num = pcurrent + 1;
			break;		
		case "Last":
			if (pcurrent == ptotal){return;}
			new_p_num = ptotal;
			break;
		case "ALL":
			if (pcurrent < 1){return;}
			var First = document.getElementById("First");
			var previous = document.getElementById("previous");
			var next = document.getElementById("next");
			var last = document.getElementById("last");
			First.disabled = true;
			previous.disabled = true;
			next.disabled = true;
			last.disabled = true;
			new_p_num = 0;
			document.getElementById("pcurrent").value = 1;
			document.getElementById("ptotal").value = 1;
			get_data_details(new_p_num);
			return;
			break;				
		default: 
			new_p_num = 0;
		} 
	}
	document.getElementById("pcurrent").value = new_p_num;
	get_data_details(new_p_num);
}
<!---------------------------------------------------------------------------------------------> 		
		
function get_data_details(page_num){
	clearAll();
	if (eval(page_num)==-1){	
	get_paging();
	document.getElementById("status").innerHTML = "";
	}
    // Create our XMLHttpRequest object
    var hr = new XMLHttpRequest();
    // Create some variables we need to send to our PHP file
    var url = "AP_TREASURY_FOLLOWUP.php";
    var CashAccount = document.getElementById("CashAccount").value;
	var Status = document.getElementById("Status").value;
	
    var vars = "page_num="+page_num+"&CashAccount="+CashAccount+"&Status="+Status+"&action=getdata_details";
	
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
			document.getElementById("getdata_details").innerHTML = return_data;
	    }
    }
    // Send the data to PHP now... and wait for response to update the status div
    hr.send(vars); // Actually execute the request
    document.getElementById("getdata_details").innerHTML = "Processing...";
}				
<!---------------------------------------------------------------------------------------------> 
function get_paging(){
	
    // Create our XMLHttpRequest object
    var hr = new XMLHttpRequest();
    // Create some variables we need to send to our PHP file
    var url = "AP_TREASURY_FOLLOWUP.php";
    var CashAccount = document.getElementById("CashAccount").value;
	var Status = document.getElementById("Status").value;
	
	
    var vars = "CashAccount="+CashAccount+"&Status="+Status+"&action=get_paging";
	
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
			document.getElementById("tblPaging").innerHTML = return_data;
			var First = document.getElementById("First")
			var previous = document.getElementById("previous")
			var next = document.getElementById("next")
			var last = document.getElementById("last")
			var all_r = document.getElementById("all")
			
			First.addEventListener('click', function () {navigation('First'); }, false);
			previous.addEventListener('click', function () {navigation('Previous'); }, false);
			next.addEventListener('click', function () {navigation('Next'); }, false);
			last.addEventListener('click', function () {navigation('Last'); }, false);
			all_r.addEventListener('click', function () {navigation('ALL'); }, false);
	    }
    }
    // Send the data to PHP now... and wait for response to update the status div
    hr.send(vars); // Actually execute the request
    document.getElementById("getdata_details").innerHTML = "Processing...";
}
<!---------------------------------------------------------------------------------------------> 
		
function clearAll() {
	
			try {
			var selectall1 = document.getElementById("select_all"); 	
			var table = document.getElementById('tblDtails');
			var rowCount = table.rows.length;
			var Status = document.getElementById("Status").value;
			var save = document.getElementById("save");
			if ( selectall1.checked ){selectall1.checked = false;}
			
			if (Status == "NEW"){
				save.value = "Approve";
				save.disabled = false;
			}else if (Status == "PAY"){
				save.value = "POST";
				save.disabled = false;
			}else{
				save.value = "Save";
				save.disabled = true;
			}
			
			for(var i=2; i<rowCount; i++) {
				table.deleteRow(i);
				rowCount--;
				i--;
			}
			
						
			}catch(e) {
				alert(e);
			}
		}
<!---------------------------------------------------------------------------------------------> 

</script>
<!--
=================================================================================================
=================================================================================================
|||||||||||||||||||||| AJAX JAVASCRIPT  |||||||||||||||||||||||||||||||||||||||| END    |||||||||
=================================================================================================
=================================================================================================
-->




<fieldset>
<form>
<fieldset style="background-color:#F5F6CE">
<legend>Search</legend>
<table border="0" style="font-family: verdana,arial,sans-serif;font-size:14px;color:#333333;">
<tr>
<td colspan="10"  id="status"></td>
</tr>
<tr>
<td colspan="10" ><input name="return_code" id="return_code" type="hidden" value=""></td>
</tr>

<tr>
  <td><b>Organization:</b></td>
  <td>
    <select name="LEDGER_ID" id="LEDGER_ID">
      <option value="2023">-- AMR--</option>
    </select></td>
  
<td><b>Cash Account:</b></td>
<td>
<select name="CashAccount" id="CashAccount" style="width:200px;" onChange="javascript:get_data_details(-1);">
<option value='-1'>---- SELECT ----- </option>
<?php
$SQL ="select FLEX_VALUE,DESCRIPTION FROM AP_APPS.AP_APP_BANK_ACCOUNTS                     ORDER BY FLEX_VALUE ";
$RS  = odbc_exec($CONNECT , $SQL);
while(odbc_fetch_row($RS)){
print   "<option value='".odbc_result($RS, 1)."'>".odbc_result($RS, 1)."     |  ".odbc_result($RS, 2)." </option>";
}
?>
</select>
</td>
<td><b>Status:</b></td>
<td>
<select name="Status" id="Status" style="width:200px;" onChange="javascript:get_data_details(-1);">
<option value='-1'>---- SELECT ----- </option>
<option value='ALL'>---- ALL ----- </option>
<option value='POSTED'>-- POSTED --</option>
<option value='NEW'>-- NEW --</option>
<option value='APPROVE'>-- APPROVE --</option>
<option value='PAY'>-- PAY --</option>
<?php
/*
$SQL ="select DISTINCT STATUS_CODE  from  AP_APPS.AP_APP_PAYMENTS_HEADERS where LEDGER_ID = ".$LEDGER_ID ;
$RS  = odbc_exec($CONNECT , $SQL);
while(odbc_fetch_row($RS)){
print   "<option value='".odbc_result($RS, 1)."'>-- ".odbc_result($RS, 1)." --</option>";
}
*/
?>
</select>
</td>
</tr>
<tr>
<td colspan="10" id="prod_description"></td>
</tr>

</table>
       
</fieldset>
<br>
<fieldset  style="background-color:#F5F6CE">
<legend>Details</legend>
<table id="tblPaging"  cellspacing="3px" cellpadding="3px">
 
</table>

<table  class="imagetable" id="tblDtails" width="100%" >
<col style="width:7%">
<col style="width:5%">
<col style="width:5%">
<col style="width:10%">
<col style="width:5%">
<col style="width:5%">
<col style="width:23%">
<col style="width:5%">
<col style="width:5%">
<col style="width:5%">
<col style="width:5%">
<col style="width:8%">
<col style="width:9%">
<col style="width:9%">
<col style="width:3%">


<thead>
<tr  style="border-style: outset;background-color:#F5F6CE;">

<th><b>ID #</b><br><input name="select_all" id="select_all" type="checkbox" onClick="javascript:selectall()"></th>
<th><b>Document #</b></th>
<th><b>Document<br>Date</b></th>
<th><b>Payee Name</b></th>
<th><b>Cash #</b></th>
<th><b>Amount</b></th>
<th><b>Notes</b></th>
<th><b>Currency<br>Rate</b></th>
<th><b>Currency<br>Code</b></th>
<th><b>Status</b></th>
<th><b>Payment #</b></th>
<th><b>Journal #</b></th>
<th><b>Post<br>Date</b></th>
<th><b>Document<br>Num</b></th>
<th><b><input name="save" id="save" type="button" value="Save" onClick="javascript:save_rows1()" ></b></th>
</tr>
</thead>
<tbody id="getdata_details" ></tbody>
</table>


     
</fieldset>
</form>
</fieldset> 
<!---------------------------------------------------------------------------------------------> 

<?php  
//-- CLOSE DATABASE CONNECTION
odbc_close($CONNECT);
 ?>
<?php //require('../lib/serfooter.php');?>
<p>
<div style="clear: both;border-top: 1px solid #cccccc;font-size: 8pt;color: #cccccc;padding: 10px 10px 10px 10px;margin-top: 0px;">&copy;2020 Pharco Corporation - Information and Decision Support Division - IT Financial
</div>
	
