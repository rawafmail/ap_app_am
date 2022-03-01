<?php
//-- MAIN VARIABLES
$uid = $_POST["uid"];
$action = $_POST["action"];

$ORGANIZATION_ID = $_POST["ORGANIZATION_ID"];
$ORG_ID = $_POST["ORG_ID"];
$COMPANY = $_POST["COMPANY"];
$PAYMENT_ID = $_POST["paymentid"];
$LE = $_POST["LE"];
$lookup = $_POST["lookup"];
$search_item = $_POST["search_item"];
$search_item_1= iconv('utf-8', 'windows-1256', $search_item);
$mjcode = $_POST["mjcode"];
$SEARCH_ACC_CC_TYPE = $_POST["mType"];
$GET_JE_TABLE_PARAMS = $_POST["GET_JE_TABLE_PARAMS"];
$EditJE_TYPE=$_POST["EditJE_TYPE"];
$SHIPMENT_NUM=$_POST["SHIPMENT_NUM"];
$CURR = $_POST["CURR"];

//-- DATABASE CONNECTION 
$CONNECT = odbc_connect('PROD', 'apps', 'apps');
?>
<?php  
/*<!---------------------------------------------------------------------------------------------> */
if (isset($action) && ($action =="GET_JE_TABLE_PARAMS")) {
header('Content-Type:text/html; charset=windows-1256');
$SQL="SELECT * FROM TABLE(AP_APPS.AP_APP_PKG.GET_JE_TABLE(".$GET_JE_TABLE_PARAMS.")) ORDER BY ENT_D DESC,JE_LINE_NUM";

$ROWCOUNT=0;
$RS  = odbc_exec($CONNECT , $SQL);
if (!$RS) {
	print(odbc_error($CONNECT).": ".odbc_errormsg($CONNECT));
}
while(odbc_fetch_row($RS)){
$ROWCOUNT = $ROWCOUNT+1;
print '<input type="text" id="GLACCOUNT" value="'.odbc_result($RS, "ACCT_NUM").'"  onKeyDown="javascript:HandleGridJEKeyEvent(event,this.parentNode.parentNode.rowIndex,\''.odbc_result($RS, "JE_TYPE").'\');" ondblclick="EDIT_JENTRY(this.parentNode.parentNode.rowIndex)" readonly="readonly" /></td>';
print '<input type="text" value="'.odbc_result($RS, "CC_NUM").'"  onKeyDown="javascript:HandleGridJEKeyEvent(event,this.parentNode.parentNode.rowIndex,\''.odbc_result($RS, "JE_TYPE").'\');" ondblclick="EDIT_JENTRY(this.parentNode.parentNode.rowIndex)" readonly="readonly" /></td>';
print '<input type="text" value="'.odbc_result($RS, "ACCT_DESC").'"  onKeyDown="javascript:HandleGridJEKeyEvent(event,this.parentNode.parentNode.rowIndex,\''.odbc_result($RS, "JE_TYPE").'\');" ondblclick="EDIT_JENTRY(this.parentNode.parentNode.rowIndex)" readonly="readonly" /></td>';
print '<input type="text" value="'.odbc_result($RS, "CC_DESC").'"  onKeyDown="javascript:HandleGridJEKeyEvent(event,this.parentNode.parentNode.rowIndex,\''.odbc_result($RS, "JE_TYPE").'\');" ondblclick="EDIT_JENTRY(this.parentNode.parentNode.rowIndex)" readonly="readonly" /></td>';
print '<input type="text" value="'.number_format(odbc_result($RS, "ENT_D"),2,".",",").'" style="text-align:right"  onKeyDown="javascript:HandleGridJEKeyEvent(event,this.parentNode.parentNode.rowIndex,\''.odbc_result($RS, "JE_TYPE").'\');" ondblclick="EDIT_JENTRY(this.parentNode.parentNode.rowIndex)"  readonly="readonly" /></td>';
print '<input type="text" value="'.number_format(odbc_result($RS, "ENT_C"),2,".",",").'" style="text-align:right"  onKeyDown="javascript:HandleGridJEKeyEvent(event,this.parentNode.parentNode.rowIndex,\''.odbc_result($RS, "JE_TYPE").'\');" ondblclick="EDIT_JENTRY(this.parentNode.parentNode.rowIndex)"  readonly="readonly" /></td>';
print '<input type="text" value="'.number_format(odbc_result($RS, "ACCT_D"),2,".",",").'" style="text-align:right"  onKeyDown="javascript:HandleGridJEKeyEvent(event,this.parentNode.parentNode.rowIndex,\''.odbc_result($RS, "JE_TYPE").'\');" ondblclick="EDIT_JENTRY(this.parentNode.parentNode.rowIndex)"  readonly="readonly" /></td>';
print '<input type="text" value="'.number_format(odbc_result($RS, "ACCT_C"),2,".",",").'" style="text-align:right"  onKeyDown="javascript:HandleGridJEKeyEvent(event,this.parentNode.parentNode.rowIndex,\''.odbc_result($RS, "JE_TYPE").'\');" ondblclick="EDIT_JENTRY(this.parentNode.parentNode.rowIndex)"  readonly="readonly" /></td>';
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
print '<input type="hidden" id="JE_LINE_NUM" 			value="'.odbc_result($RS, "JE_LINE_NUM").'"  />';
print '<input type="hidden" id="JE_HEADER_ID" 		value="'.odbc_result($RS, "JE_HEADER_ID").'"  />';
print '<input type="hidden" id="SHIPMENT_NUM" 		value="'.odbc_result($RS, "SHIPMENT_NUM").'"  />';
print '<input type="hidden" id="EXPENSE_TYPE" 		value="'.odbc_result($RS, "EXPENSE_TYPE").'"  />';
print '<input type="hidden" id="MAJOR_CODE" 			value="'.odbc_result($RS, "MAJOR_CODE").'"  />';
print '<input type="hidden" id="MINOR_CODE" 			value="'.odbc_result($RS, "MINOR_CODE").'"  />';
print '<input type="hidden" id="SALES_R_FLAG" 		value="'.odbc_result($RS, "SALES_R_FLAG").'"  />';
print '<input type="hidden" id="SALES_TAX_RATE" 		value="'.odbc_result($RS, "SALES_TAX_RATE").'"  />';
print '<input type="hidden" id="PAY_SCHEDULE_ID" 		value="'.odbc_result($RS, "PAY_SCHEDULE_ID").'"  />';
print '<input type="hidden" id="ERR_NUM" 				value="'.odbc_result($RS, "ERR_NUM").'"  />';
print '<input type="hidden" id="ERR_MSG" 				value="'.odbc_result($RS, "ERR_MSG").'"  /></td>';
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
if (isset($action) && ($action =="CreateMatchedAccounting")) {
header('Content-Type:text/html; charset=windows-1256');
$SQL="SELECT * FROM AP_APPS.AP_APP_DEFAULT_MATCHED_JE_SUM WHERE (NVL(CR_AMT_ACC,0) <> 0 OR NVL(DR_AMT_ACC,0) <> 0) AND PAYMENT_ID=".$PAYMENT_ID;

$ROWCOUNT=0;
$RS  = odbc_exec($CONNECT , $SQL);
if (!$RS) {
	print(odbc_error($CONNECT).": ".odbc_errormsg($CONNECT)."\n".$SQL);
}

while(odbc_fetch_row($RS)){
$ROWCOUNT = $ROWCOUNT+1;
print '<input type="text" id="GLACCOUNT" value="'.odbc_result($RS, "GLACCOUNT").'"   ondblclick="EDIT_JENTRY(this.parentNode.parentNode.rowIndex)" readonly="readonly" /></td>';
print '<input type="text" value="'.odbc_result($RS, "COSTCENTER").'"   ondblclick="EDIT_JENTRY(this.parentNode.parentNode.rowIndex)" readonly="readonly" /></td>';
print '<input type="text" value="'.odbc_result($RS, "ACCT").'"   ondblclick="EDIT_JENTRY(this.parentNode.parentNode.rowIndex)" readonly="readonly" /></td>';
print '<input type="text" value="'.odbc_result($RS, "CCT").'"   ondblclick="EDIT_JENTRY(this.parentNode.parentNode.rowIndex)" readonly="readonly" /></td>';
print '<input type="text" value="'.number_format(odbc_result($RS, "DR_AMT"),2,".",",").'" style="text-align:right"   ondblclick="EDIT_JENTRY(this.parentNode.parentNode.rowIndex)"  readonly="readonly" /></td>';
print '<input type="text" value="'.number_format(odbc_result($RS, "CR_AMT"),2,".",",").'" style="text-align:right"   ondblclick="EDIT_JENTRY(this.parentNode.parentNode.rowIndex)"  readonly="readonly" /></td>';
print '<input type="text" value="'.number_format(odbc_result($RS, "DR_AMT_ACC"),2,".",",").'" style="text-align:right"   ondblclick="EDIT_JENTRY(this.parentNode.parentNode.rowIndex)"  readonly="readonly" /></td>';
print '<input type="text" value="'.number_format(odbc_result($RS, "CR_AMT_ACC"),2,".",",").'" style="text-align:right"   ondblclick="EDIT_JENTRY(this.parentNode.parentNode.rowIndex)"  readonly="readonly" /></td>';
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
print '<input type="hidden" id="JE_LINE_NUM" 			value="'.odbc_result($RS, "JE_LINE_NUM").'"  />';
print '<input type="hidden" id="JE_HEADER_ID" 		value="'.odbc_result($RS, "JE_HEADER_ID").'"  />';
print '<input type="hidden" id="SHIPMENT_NUM" 		value=""  />';
print '<input type="hidden" id="EXPENSE_TYPE" 		value="'.odbc_result($RS, "EXPENSE_TYPE").'"  />';
print '<input type="hidden" id="MAJOR_CODE" 			value="0"  />';
print '<input type="hidden" id="MINOR_CODE" 			value="0"  />';
print '<input type="hidden" id="SALES_R_FLAG" 		value="'.odbc_result($RS, "SALES_R_FLAG").'"  />';
print '<input type="hidden" id="SALES_TAX_RATE" 		value="'.odbc_result($RS, "SALES_TAX_RATE").'"  />';
print '<input type="hidden" id="PAY_SCHEDULE_ID" 		value="0"  />';
print '<input type="hidden" id="ERR_NUM" 				value="'.odbc_result($RS, "ERR_NUM").'"  />';
print '<input type="hidden" id="ERR_MSG" 				value="'.odbc_result($RS, "ERR_MSG").'"  /></td>';
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
//<!---------------------------------------------------------------------------------------------> 

if (isset($lookup) && ($lookup ==1)) {
 
if (isset($action) && ($action =="get_exp")) {
header('Content-Type:text/html; charset=windows-1256');
$SQL = "SELECT LOOKUP_CODE,LOOKUP_CODE||' - '||NVL(DESCRIPTION,'-') FROM AP_APPS.FND_LOOKUP_VALUES WHERE LOOKUP_TYPE = 'PHARCO SHIPMENTS EXPE TYPES' AND LANGUAGE = 'AR' ORDER BY LOOKUP_CODE
";
}


if (isset($action) && ($action =="get_major")) {
header('Content-Type:text/html; charset=windows-1256');
$SQL = "SELECT LOOKUP_CODE,LOOKUP_CODE||' - '||NVL(DESCRIPTION,'-') FROM AP_APPS.FND_LOOKUP_VALUES WHERE LOOKUP_TYPE = 'PHARCO_GL_MAJOR' AND LANGUAGE = 'AR' ORDER BY LOOKUP_CODE
";
}

if (isset($action) && ($action =="transtype")) {
header('Content-Type:text/html; charset=windows-1256');
$SQL = "SELECT LOOKUP_CODE,NVL(DESCRIPTION,'-') FROM AP_APPS.FND_LOOKUP_VALUES WHERE LOOKUP_TYPE = 'AP_TRANS_TYPES' AND LANGUAGE = 'AR' ORDER BY LOOKUP_CODE
";
}

if (isset($action) && ($action =="get_wat")) {
header('Content-Type:text/html; charset=windows-1256');
$SQL = "SELECT LOOKUP_CODE FROM AP_APPS.FND_LOOKUP_VALUES WHERE LOOKUP_TYPE = 'PHARCO_AWT_RATS' AND LANGUAGE = 'AR' ORDER BY TO_NUMBER(LOOKUP_CODE)
";
}

if (isset($action) && ($action =="get_vat")) {
header('Content-Type:text/html; charset=windows-1256');
$SQL = "SELECT LOOKUP_CODE FROM AP_APPS.FND_LOOKUP_VALUES WHERE LOOKUP_TYPE = 'PHARCO_VAT_RATES' AND LANGUAGE = 'AR' ORDER BY TO_NUMBER(LOOKUP_CODE)
";
}


if (isset($action) && ($action =="get_sal")) {
header('Content-Type:text/html; charset=windows-1256');
$SQL = "SELECT LOOKUP_CODE FROM AP_APPS.FND_LOOKUP_VALUES WHERE LOOKUP_TYPE = 'PHARCO_SALES_RATE' AND LANGUAGE = 'AR' ORDER BY TO_NUMBER(LOOKUP_CODE)
";
}

if (isset($action) && ($action =="get_PENALTY")) {
header('Content-Type:text/html; charset=windows-1256');
$SQL = "SELECT LOOKUP_CODE FROM AP_APPS.FND_LOOKUP_VALUES WHERE LOOKUP_TYPE = 'PHARCO_PENALTY' AND LANGUAGE = 'AR'  ORDER BY TO_NUMBER(LOOKUP_CODE)
";
}

if (isset($action) && ($action =="get_irrsal")) {
header('Content-Type:text/html; charset=windows-1256');
$SQL = "SELECT LOOKUP_CODE FROM AP_APPS.FND_LOOKUP_VALUES WHERE LOOKUP_TYPE = 'PHARCO_SALES_RATE' AND LANGUAGE = 'AR' ORDER BY TO_NUMBER(LOOKUP_CODE)
";
}



if (isset($action) && ($action =="get_M_GL_VENDOR")) {
header('Content-Type:text/html; charset=windows-1256');
$SQL = "SELECT A.FLEX_VALUE_MEANING,  A.DESCRIPTION FROM FND_FLEX_VALUES_TL A 
INNER JOIN FND_FLEX_VALUES B ON A.FLEX_VALUE_ID = B.FLEX_VALUE_ID 
INNER JOIN FND_FLEX_VALUE_SETS C ON B.FLEX_VALUE_SET_ID = C.FLEX_VALUE_SET_ID  
WHERE NVL(B.END_DATE_ACTIVE, SYSDATE) + 1 > SYSDATE AND C.FLEX_VALUE_SET_ID = 1014148 AND A.LANGUAGE = 'AR'  AND SUMMARY_FLAG = 'N'
 ORDER BY A.FLEX_VALUE_MEANING
";
}

if (isset($action) && ($action =="get_M_GL_CC")) {
header('Content-Type:text/html; charset=windows-1256');
$SQL = "SELECT * FROM(
SELECT A.FLEX_VALUE_MEANING,  A.DESCRIPTION FROM FND_FLEX_VALUES_TL A 
INNER JOIN FND_FLEX_VALUES B ON A.FLEX_VALUE_ID = B.FLEX_VALUE_ID 
INNER JOIN FND_FLEX_VALUE_SETS C ON B.FLEX_VALUE_SET_ID = C.FLEX_VALUE_SET_ID  
WHERE NVL(B.END_DATE_ACTIVE, SYSDATE) + 1 > SYSDATE AND C.FLEX_VALUE_SET_ID = 1014149 AND A.LANGUAGE = 'AR'  AND SUMMARY_FLAG = 'N'
AND SUBSTR(A.FLEX_VALUE_MEANING,1,2) ='".$COMPANY."' 
UNION ALL
SELECT '0', 'No Cost Center' FROM DUAL
)ORDER BY FLEX_VALUE_MEANING
";
}

$RS  = odbc_exec($CONNECT , $SQL);
print "<option value='0'>--</option>";
while(odbc_fetch_row($RS)){
if (($action =="get_major")||($action =="get_exp")||($action =="transtype")){	
	print '<option value="'.odbc_result($RS,1) . '">'.odbc_result($RS,2) . '</option>';
}else if (($action =="get_cashacc")||($action =="get_M_GL_CC")||($action =="get_M_GL_VENDOR")){
	print '<option value="'.odbc_result($RS,1) . '">'.odbc_result($RS,1).' - '.odbc_result($RS,2).'</option>';
}else{
	print '<option value="'.odbc_result($RS,1) . '">'.odbc_result($RS,1) . '</option>';

}
}


//-- CLOSE DATABASE CONNECTION
odbc_close($CONNECT);
exit();
}
?>
<?php  
//<!---------------------------------------------------------------------------------------------> 
if (isset($action) && ($action =="GET_MINOR")) {
	
header('Content-Type:text/html; charset=windows-1256');	
$SQL = "SELECT MINOR_TYPE_CODE,MINOR_TYPE_CODE||' - '||DESCRIPTION FROM AP_APPS.GL_MINOR_TYPES WHERE  ORG_ID = ".$ORG_ID." AND MAJOR_TYPE = '".$mjcode."' ORDER BY MINOR_TYPE_CODE";

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
if (isset($action) && ($action =="GET_PO_JE_LIST")) {
	
header('Content-Type:text/html; charset=windows-1256');	
$SQL = "SELECT PO_HEADER_ID,SEGMENT1 FROM PO.PO_HEADERS_ALL WHERE  ORG_ID = ".$ORG_ID." AND (UPPER(SEGMENT1) LIKE 'GRP-Y%' OR UPPER(SEGMENT1) LIKE 'GRP-V%' OR UPPER(SEGMENT1) LIKE 'GRP-B%')";

$RS  = odbc_exec($CONNECT , $SQL);
print "<option value='0'>--</option>";
while(odbc_fetch_row($RS)){
print   "<option value='".odbc_result($RS, 2)."'>".odbc_result($RS, 2)." </option>";
}
//-- CLOSE DATABASE CONNECTION
odbc_close($CONNECT);
exit();
}
?>
<?php  
//<!---------------------------------------------------------------------------------------------> 
if (isset($action) && ($action =="GET_AP_TRADE_PAY_SCHEDULE")) {
	
header('Content-Type:text/html; charset=windows-1256');	
$SQL = "SELECT PAY_SCHEDULE_ID,BILL_DESCRIPTION||' - '||DOC_DESCRIPTION||' - '||DUE_DATE FROM AP_APPS.AP_TRADE_PAY_SCHEDULE_VW WHERE  SHIPMENT_NUM = '".$SHIPMENT_NUM."' AND CURRENCY_CODE='".$CURR."'";

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
if (isset($action) && ($action =="SEARCH_ACCOUNTS_GET_RESULT")) {
$COUNT_MYRS = 0;	
header('Content-Type:text/html; charset=windows-1256');
print '<table class="Design6" border="1" cellspacing="0" cellpadding="0" id="items_results">';
print '<thead style="color:#fff">';
print '<tr>';
print '<th width="35%">Code</th>';
print '<th width="65%">Name</th>';
print '</tr>';
print '</thead>';

if ($EditJE_TYPE=='AP_PAYMENT'){
$sql = "SELECT A.FLEX_VALUE_MEANING,  A.DESCRIPTION FROM FND_FLEX_VALUES_TL A 
INNER JOIN FND_FLEX_VALUES B ON A.FLEX_VALUE_ID = B.FLEX_VALUE_ID 
INNER JOIN FND_FLEX_VALUE_SETS C ON B.FLEX_VALUE_SET_ID = C.FLEX_VALUE_SET_ID  
WHERE NVL(B.END_DATE_ACTIVE, SYSDATE) + 1 > SYSDATE AND C.FLEX_VALUE_SET_ID = 1014148 AND A.LANGUAGE = 'AR'  AND SUMMARY_FLAG = 'N'
 
 AND (A.FLEX_VALUE_MEANING LIKE '".$search_item."' OR UPPER(A.DESCRIPTION) LIKE UPPER('".$search_item_1."')) ORDER BY A.FLEX_VALUE_MEANING";
}else{
$sql = "SELECT A.FLEX_VALUE_MEANING,  A.DESCRIPTION FROM FND_FLEX_VALUES_TL A 
INNER JOIN FND_FLEX_VALUES B ON A.FLEX_VALUE_ID = B.FLEX_VALUE_ID 
INNER JOIN FND_FLEX_VALUE_SETS C ON B.FLEX_VALUE_SET_ID = C.FLEX_VALUE_SET_ID  
WHERE NVL(B.END_DATE_ACTIVE, SYSDATE) + 1 > SYSDATE AND C.FLEX_VALUE_SET_ID = 1014148 AND A.LANGUAGE = 'AR'  AND SUMMARY_FLAG = 'N'
 --AND A.FLEX_VALUE_MEANING NOT IN(SELECT DISTINCT MEANING FROM AP_APPS.FND_LOOKUP_VALUES WHERE LOOKUP_TYPE = 'PHARCO_AP_ACCOUNTS' AND LANGUAGE = 'AR' )
 AND (A.FLEX_VALUE_MEANING LIKE '".$search_item."' OR UPPER(A.DESCRIPTION) LIKE UPPER('".$search_item_1."')) ORDER BY A.FLEX_VALUE_MEANING";
}
$res = odbc_exec($CONNECT, $sql);
print '<tbody>';
while(odbc_fetch_row($res)){
	$COUNT_MYRS = $COUNT_MYRS + 1;
	print '<tr>';
	print '<td style="background:#FFFFFF; width:35% "><a href="#" onclick="javascript:SELECT_ACCOUNT(';
	print "'".odbc_result($res, 1)."',";
	print "'".odbc_result($res, 2)."'";
	print ')">'.odbc_result($res, 1).'</a></td>';
	print '<td style="background:#FFFFFF; width:65% ">'.odbc_result($res, 2).'</td>';
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
if (isset($action) && ($action =="SEARCH_CCENTERS_GET_RESULT")) {
$COUNT_MYRS = 0;	
header('Content-Type:text/html; charset=windows-1256');
print '<table class="Design6" border="1" cellspacing="0" cellpadding="0" id="items_results">';
print '<thead style="color:#fff">';
print '<tr>';
print '<th width="35%">Code</th>';
print '<th width="65%">Name</th>';
print '</tr>';
print '</thead>';

$sql = "SELECT A.FLEX_VALUE_MEANING,  A.DESCRIPTION FROM FND_FLEX_VALUES_TL A 
INNER JOIN FND_FLEX_VALUES B ON A.FLEX_VALUE_ID = B.FLEX_VALUE_ID 
INNER JOIN FND_FLEX_VALUE_SETS C ON B.FLEX_VALUE_SET_ID = C.FLEX_VALUE_SET_ID  
WHERE NVL(B.END_DATE_ACTIVE, SYSDATE) + 1 > SYSDATE AND C.FLEX_VALUE_SET_ID = 1014149 AND A.LANGUAGE = 'AR'  AND SUMMARY_FLAG = 'N' AND A.FLEX_VALUE_MEANING LIKE '".$COMPANY."%'
 AND (A.FLEX_VALUE_MEANING LIKE '".$search_item."' OR UPPER(A.DESCRIPTION) LIKE UPPER('".$search_item_1."')) ORDER BY A.FLEX_VALUE_MEANING";

$res = odbc_exec($CONNECT, $sql);
print '<tbody>';
while(odbc_fetch_row($res)){
	$COUNT_MYRS = $COUNT_MYRS + 1;
	print '<tr>';
	print '<td style="background:#FFFFFF; width:35% "><a href="#" onclick="javascript:SELECT_CCENTER(';
	print "'".odbc_result($res, 1)."',";
	print "'".odbc_result($res, 2)."'";
	print ')">'.odbc_result($res, 1).'</a></td>';
	print '<td style="background:#FFFFFF; width:65% ">'.odbc_result($res, 2).'</td>';
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
if (isset($action) && ($action =="SEARCH_ACC_NAME")) {
$COUNT_MYRS = 0;	
header('Content-Type:text/html; charset=windows-1256');

if ($SEARCH_ACC_CC_TYPE=='A'){
if ($EditJE_TYPE=='AP_PAYMENT'){
$sql = "SELECT A.DESCRIPTION FROM FND_FLEX_VALUES_TL A 
INNER JOIN FND_FLEX_VALUES B ON A.FLEX_VALUE_ID = B.FLEX_VALUE_ID 
INNER JOIN FND_FLEX_VALUE_SETS C ON B.FLEX_VALUE_SET_ID = C.FLEX_VALUE_SET_ID  
WHERE NVL(B.END_DATE_ACTIVE, SYSDATE) + 1 > SYSDATE AND C.FLEX_VALUE_SET_ID = 1014148 AND A.LANGUAGE = 'AR'  AND SUMMARY_FLAG = 'N' 
  AND (A.FLEX_VALUE_MEANING LIKE '".$search_item."' OR UPPER(A.DESCRIPTION) LIKE UPPER('".$search_item_1."')) ORDER BY A.FLEX_VALUE_MEANING";
}else{
$sql = "SELECT A.DESCRIPTION FROM FND_FLEX_VALUES_TL A 
INNER JOIN FND_FLEX_VALUES B ON A.FLEX_VALUE_ID = B.FLEX_VALUE_ID 
INNER JOIN FND_FLEX_VALUE_SETS C ON B.FLEX_VALUE_SET_ID = C.FLEX_VALUE_SET_ID  
WHERE NVL(B.END_DATE_ACTIVE, SYSDATE) + 1 > SYSDATE AND C.FLEX_VALUE_SET_ID = 1014148 AND A.LANGUAGE = 'AR'  AND SUMMARY_FLAG = 'N' 
 --AND A.FLEX_VALUE_MEANING NOT IN(SELECT DISTINCT MEANING FROM AP_APPS.FND_LOOKUP_VALUES WHERE LOOKUP_TYPE = 'PHARCO_AP_ACCOUNTS' AND LANGUAGE = 'AR' )
 AND (A.FLEX_VALUE_MEANING LIKE '".$search_item."' OR UPPER(A.DESCRIPTION) LIKE UPPER('".$search_item_1."')) ORDER BY A.FLEX_VALUE_MEANING";
}
}




if ($SEARCH_ACC_CC_TYPE=='C'){
$sql = "SELECT A.DESCRIPTION FROM FND_FLEX_VALUES_TL A 
INNER JOIN FND_FLEX_VALUES B ON A.FLEX_VALUE_ID = B.FLEX_VALUE_ID 
INNER JOIN FND_FLEX_VALUE_SETS C ON B.FLEX_VALUE_SET_ID = C.FLEX_VALUE_SET_ID  
WHERE NVL(B.END_DATE_ACTIVE, SYSDATE) + 1 > SYSDATE AND C.FLEX_VALUE_SET_ID = 1014149 AND A.LANGUAGE = 'AR'  AND SUMMARY_FLAG = 'N' AND A.FLEX_VALUE_MEANING LIKE '".$COMPANY."%'
 AND (A.FLEX_VALUE_MEANING LIKE '".$search_item."' OR UPPER(A.DESCRIPTION) LIKE UPPER('".$search_item_1."')) ORDER BY A.FLEX_VALUE_MEANING";
}
$res = odbc_exec($CONNECT, $sql);

while(odbc_fetch_row($res)){
	$COUNT_MYRS = $COUNT_MYRS + 1;
	print $COUNT_MYRS.'</td>'.odbc_result($res, 1).'</td>';
}


if ($COUNT_MYRS==0){
	print $COUNT_MYRS.'</td>No data found</td>';
}
//-- CLOSE DATABASE CONNECTION
odbc_close($CONNECT);

exit();
}
?>