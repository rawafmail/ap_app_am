<?php
//-- MAIN VARIABLES
$uid = $_POST["uid"];
$action = $_POST["action"];
$ORG_ID = $_POST["ORG_ID"];
$LEDGER_ID = $_POST["LEDGER_ID"];
$LE = $_POST["LE"];
$PAYMENT_ID =trim($_POST["PAYMENT_ID"]);
$HEADER_DATA= iconv('utf-8', 'windows-1256', $_POST["HEADER_DATA"]);
$M_DETAIL_DATA =$_POST["M_DETAIL_DATA"];
$JE_DATA =$_POST["JE_DATA"];
//-- DATABASE CONNECTION 
$CONNECT = odbc_connect('PROD', 'apps', 'apps');
?>
<?php 
/*<!---------------------------------------------------------------------------------------------> */
  
if (isset($action) && ($action =="UPDATE_CHEQUE_DATA")) {
header('Content-Type:text/html; charset=windows-1256');

$SQL    = odbc_prepare($CONNECT, 'CALL AP_APPS.AP_APP_PKG.UPDATE_CHEQUE_DATA(?,?,?)');
$success = odbc_execute($SQL, array($ORG_ID,$PAYMENT_ID,$HEADER_DATA));
if (!$success) {
	print(odbc_error($CONNECT).": ".odbc_errormsg($CONNECT)."\n");		
}else{
	print 'Cheque Data Updated';
}
$RS  = odbc_exec($CONNECT , 'COMMIT;');


odbc_close($CONNECT);
exit();
}
?>
<?php 
/*<!---------------------------------------------------------------------------------------------> */
  
if (isset($action) && ($action =="PROCESS_CHEQUE")) {
header('Content-Type:text/html; charset=windows-1256');



$SQL    = odbc_prepare($CONNECT, 'CALL AP_APPS.AP_APP_PKG.PROCESS_CHEQUE(?,?)');
$success = odbc_execute($SQL, array($PAYMENT_ID,$uid));
if (!$success) {
	print(odbc_error($CONNECT).": ".odbc_errormsg($CONNECT)."\n");		
}
$RS  = odbc_exec($CONNECT , 'COMMIT;');


odbc_close($CONNECT);
exit();
}
?>
<?php  
//<!---------------------------------------------------------------------------------------------> 
if (isset($action) && ($action =="GET_CHEQUE_SIGNS")) {
	
header('Content-Type:text/html; charset=windows-1256');	
$SQL = "SELECT NVL(LOOKUP_CODE,'-'),NVL(DESCRIPTION,'-') FROM AP_APPS.FND_LOOKUP_VALUES WHERE LOOKUP_TYPE = 'PHARCO APPROVAL SIGN' AND LANGUAGE = 'AR' AND LOOKUP_CODE IN(2,10,7,11,17)" ;

$RS  = odbc_exec($CONNECT , $SQL);
print "<option value='0'>--</option>";
while(odbc_fetch_row($RS)){
print   "<option value='".odbc_result($RS,2)."'>".odbc_result($RS, 2)." </option>";
}


//-- CLOSE DATABASE CONNECTION
odbc_close($CONNECT);
exit();
}
?>