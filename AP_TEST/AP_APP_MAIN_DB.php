<?php
session_start();
set_time_limit(50000);
ini_set('max_input_vars', 50000);
//ini_set('post_max_size','16M');
header("Expires: 0");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
//-- MAIN VARIABLES
$uid = $_POST["uid"];
$action = $_POST["action"];
$ORG_ID = $_POST["ORG_ID"];
$LEDGER_ID = $_POST["LEDGER_ID"];
$LE = $_POST["LE"];
$PAYMENT_ID =trim($_POST["PAYMENT_ID"]);
$HEADER_DATA= iconv('utf-8', 'windows-1256', $_POST["HEADER_DATA"]);
$search_item = $_POST["search_item"];
$search_item_1= iconv('utf-8', 'windows-1256', $_POST["search_item"]);

$JENUM =trim($_POST["JENum"]);
$JETYPE =trim($_POST["JEType"]);

//--
$SEARCH_PAYMETHOD = $_POST["search_PAYMETHOD"];
$SEARCH_PAYACCOUNT = $_POST["search_PAYACCOUNT"];
$SEARCH_FDATE = $_POST["search_FDATE"];
$SEARCH_FDATE = $_POST["search_FDATE"];
$SEARCH_JENUM = $_POST["search_JENUM"];
$SEARCH_PAYMENT = $_POST["search_PAYMENT"];
$SEARCH_PAYEE = $_POST["search_PAYEE"];
$SEARCH_NOTE = $_POST["search_NOTE"];
$SEARCH_AMOUNT = $_POST["search_AMOUNT"];
$SEARCH_NOTE= iconv('utf-8', 'windows-1256', $SEARCH_NOTE);
$SEARCH_PAYEE= iconv('utf-8', 'windows-1256', $SEARCH_PAYEE);
$SEARCH_STATUS = $_POST["search_STATUS"];
//--


//$HEADER_DATA =$_POST["HEADER_DATA"];
$M_DETAIL_DATA =$_POST["M_DETAIL_DATA"];
$JE_DATA =$_POST["JE_DATA"];
$CURR_CODE =$_POST["CURR_CODE"];

//-- DATABASE CONNECTION 
$CONNECT = odbc_connect('PROD', 'apps', 'apps');
?>
<?php
//<!---------------------------------------------------------------------------------------------> 
if (isset($action) && ($action =="SEARCH_PAYEE_GET_RESULT")) {
$COUNT_MYRS = 0;	
header('Content-Type:text/html; charset=windows-1256');
print '<table class="Design6" border="1" cellspacing="0" cellpadding="0" id="items_results1">';
print '<thead style="color:#fff">';
print '<tr>';
print '<th width="25%">Code</th>';
print '<th width="39%">Name</th>';
print '<th>Account</th>';
print '</tr>';
print '</thead>';

$sql = "SELECT NVL(A.VENDOR_ID,A.PAYEE_ID), A.VENDOR_NAME, A.ACCOUNT_NUMBER
FROM AP_APPS.AP_PAYEES_VW A
WHERE  NVL(A.END_DATE_E,SYSDATE+1) > SYSDATE
 AND A.ORG_ID=  ".$ORG_ID." and (A.VENDOR_CODE LIKE '".$search_item."' OR UPPER(A.VENDOR_NAME) LIKE UPPER('".$search_item_1."') )
order by A.VENDOR_CODE";

$res = odbc_exec($CONNECT, $sql);
print '<tbody>';
while(odbc_fetch_row($res)){
	$COUNT_MYRS = $COUNT_MYRS + 1;
	print '<tr>';
	print '<td style="background:#FFFFFF; width:25% "><a href="#" onclick="javascript:SELECT_PAYEE(';
	print "'".odbc_result($res, 1)."',";
	print "'".odbc_result($res, 2)."'";
	print ')">'.odbc_result($res, 1).'</a></td>';
	print '<td style="background:#FFFFFF; width:40% ">'.odbc_result($res, 2).'</td>';
	print '<td style="background:#FFFFFF; ">'.odbc_result($res, 3).'</td>';
	print '</tr>';
}
print '</tbody>';
print '</table>';

if ($COUNT_MYRS==0){
	print '<h3 class="blink_me" style="color:red">no data found for your search criteria ... !</h3>';
}
//-- CLOSE DATABASE CONNECTION
odbc_close($CONNECT);

exit();
}
?>

<?php
//<!---------------------------------------------------------------------------------------------> 
if (isset($action) && ($action =="SEARCH_PAYMENT_GET_RESULT")) {
$SEARCH_WHERE ='';
if (isset($SEARCH_PAYMETHOD)&&($SEARCH_PAYMETHOD!=-1)){$SEARCH_WHERE = $SEARCH_WHERE." AND CASH_FLAG = ".$SEARCH_PAYMETHOD;}
if (isset($SEARCH_PAYACCOUNT)&&($SEARCH_PAYACCOUNT!='0')){$SEARCH_WHERE = $SEARCH_WHERE." AND BANK_ACCOUNT_NUM = '".$SEARCH_PAYACCOUNT."'";}
if (isset($SEARCH_FDATE)&&($SEARCH_FDATE!='')){$SEARCH_WHERE = $SEARCH_WHERE." AND TO_CHAR(PAYMENT_DOC_DATE,'YYYYMMDD') >= '".$SEARCH_FDATE."'";}
if (isset($SEARCH_TDATE)&&($SEARCH_TDATE!='')){$SEARCH_WHERE = $SEARCH_WHERE." AND TO_CHAR(PAYMENT_DOC_DATE,'YYYYMMDD') <= '".$SEARCH_TDATE."'";}
if (isset($SEARCH_PAYMENT)&&($SEARCH_PAYMENT!='')){$SEARCH_WHERE = $SEARCH_WHERE." AND PAYMENT_DOC_NUM = '".$SEARCH_PAYMENT."'";}
if (isset($SEARCH_AMOUNT)&&($SEARCH_AMOUNT!=0)){$SEARCH_WHERE = $SEARCH_WHERE." AND SUM_AMOUNT = ".$SEARCH_AMOUNT;}
if (isset($SEARCH_PAYEE)&&($SEARCH_PAYEE!='')){$SEARCH_WHERE = $SEARCH_WHERE." AND NVL(PAYEE_NAME,'-') LIKE '".$SEARCH_PAYEE."'";}
if (isset($SEARCH_NOTE)&&($SEARCH_NOTE!='')){$SEARCH_WHERE = $SEARCH_WHERE." AND NVL(NOTES,'-') LIKE '".$SEARCH_NOTE."'";}
if (isset($SEARCH_STATUS)&&($SEARCH_STATUS!='A')&&($SEARCH_STATUS!='')){$SEARCH_WHERE = $SEARCH_WHERE." AND STATUS_CODE = '".$SEARCH_STATUS."'";}
if (isset($SEARCH_JENUM)&&($SEARCH_JENUM!='')){$SEARCH_WHERE = $SEARCH_WHERE." AND NVL(B.NAME,'-') LIKE '".$SEARCH_JENUM."'";}




$COUNT_MYRS = 0;	
header('Content-Type:text/html; charset=windows-1256');
print '<table border="1" cellspacing="0" cellpadding="0" id="items_results">';
print '<thead>';
print '<tr>';
print '<th width="130px">Transaction</th>';
print '<th width="90px">Date</th>';
print '<th width="30%">Payee</th>';
print '<th width="20%">Payment JE</th>';
print '<th width="20%">Reverse JE</th>';
print '</tr>';
print '</thead>';

$sql = "SELECT A.PAYMENT_ID,A.PAYMENT_DOC_NUM,TO_CHAR(A.PAYMENT_DOC_DATE,'YYYY-MM-DD')PAYMENT_DOC_DATE,A.PAYEE_NAME,B.NAME JE,C.NAME RJE FROM AP_APPS.AP_APP_PAYMENTS_HEADERS A LEFT OUTER JOIN GL.GL_JE_HEADERS B ON A.JE_HEADER_ID = B.JE_HEADER_ID LEFT OUTER JOIN GL.GL_JE_HEADERS C ON NVL(A.REVERSE_JE_HEADER_ID,0) = C.JE_HEADER_ID  WHERE A.ORG_ID =".$ORG_ID.$SEARCH_WHERE. " ORDER BY A.PAYMENT_DOC_NUM DESC";

$res = odbc_exec($CONNECT, $sql);

print '<tbody>';
while(odbc_fetch_row($res)){
	$COUNT_MYRS = $COUNT_MYRS + 1;
	print '<tr>';
	print '<td style="background:#FFFFFF; width:125px "><a href="#" onclick="javascript:searchForEditHeader('.odbc_result($res, "PAYMENT_ID").')">';
	print odbc_result($res, "PAYMENT_DOC_NUM").'</a></td>';
	print '<td style="background:#FFFFFF; width:90px ">'.odbc_result($res, "PAYMENT_DOC_DATE").'</td>';
	print '<td style="background:#FFFFFF; width:30% ">'.odbc_result($res, "PAYEE_NAME").'</td>';
	print '<td style="background:#FFFFFF; width:20% ">'.odbc_result($res, "JE").'</td>';
	print '<td style="background:#FFFFFF; width:20% ">'.odbc_result($res, "RJE").'</td>';
	print '</tr>';
}
print '</tbody>';
print '</table>';

if ($COUNT_MYRS==0){
	print '<h3 class="blink_me" style="color:red">no data found for your search criteria ... !</h3>';
}
//-- CLOSE DATABASE CONNECTION
odbc_close($CONNECT);

exit();
}
?>
<?php 
/*<!---------------------------------------------------------------------------------------------> */

 
if (isset($action) && ($action =="searchForEditPrePayApp")) {
header('Content-Type:text/html; charset=windows-1256');

$SQL = "SELECT REPLACE(A.LINE_DESCRIPTION,CHR(13),'<br>')LINE_DESCRIPTION1,REPLACE(B.DESCRIPTION,CHR(13),'<br>')DESCRIPTION,A.* FROM AP_APPS.APPLIED_PREPAYMENTS_DETAILS A INNER JOIN AP_APPS.AP_APP_PRE_PAYMENTS_VW B ON A.PRE_PAYMENT_ID =B.PRE_PAYMENT_ID  WHERE  A.PAYMENT_ID = ".$PAYMENT_ID;
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


odbc_close($CONNECT);
exit();
}
?>
<?php  
/*<!---------------------------------------------------------------------------------------------> */
if (isset($action) && ($action =="searchForEditJE")) {
header('Content-Type:text/html; charset=windows-1256');
$SQL="SELECT * FROM AP_APPS.AP_APP_PAYMENTS_JE_VW WHERE PAYMENT_ID=".$PAYMENT_ID;

$ROWCOUNT=0;
$RS  = odbc_exec($CONNECT , $SQL);
if (!$RS) {
	print(odbc_errormsg($CONNECT)."\n".$SQL);
}

while(odbc_fetch_row($RS)){
$ROWCOUNT = $ROWCOUNT+1;
print '<input type="text" id="GLACCOUNT" value="'.odbc_result($RS, "GLACCOUNT").'"   ondblclick="EDIT_JENTRY(this.parentNode.parentNode.rowIndex)" onKeyDown="javascript:HandleGridJEKeyEvent(event,this.parentNode.parentNode.rowIndex,\''.odbc_result($RS, "JE_TYPE").'\');" readonly="readonly" /></td>';
print '<input type="text" value="'.odbc_result($RS, "COSTCENTER").'"   ondblclick="EDIT_JENTRY(this.parentNode.parentNode.rowIndex)" onKeyDown="javascript:HandleGridJEKeyEvent(event,this.parentNode.parentNode.rowIndex,\''.odbc_result($RS, "JE_TYPE").'\');" readonly="readonly" /></td>';
print '<input type="text" value="'.odbc_result($RS, "ACCT").'"   ondblclick="EDIT_JENTRY(this.parentNode.parentNode.rowIndex)" onKeyDown="javascript:HandleGridJEKeyEvent(event,this.parentNode.parentNode.rowIndex,\''.odbc_result($RS, "JE_TYPE").'\');" readonly="readonly" /></td>';
print '<input type="text" value="'.odbc_result($RS, "CCT").'"   ondblclick="EDIT_JENTRY(this.parentNode.parentNode.rowIndex)" onKeyDown="javascript:HandleGridJEKeyEvent(event,this.parentNode.parentNode.rowIndex,\''.odbc_result($RS, "JE_TYPE").'\');" readonly="readonly" /></td>';
print '<input type="text" value="'.number_format(odbc_result($RS, "DR_AMT"),2,".",",").'" style="text-align:right"   ondblclick="EDIT_JENTRY(this.parentNode.parentNode.rowIndex)" onKeyDown="javascript:HandleGridJEKeyEvent(event,this.parentNode.parentNode.rowIndex,\''.odbc_result($RS, "JE_TYPE").'\');" readonly="readonly" /></td>';
print '<input type="text" value="'.number_format(odbc_result($RS, "CR_AMT"),2,".",",").'" style="text-align:right"   ondblclick="EDIT_JENTRY(this.parentNode.parentNode.rowIndex)" onKeyDown="javascript:HandleGridJEKeyEvent(event,this.parentNode.parentNode.rowIndex,\''.odbc_result($RS, "JE_TYPE").'\');" readonly="readonly" /></td>';
print '<input type="text" value="'.number_format(odbc_result($RS, "DR_AMT_ACC"),2,".",",").'" style="text-align:right"   ondblclick="EDIT_JENTRY(this.parentNode.parentNode.rowIndex)" onKeyDown="javascript:HandleGridJEKeyEvent(event,this.parentNode.parentNode.rowIndex,\''.odbc_result($RS, "JE_TYPE").'\');" readonly="readonly" /></td>';
print '<input type="text" value="'.number_format(odbc_result($RS, "CR_AMT_ACC"),2,".",",").'" style="text-align:right"   ondblclick="EDIT_JENTRY(this.parentNode.parentNode.rowIndex)"  onKeyDown="javascript:HandleGridJEKeyEvent(event,this.parentNode.parentNode.rowIndex,\''.odbc_result($RS, "JE_TYPE").'\');" readonly="readonly" /></td>';
print '<input type="hidden" id="LINE_TYPE_DFF" 		value="'.odbc_result($RS, "LINE_TYPE_DFF").'"  />';
print '<input type="hidden" id="PAYMENT_ID" 			value="'.odbc_result($RS, "PAYMENT_ID").'"  />';
print '<input type="hidden" id="WAT_TAX_RATE" 		value="'.odbc_result($RS, "WAT_TAX_RATE").'"  />';
print '<input type="hidden" id="IR_SAL_TAX_AMT" 		value="'.odbc_result($RS, "IR_SAL_TAX_AMT").'"  />';
print '<input type="hidden" id="VALUE_ADD_TAX_RATE" 	value="'.odbc_result($RS, "VALUE_ADD_TAX_RATE").'"  />';
print '<input type="hidden" id="PENALTY_AMOUNT" 		value="'.odbc_result($RS, "PENALTY_AMOUNT").'"  />';
print '<input type="hidden" id="VENDOR_CODE" 			value="'.odbc_result($RS, "VENDOR_CODE").'"  />';
print '<input type="hidden" id="PO_NUM" 				value="'.odbc_result($RS, "PO_NUM").'"  />';
print '<input type="hidden" id="INVOICE_NUM" 			value="'.odbc_result($RS, "INVOICE_NUM").'"  />';
print '<input type="hidden" id="RECEIPT_NUM" 			value="'.odbc_result($RS, "RECEIPT_NUM").'"  />';
print '<input type="hidden" id="MATCH_TYPE_ID" 		value="'.odbc_result($RS, "MATCH_TYPE_ID").'"  />';
print '<input type="hidden" id="JE_TYPE" 				value="'.odbc_result($RS, "JE_TYPE").'"  />';
print '<input type="hidden" id="JE_LINE_NUM" 	value="'.odbc_result($RS, "JE_LINE_NUM").'"  />';
print '<input type="hidden" id="JE_HEADER_ID" value="'.odbc_result($RS, "JE_HEADER_ID").'"  />';
print '<input type="hidden" id="SHIPMENT_NUM" value="'.odbc_result($RS, "SHIPMENT_NUM").'"  />';
print '<input type="hidden" id="EXPENSE_TYPE" value="'.odbc_result($RS, "EXPENSE_TYPE").'"  />';
print '<input type="hidden" id="MAJOR_CODE" 	value="'.odbc_result($RS, "MAJOR_CODE").'"  />';
print '<input type="hidden" id="MINOR_CODE" 	value="'.odbc_result($RS, "MINOR_CODE").'"  />';
print '<input type="hidden" id="SALES_R_FLAG" 	value="'.odbc_result($RS, "SALES_R_FLAG").'"  />';
print '<input type="hidden" id="SALES_TAX_RATE" 	value="'.odbc_result($RS, "SALES_TAX_RATE").'"  />';
print '<input type="hidden" id="PAY_SCHEDULE_ID" 	value="'.odbc_result($RS, "PAY_SCHEDULE_ID").'"  />';
print '<input type="hidden" id="ERR_NUM" 	value="'.odbc_result($RS, "ERR_NUM").'"  />';
print '<input type="hidden" id="ERR_MSG" 	value="'.odbc_result($RS, "ERR_MSG").'"  /></td>';
print odbc_result($RS, "ERR_NUM").'</td>';
print odbc_result($RS, "ERR_MSG").'</td>';
print '</tr>';

}
//-- CLOSE DATABASE CONNECTION
odbc_close($CONNECT);
exit();
}
?>

<?php  
/*<!---------------------------------------------------------------------------------------------> */
if (isset($action) && ($action =="searchForEditLines")) {
header('Content-Type:text/html; charset=windows-1256');
$SQL = "SELECT * FROM AP_APPS.AP_APP_PAYMENTS_DETAILS_VW WHERE PAYMENT_ID =".$PAYMENT_ID;

$ROWCOUNT=0;
$RS  = odbc_exec($CONNECT , $SQL);
while(odbc_fetch_row($RS)){

	
$ROWCOUNT = $ROWCOUNT+1;
//print '<tr>';
print '<input type="text"  id="MATCH_INV_NUM"  value="'.odbc_result($RS, "INVOICE_NUM").'" onKeyDown="javascript:HandleGridMatchKeyEvent(event,this.parentNode.parentNode.rowIndex);" ondblclick="EDIT_PRICE_OVERLAY(this.parentNode.parentNode.rowIndex)" readonly="readonly" /></td>';
print '<input type="text" value="'.odbc_result($RS, "INVOICE_DATE").'" onKeyDown="javascript:HandleGridMatchKeyEvent(event,this.parentNode.parentNode.rowIndex);" ondblclick="EDIT_PRICE_OVERLAY(this.parentNode.parentNode.rowIndex)" readonly="readonly" /></td>';
print '<input type="text" value="'.odbc_result($RS, "QTY").'" onKeyDown="javascript:HandleGridMatchKeyEvent(event,this.parentNode.parentNode.rowIndex);" ondblclick="EDIT_PRICE_OVERLAY(this.parentNode.parentNode.rowIndex)" readonly="readonly" /></td>';
print '<input type="text" value="'.odbc_result($RS, "UOM_CODE").'" onKeyDown="javascript:HandleGridMatchKeyEvent(event,this.parentNode.parentNode.rowIndex);" ondblclick="EDIT_PRICE_OVERLAY(this.parentNode.parentNode.rowIndex)" readonly="readonly" /></td>';
print '<input type="text" value="'.odbc_result($RS, "U_COST").'" onKeyDown="javascript:HandleGridMatchKeyEvent(event,this.parentNode.parentNode.rowIndex);" ondblclick="EDIT_PRICE_OVERLAY(this.parentNode.parentNode.rowIndex)" readonly="readonly" /></td>';
print '<input type="text" value="'.odbc_result($RS, "U_PRICE").'" onKeyDown="javascript:HandleGridMatchKeyEvent(event,this.parentNode.parentNode.rowIndex);" ondblclick="EDIT_PRICE_OVERLAY(this.parentNode.parentNode.rowIndex)" readonly="readonly" /></td>';
print '<input type="text" value="'.odbc_result($RS, "PO_UNIT_PRICE").'" onKeyDown="javascript:HandleGridMatchKeyEvent(event,this.parentNode.parentNode.rowIndex);" ondblclick="EDIT_PRICE_OVERLAY(this.parentNode.parentNode.rowIndex)" readonly="readonly" /></td>';
print '<input type="text" value="'.odbc_result($RS, "PRICE_VAR").'" onKeyDown="javascript:HandleGridMatchKeyEvent(event,this.parentNode.parentNode.rowIndex);" ondblclick="EDIT_PRICE_OVERLAY(this.parentNode.parentNode.rowIndex)" readonly="readonly" /></td>';
print '<input type="text" value="'.odbc_result($RS, "INV_VALUE").'" onKeyDown="javascript:HandleGridMatchKeyEvent(event,this.parentNode.parentNode.rowIndex);" ondblclick="EDIT_PRICE_OVERLAY(this.parentNode.parentNode.rowIndex)" readonly="readonly" /></td>';
print '<input type="text" value="'.odbc_result($RS, "SALES_TAX_RATE").'" onKeyDown="javascript:HandleGridMatchKeyEvent(event,this.parentNode.parentNode.rowIndex);" ondblclick="EDIT_PRICE_OVERLAY(this.parentNode.parentNode.rowIndex)" readonly="readonly" /></td>';
print '<input type="text" value="'.odbc_result($RS, "SALES_R_FLAG").'" onKeyDown="javascript:HandleGridMatchKeyEvent(event,this.parentNode.parentNode.rowIndex);" ondblclick="EDIT_PRICE_OVERLAY(this.parentNode.parentNode.rowIndex)" readonly="readonly" /></td>';
print '<input type="text" value="'.odbc_result($RS, "SAL_TAX_VAL").'" onKeyDown="javascript:HandleGridMatchKeyEvent(event,this.parentNode.parentNode.rowIndex);" ondblclick="EDIT_PRICE_OVERLAY(this.parentNode.parentNode.rowIndex)" readonly="readonly" /></td>';
print '<input type="text" value="'.odbc_result($RS, "VALUE_ADD_TAX_RATE").'" onKeyDown="javascript:HandleGridMatchKeyEvent(event,this.parentNode.parentNode.rowIndex);" ondblclick="EDIT_PRICE_OVERLAY(this.parentNode.parentNode.rowIndex)" readonly="readonly" /></td>';
print '<input type="text" value="'.odbc_result($RS, "VAT_VAL").'" onKeyDown="javascript:HandleGridMatchKeyEvent(event,this.parentNode.parentNode.rowIndex);" ondblclick="EDIT_PRICE_OVERLAY(this.parentNode.parentNode.rowIndex)" readonly="readonly" /></td>';
print '<input type="text" value="'.odbc_result($RS, "WAT_TAX_RATE").'" onKeyDown="javascript:HandleGridMatchKeyEvent(event,this.parentNode.parentNode.rowIndex);" ondblclick="EDIT_PRICE_OVERLAY(this.parentNode.parentNode.rowIndex)" readonly="readonly" /></td>';
print '<input type="text" value="'.odbc_result($RS, "AWT_VAL").'" onKeyDown="javascript:HandleGridMatchKeyEvent(event,this.parentNode.parentNode.rowIndex);" ondblclick="EDIT_PRICE_OVERLAY(this.parentNode.parentNode.rowIndex)" readonly="readonly" /></td>';
print '<input type="text" value="'.odbc_result($RS, "PENALTY_RATE").'" onKeyDown="javascript:HandleGridMatchKeyEvent(event,this.parentNode.parentNode.rowIndex);" ondblclick="EDIT_PRICE_OVERLAY(this.parentNode.parentNode.rowIndex)" readonly="readonly" /></td>';
print '<input type="text" value="'.odbc_result($RS, "PENALTY_AMOUNT").'" onKeyDown="javascript:HandleGridMatchKeyEvent(event,this.parentNode.parentNode.rowIndex);" ondblclick="EDIT_PRICE_OVERLAY(this.parentNode.parentNode.rowIndex)" readonly="readonly" /></td>';
print '<input type="text" value="'.odbc_result($RS, "NET_AMOUNT").'" onKeyDown="javascript:HandleGridMatchKeyEvent(event,this.parentNode.parentNode.rowIndex);" ondblclick="EDIT_PRICE_OVERLAY(this.parentNode.parentNode.rowIndex)" readonly="readonly" /></td>';
print '<input type="text" value="'.odbc_result($RS, "A_RECEIPT_NUM").'" onKeyDown="javascript:HandleGridMatchKeyEvent(event,this.parentNode.parentNode.rowIndex);" ondblclick="EDIT_PRICE_OVERLAY(this.parentNode.parentNode.rowIndex)" readonly="readonly" /></td>';
print '<input type="text" value="'.odbc_result($RS, "M_RECEIPT_NUM").'" onKeyDown="javascript:HandleGridMatchKeyEvent(event,this.parentNode.parentNode.rowIndex);" ondblclick="EDIT_PRICE_OVERLAY(this.parentNode.parentNode.rowIndex)" readonly="readonly" /></td>';
print '<input type="text" value="'.odbc_result($RS, "ITEM").'" onKeyDown="javascript:HandleGridMatchKeyEvent(event,this.parentNode.parentNode.rowIndex);" ondblclick="EDIT_PRICE_OVERLAY(this.parentNode.parentNode.rowIndex)" readonly="readonly" /></td>';
print '<input type="text" value="'.odbc_result($RS, "VENDOR").'" onKeyDown="javascript:HandleGridMatchKeyEvent(event,this.parentNode.parentNode.rowIndex);" ondblclick="EDIT_PRICE_OVERLAY(this.parentNode.parentNode.rowIndex)" readonly="readonly" /></td>';
print '<input type="hidden" id="ORG_ID" value="'.odbc_result($RS, "ORG_ID").'"  />';
print '<input type="hidden" id="PO_HEADER_ID"  			value="'.odbc_result($RS, "PO_HEADER_ID").'"  />';
print '<input type="hidden" id="PO_LINE_ID"  				value="'.odbc_result($RS, "PO_LINE_ID").'"  />';
print '<input type="hidden" id="TRANSACTION_ID"  			value="'.odbc_result($RS, "RCV_TRANSACTION_ID").'"  />';
print '<input type="hidden" id="ITEM_ID"  				value="'.odbc_result($RS, "INVENTORY_ITEM_ID").'"  />';
print '<input type="hidden" id="INV_PERIOD"  				value="'.odbc_result($RS, "INV_PERIOD").'"  />';
print '<input type="hidden" id="VENDOR_CODE"  			value="'.odbc_result($RS, "VENDOR_CODE").'"  />';
print '<input type="hidden" id="MATCH_TYPE_ID"  			value="'.odbc_result($RS, "MATCH_TYPE_ID").'"  />';
print '<input type="hidden" id="GLACCOUNT"  				value="'.odbc_result($RS, "GLACCOUNT").'"  />';
print '<input type="hidden" id="COSTCENTER"  				value="'.odbc_result($RS, "COSTCENTER").'"  />';
print '<input type="hidden" id="PENALTY_GLACCOUNT"  		value="'.odbc_result($RS, "PENALTY_GLACCOUNT").'"  />';
print '<input type="hidden" id="PENALTY_GLCOSTCENTER"  	value="'.odbc_result($RS, "PENALTY_GLCOSTCENTER").'"  />';
print '<input type="hidden" id="PAYMENT_LINE_ID"  	value="'.odbc_result($RS, "PAYMENT_LINE_ID").'"  />';
print '<input type="hidden" id="PAYMENT_SCHEDULE_ID"  	value="'.odbc_result($RS, "PAYMENT_SCHEDULE_ID").'"  />';

print '</td>';
print odbc_result($RS, "PAYMENT_SCHEDULE_ID").odbc_result($RS, "PO_LINE_ID").odbc_result($RS, "TRANSACTION_ID").odbc_result($RS, "QUANTITY").'</td>';









print '</tr>';


}

//-- CLOSE DATABASE CONNECTION
odbc_close($CONNECT);
exit();
}
?>

<?php  
//<!---------------------------------------------------------------------------------------------> 
if (isset($action) && ($action =="searchForEditHeader")) {
	
header('Content-Type:text/html; charset=windows-1256');	
$SQL = "SELECT PAYMENT_ID,ORG_ID,PAYMENT_DOC_NUM,TO_CHAR(PAYMENT_DOC_DATE,'YYYYMMDD')PAYMENT_DOC_DATE,
PAYEE_NAME,BANK_ACCOUNT_NUM,CHEQUE_NUM,TO_CHAR(CHEQUE_MATURITY_DATE,'YYYYMMDD')CHEQUE_MATURITY_DATE,
NON_ENDORSEMENT,SUM_AMOUNT,WAT_RATE,APPROVE_SIGN1,APPROVE_SIGN2,NOTES,CURRENCY_CODE,CURRENCY_CONVERSION_RATE,
JE_HEADER_ID,PAYEE_ID,CASH_FLAG,VAT_RATE,USER_ID,CREATION_DATE,DELIVERY_FLAG,DELIVERY_DATE,DELIVERY_TO,POST_DATE,STATUS_CODE,TO_CHAR(NVL(GL_DATE,PAYMENT_DOC_DATE),'YYYYMMDD')GL_DATE
 FROM AP_APPS.AP_APP_PAYMENTS_HEADERS WHERE PAYMENT_ID = ".$PAYMENT_ID;
$RS  = odbc_exec($CONNECT , $SQL);
while(odbc_fetch_row($RS)){
print odbc_result($RS,"PAYMENT_ID").'</td>';
print odbc_result($RS,"ORG_ID").'</td>';
print odbc_result($RS,"PAYMENT_DOC_NUM").'</td>';
print odbc_result($RS,"PAYMENT_DOC_DATE").'</td>';
print odbc_result($RS,"PAYEE_NAME").'</td>';
print odbc_result($RS,"BANK_ACCOUNT_NUM").'</td>';
print odbc_result($RS,"CHEQUE_NUM").'</td>';
print odbc_result($RS,"CHEQUE_MATURITY_DATE").'</td>';
print odbc_result($RS,"NON_ENDORSEMENT").'</td>';
print odbc_result($RS,"SUM_AMOUNT").'</td>';
print odbc_result($RS,"WAT_RATE").'</td>';
print odbc_result($RS,"APPROVE_SIGN1").'</td>';
print odbc_result($RS,"APPROVE_SIGN2").'</td>';
print odbc_result($RS,"NOTES").'</td>';
print odbc_result($RS,"CURRENCY_CODE").'</td>';
print odbc_result($RS,"CURRENCY_CONVERSION_RATE").'</td>';
print odbc_result($RS,"JE_HEADER_ID").'</td>';
print odbc_result($RS,"PAYEE_ID").'</td>';
print odbc_result($RS,"CASH_FLAG").'</td>';
print odbc_result($RS,"VAT_RATE").'</td>';
print odbc_result($RS,"USER_ID").'</td>';
print odbc_result($RS,"CREATION_DATE").'</td>';
print odbc_result($RS,"DELIVERY_FLAG").'</td>';
print odbc_result($RS,"DELIVERY_DATE").'</td>';
print odbc_result($RS,"DELIVERY_TO").'</td>';
print odbc_result($RS,"POST_DATE").'</td>';
print odbc_result($RS,"STATUS_CODE").'</td>';
print odbc_result($RS,"GL_DATE").'</td>';
print '</tr>';
}


//-- CLOSE DATABASE CONNECTION
odbc_close($CONNECT);
exit();
}
?>
<?php  
//<!---------------------------------------------------------------------------------------------> 
if (isset($action) && ($action =="GET_SUBINVENTORY_PERIOD")) {
	
header('Content-Type:text/html; charset=windows-1256');	
$SQL = "SELECT TO_CHAR(START_DATE,'MON-YY','NLS_DATE_LANGUAGE=AMERICAN') FROM GMF_PERIOD_STATUSES WHERE LEGAL_ENTITY_ID = ".$LE."  ORDER BY START_DATE DESC";

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
if (isset($action) && ($action =="VaildateJEPostedBefore")) {
	
header('Content-Type:text/html; charset=windows-1256');	
$SQL = "SELECT NVL((SELECT JE_HEADER_ID FROM GL_JE_HEADERS WHERE GLOBAL_ATTRIBUTE1 = TO_CHAR('".$PAYMENT_ID."')),0) FROM DUAL";

$RS  = odbc_exec($CONNECT , $SQL);

while(odbc_fetch_row($RS)){
print odbc_result($RS, 1);
}


//-- CLOSE DATABASE CONNECTION
odbc_close($CONNECT);
exit();
}
?>


<?php  
//<!---------------------------------------------------------------------------------------------> 
if (isset($action) && ($action =="GET_BANK_ACCOUNTS_LOV")) {
	
header('Content-Type:text/html; charset=windows-1256');	
$SQL = "SELECT FLEX_VALUE, DESCRIPTION FROM AP_APPS.AP_APP_BANK_ACCOUNTS WHERE CURRENCY_CODE = '".$CURR_CODE."' ORDER BY FLEX_VALUE";
$RS  = odbc_exec($CONNECT , $SQL);
print '<option value="0">--</option>';

while(odbc_fetch_row($RS)){
print '<option value="'.odbc_result($RS,1) . '">'.odbc_result($RS,1).' - '.odbc_result($RS,2).'</option>';

}


//-- CLOSE DATABASE CONNECTION
odbc_close($CONNECT);
exit();
}
?>
<?php 
/*<!---------------------------------------------------------------------------------------------> */
  
if (isset($action) && ($action =="GET_POSTED_JE_NUMBER")) {
header('Content-Type:text/html; charset=windows-1256');

$SQL    = "SELECT JE_HEADER_ID,NAME FROM GL.GL_JE_HEADERS WHERE SUBSTR(GLOBAL_ATTRIBUTE1,1,9)= 'AP_APP_ID' AND TO_NUMBER(regexp_replace(GLOBAL_ATTRIBUTE1, '[^[:digit:]]', ''))=".$PAYMENT_ID;
$RS  = odbc_exec($CONNECT , $SQL);

if (!$RS) {
	print(odbc_errormsg($CONNECT)."\n");
	//print 'Err:-1';		
}

while(odbc_fetch_row($RS)){
print odbc_result($RS, 1).'</td>';
print odbc_result($RS, 2).'</td>';
}

odbc_close($CONNECT);
exit();
}
?>
<?php 
/*<!---------------------------------------------------------------------------------------------> */

 
if (isset($action) && ($action =="POST_JE")) {
header('Content-Type:text/html; charset=windows-1256');


$SQL    = odbc_prepare($CONNECT, 'CALL AP_APPS.UPDATE_PAYMENT_STATUS(?,?,?,?,?)');
$success = odbc_execute($SQL, array($PAYMENT_ID,$uid,'POSTED',$LEDGER_ID,$JENUM));	

if (!$success) {
	print(iconv('utf-8', 'windows-1256','تعذر إعادة إنشاء قيد السداد. قد يخالف هذا الإجراء إحدى لوائح العمل النشطة.'));
	print('Could not post Journal Entry. This action may violate an active business rule. Error Details:');
	print(odbc_errormsg($CONNECT)."\n");		
}


odbc_close($CONNECT);
exit();
}
?>
<?php 
/*<!---------------------------------------------------------------------------------------------> */

 
if (isset($action) && ($action =="PAY")) {
header('Content-Type:text/html; charset=windows-1256');


$SQL    = odbc_prepare($CONNECT, 'CALL AP_APPS.UPDATE_PAYMENT_STATUS(?,?,?,?,?)');
$success = odbc_execute($SQL, array($PAYMENT_ID,$uid,'PAY',$LEDGER_ID,$JENUM));	

if (!$success) {
	print(iconv('utf-8', 'windows-1256','تعذر إعادة إنشاء قيد السداد. قد يخالف هذا الإجراء إحدى لوائح العمل النشطة.'));
	print('Could not post Journal Entry. This action may violate an active business rule. Error Details:');
	print(odbc_errormsg($CONNECT)."\n");		
}else{
	print 'Transaction PAID  ';
	$SQL    = "SELECT GL_JE_NAME FROM AP_APPS.AP_APP_PAYMENTS_HEADERS WHERE PAYMENT_ID=".$PAYMENT_ID;
	$RS  = odbc_exec($CONNECT , $SQL);
	while(odbc_fetch_row($RS)){print odbc_result($RS, 1);}
}


odbc_close($CONNECT);
exit();
}
?>
<?php 
/*<!---------------------------------------------------------------------------------------------> */

 
if (isset($action) && ($action =="APPROVE")) {
header('Content-Type:text/html; charset=windows-1256');


$SQL    = odbc_prepare($CONNECT, 'CALL AP_APPS.UPDATE_PAYMENT_STATUS(?,?,?,?,?)');
$success = odbc_execute($SQL, array($PAYMENT_ID,$uid,'APPROVE',$LEDGER_ID,$JENUM));	

if (!$success) {
	print(iconv('utf-8', 'windows-1256','تعذر إعادة إنشاء قيد السداد. قد يخالف هذا الإجراء إحدى لوائح العمل النشطة.'));
	print('Could not post Journal Entry. This action may violate an active business rule. Error Details:');
	print(odbc_errormsg($CONNECT)."\n");		
}else{
	print 'Approval DONE';
}


odbc_close($CONNECT);
exit();
}
?>
<?php 
/*<!---------------------------------------------------------------------------------------------> */

 
if (isset($action) && ($action =="DELETE_POSTED_JE")) {
header('Content-Type:text/html; charset=windows-1256');


$SQL    = odbc_prepare($CONNECT, 'CALL AP_APPS.AP_APP_PKG.DELETE_POSTED_JE(?,?,?,?)');
$success = odbc_execute($SQL, array($PAYMENT_ID,$uid,$JENUM,$JETYPE));	

if (!$success) {
	print(odbc_errormsg($CONNECT)."\n");		
}else{
	print 'The transaction entry is reversed successfully.';
}


odbc_close($CONNECT);
exit();
}
?>

<?php 
/*<!---------------------------------------------------------------------------------------------> */
  
if (isset($action) && ($action =="UPDATE_DATA")) {
header('Content-Type:text/html; charset=windows-1256');
$err_msg='';
$err_count=0;
if (isset($M_DETAIL_DATA) && ($M_DETAIL_DATA != '')){
$SQL = "DELETE FROM AP_APPS.AP_APP_PAYMENTS_DATA_TEMP WHERE PAYMENT_ID=".$PAYMENT_ID;
$success =odbc_exec($CONNECT , $SQL);	
$SQL = "INSERT INTO AP_APPS.AP_APP_PAYMENTS_DATA_TEMP select '".session_id()."' as SID,A.* from  (".$M_DETAIL_DATA.")A";
$success =odbc_exec($CONNECT , $SQL);
$SQL = "DELETE FROM AP_APPS.AP_APP_PAYMENTS_DETAILS A WHERE A.PAYMENT_ID=".$PAYMENT_ID." 
    AND A.PAYMENT_LINE_ID NOT IN(SELECT TO_PAYMENT_LINE_ID FROM AP_APPS.AP_APP_PRE_PAY_APPLY_HISTORY WHERE TO_PAYMENT_LINE_ID =A.PAYMENT_LINE_ID) 
    AND A.PAYMENT_LINE_ID NOT IN(SELECT PAYMENT_LINE_ID FROM AP_APPS.AP_APP_PAYMENTS_DATA_TEMP WHERE PAYMENT_ID=".$PAYMENT_ID." AND SESSION_ID='".session_id()."')";
$success =odbc_exec($CONNECT , $SQL);	 
}
if (isset($JE_DATA) && ($JE_DATA != '')){
$SQL = "DELETE FROM AP_APPS.AP_APP_PAYMENTS_JE_TEMP WHERE PAYMENT_ID=".$PAYMENT_ID;
$success =odbc_exec($CONNECT , $SQL);		
$SQL = "INSERT INTO AP_APPS.AP_APP_PAYMENTS_JE_TEMP select '".session_id()."' as SID,A.* from  (".$JE_DATA.")A";
$success =odbc_exec($CONNECT , $SQL);
$SQL = "DELETE FROM AP_APPS.AP_APP_PAYMENTS_JE WHERE PAYMENT_ID=".$PAYMENT_ID;
$success =odbc_exec($CONNECT , $SQL);
}

$SQL    = odbc_prepare($CONNECT, 'CALL AP_APPS.AP_APP_PKG.UPDATE_DATA(?,?,?,?,?)');
$success = odbc_execute($SQL, array($ORG_ID,$PAYMENT_ID,$HEADER_DATA,$uid,session_id()));
if (!$success) {
	$err_count=1;
	$err_msg =$err_msg ."<div class=\"alert-box errormsg\">". odbc_errormsg($CONNECT)."</div>";
		
}
if ($err_count > 0){print $err_msg;}
odbc_close($CONNECT);
exit();
}
?>
<?php 
/*<!---------------------------------------------------------------------------------------------> */
  
if (isset($action) && ($action =="UPDATE_DATA_NEW")) {
header('Content-Type:text/html; charset=windows-1256');


$SQL    = "SELECT AP_APPS.AP_APP_PAYMENTS_HEADERS_S.NEXTVAL FROM DUAL";
$RS  = odbc_exec($CONNECT , $SQL);

if (!$RS) {
	print(odbc_errormsg($CONNECT)."\n");		
}

while(odbc_fetch_row($RS)){
print odbc_result($RS, 1).'</td>';
}

odbc_close($CONNECT);
exit();
}
?>
<?php 
/*<!---------------------------------------------------------------------------------------------> */
  
if (isset($action) && ($action =="GET_NEW_DOCNUM")) {
header('Content-Type:text/html; charset=windows-1256');

$SQL    = "SELECT PAYMENT_DOC_NUM FROM AP_APPS.AP_APP_PAYMENTS_HEADERS WHERE PAYMENT_ID=".$PAYMENT_ID;
$RS  = odbc_exec($CONNECT , $SQL);

if (!$RS) {
	//print(odbc_errormsg($CONNECT)."\n");
	print 'Err:-1';		
}

while(odbc_fetch_row($RS)){
print odbc_result($RS, 1).'</td>';
}

odbc_close($CONNECT);
exit();
}
?>