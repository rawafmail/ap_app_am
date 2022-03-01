<?php
//-- MAIN VARIABLES
$uid = $_POST["uid"];
$action = $_POST["action"];

$ORGANIZATION_ID = $_POST["ORGANIZATION_ID"];
$ORG_ID = $_POST["ORG_ID"];
$COMPANY = $_POST["COMPANY"];
$PAYMENT_ID = $_POST["PAYMENT_ID"];
$LE = $_POST["LE"];
$CURR_CODE = $_POST["CURR_CODE"];
$PRE_PAYMENT_ID = $_POST["PREPAYMENT_ID"];
$APPY_TO_ID = $_POST["APPY_TO_ID"];
$APPY_AMT = $_POST["APPY_AMT"];
$APPLICATION_ID =$_POST["APPLICATION_ID"];





//-- DATABASE CONNECTION 
$CONNECT = odbc_connect('PROD', 'apps', 'apps');
?>
<?php 
/*<!---------------------------------------------------------------------------------------------> */

 
if (isset($action) && ($action =="APPLY_PREPAYMENT_AUTO")) {
header('Content-Type:text/html; charset=windows-1256');

$SQL    = odbc_prepare($CONNECT, 'CALL AP_APPS.APPLY_PREPAYMENT_AUTO(?,?)');
$success = odbc_execute($SQL, array(trim($PAYMENT_ID),trim($uid)));	
$err_msg='';
$err_count=0;
if (!$success) {
	$err_count=1;
	$err_msg =$err_msg ."<div class=\"alert-box errormsg\">". odbc_error($CONNECT).": ".odbc_errormsg($CONNECT)."</div>";		
}


if ($err_count > 0){print $err_msg;}

odbc_close($CONNECT);
exit();
}
?>

<?php 
/*<!---------------------------------------------------------------------------------------------> */

 
if (isset($action) && ($action =="UNAPPLY_PREPAYMENT")) {
header('Content-Type:text/html; charset=windows-1256');

//ORG_ID_V NUMBER,PREPAYMENT_ID_V NUMBER,APPLIED_PAYMENT_LINE_ID NUMBER,PAYMENT_ID_V NUMBER, APPLIED_AMOUNT NUMBER,USER_ID NUMBER
$SQL    = odbc_prepare($CONNECT, 'CALL AP_APPS.UNAPPLY_PREPAYMENT(?,?)');
$success = odbc_execute($SQL, array($APPLICATION_ID,$uid));	
$err_msg='';
$err_count=0;
if (!$success) {
	$err_count=1;
	$err_msg =$err_msg ."<div class=\"alert-box errormsg\">". odbc_error($CONNECT).": ".odbc_errormsg($CONNECT)."</div>";		
}

if ($err_count==0){print 'UNAPPLIED';}


if ($err_count > 0){print $err_msg;}

odbc_close($CONNECT);
exit();
}
?>

<?php 
/*<!---------------------------------------------------------------------------------------------> */

 
if (isset($action) && ($action =="APPLY_PREPAYMENT")) {
header('Content-Type:text/html; charset=windows-1256');

//ORG_ID_V NUMBER,PREPAYMENT_ID_V NUMBER,APPLIED_PAYMENT_LINE_ID NUMBER,PAYMENT_ID_V NUMBER, APPLIED_AMOUNT NUMBER,USER_ID NUMBER
$SQL    = odbc_prepare($CONNECT, 'CALL AP_APPS.APPLY_PREPAYMENT(?,?,?,?,?,?)');
$success = odbc_execute($SQL, array(trim($ORG_ID),trim($PRE_PAYMENT_ID),trim($APPY_TO_ID),trim($PAYMENT_ID),trim($APPY_AMT),trim($uid)));	
$err_msg='';
$err_count=0;
if (!$success) {
	$err_count=1;
	$err_msg =$err_msg ."<div class=\"alert-box errormsg\">". odbc_error($CONNECT).": ".odbc_errormsg($CONNECT)."</div>";		
}

if ($err_count==0){
$SQL = "SELECT  REPLACE(A.LINE_DESCRIPTION,CHR(13),'<br>')LINE_DESCRIPTION1,REPLACE(B.DESCRIPTION,CHR(13),'<br>')DESCRIPTION,A.* FROM AP_APPS.APPLIED_PREPAYMENTS_DETAILS A INNER JOIN AP_APPS.AP_PREPAY_VENDORS_LOV B ON A.PAYMENT_ID=B.PAYMENT_ID AND A.PRE_PAYMENT_ID =B.PRE_PAYMENT_ID AND A.TO_PAYMENT_LINE_ID=".$APPY_TO_ID." WHERE  A.PAYMENT_ID = ".$PAYMENT_ID." AND A.PRE_PAYMENT_ID = ".$PRE_PAYMENT_ID;
$RS  = odbc_exec($CONNECT , $SQL);
$rowcount =0;
while(odbc_fetch_row($RS)){
$rowcount=$rowcount+1;
print   odbc_result($RS, "DESCRIPTION")."</td>";
print   odbc_result($RS, "LINE_DESCRIPTION1")."</td>";
print   odbc_result($RS, "AMOUNT")."</td>";
print   '<input id="btnunapply" type="button" value="Un-Apply" onclick="javascript:UNAPPLY_PREPAYMENT('.odbc_result($RS, "HISTORY_ID").','.$rowcount.');" /></td>';
print   odbc_result($RS, "HISTORY_ID")."</td>";
print   '</tr>';
}	
}

if ($err_count > 0){print $err_msg;}

odbc_close($CONNECT);
exit();
}
?>

<?php  
//<!---------------------------------------------------------------------------------------------> 
if (isset($action) && ($action =="GET_PREPAY_VENDORS_LOV")) {
	
header('Content-Type:text/html; charset=windows-1256');	
$SQL = "SELECT PRE_PAYMENT_ID,DESCRIPTION FROM AP_APPS.AP_PREPAY_VENDORS_LOV WHERE  PAYMENT_ID = ".$PAYMENT_ID." AND CURRENCY_CODE = '".$CURR_CODE."'";

$RS  = odbc_exec($CONNECT , $SQL);
print "<option value='0'>--</option>";
while(odbc_fetch_row($RS)){
print   "<option value='".odbc_result($RS, 1)."'>".odbc_result($RS, 2)." </option>";
}

//-- CLOSE DATABASE CONNECTION
odbc_close($CONNECT);
exit();
}
?>
<?php  
//<!---------------------------------------------------------------------------------------------> 
if (isset($action) && ($action =="GET_TRANS_APPLICATIONS_LOV")) {
	
header('Content-Type:text/html; charset=windows-1256');	
$SQL = "SELECT PAYMENT_LINE_ID,'[Remain: '||AMOUNT_REMAIN||']-'||LINE_DESCRIPTION FROM AP_APPS.AP_TRANS_APPLICATIONS_SUM WHERE  PAYMENT_ID = ".$PAYMENT_ID." AND VENDOR_ID IN (SELECT VENDOR_ID FROM AP_APPS.AP_PREPAY_VENDORS_LOV WHERE  PRE_PAYMENT_ID = ".$PRE_PAYMENT_ID.")";

$RS  = odbc_exec($CONNECT , $SQL);
print "<option value='0'>--</option>";
while(odbc_fetch_row($RS)){
print   "<option value='".odbc_result($RS, 1)."'>".odbc_result($RS, 2)." </option>";
}

//-- CLOSE DATABASE CONNECTION
odbc_close($CONNECT);
exit();
}
?>