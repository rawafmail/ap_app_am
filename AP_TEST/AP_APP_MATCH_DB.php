<?php
//-- MAIN VARIABLES
$uid = $_POST["uid"];
$action = $_POST["action"];

$ORGANIZATION_ID = $_POST["ORGANIZATION_ID"];
$ORG_ID = $_POST["ORG_ID"];
$LE = $_POST["LE"];
$search_item = $_POST["search_item"];
$search_item_1= iconv('utf-8', 'windows-1256', $search_item);

$vendor_id = $_POST["vendor_id"];
$PO_ID= $_POST["po_id"];
$PO_NUM_TXT= $_POST["po_num_txt"];
$SUB_INV= $_POST["store"];
$period= $_POST["invperiod"];
$matchtype= $_POST["matchtype"];
$invcnum= $_POST["invcnum"];
$txtInvDate= $_POST["txtInvDate"];
$rcv_num= $_POST["rcv_num"];

$header_ven_emp_number=$_POST['header_ven_emp_number'];


//-- DATABASE CONNECTION 
$CONNECT = odbc_connect('PROD', 'apps', 'apps');
?>
<?php  
/*<!---------------------------------------------------------------------------------------------> */
if (isset($action) && ($action =="GET_MATCH_DTL")) {
header('Content-Type:text/html; charset=windows-1256');
//CHECK VENDOR DATA [ENABLED OR  DISAPLED]

$CHECK_VENDOR_SQL = "SELECT APPS.CHECK_VENDOR_STATUS('".$vendor_id."') AS ENABLED_FLAG FROM DUAL";	
$RS_CHECK_VENDOR_SQL  = odbc_exec($CONNECT , $CHECK_VENDOR_SQL);
$check_flag=odbc_result($RS_CHECK_VENDOR_SQL, "ENABLED_FLAG");
if($check_flag=='Y'){

$SQL='';
if ($matchtype==1){	
$SQL = "SELECT A.TERM_INST_TYPE,'".$invcnum."' INV_NUM,'".$txtInvDate."' INV_DATE,(NVL (C.QUANTITY, 0) - NVL (C.BILLED_QTY, 0)) QUANTITY,A.PRIMARY_UOM_CODE,A.UNIT_PRICE,0 PRICE_VAR,(A.ITEM_CODE||' - '||A.DESCRIPTION)ITEM,A.ITEM_ID,(A.VENDOR_NUM||' - '||NVL(B.KNOWN_AS, A.VENDOR_NAME))VENDOR,A.VENDOR_NUM,A.PO_LINE_ID,A.PO_HEADER_ID,A.ORG_ID,A.VENDOR_ACCOUNT,0 TRANSACTION_ID,
0 UNIT_COST, AP_APPS.AP_APP_TRANS_SET_S.NEXTVAL AS PAYMENT_LINE_ID,C.PAYMENT_SCHEDULE_ID  FROM  AP_APPS.PO_APPROVED A LEFT OUTER JOIN AR.HZ_PARTIES B ON A.PARTY_ID = B.PARTY_ID INNER JOIN  AP_APPS.AP_PAYMENTS_SCHEDULE_ALL C ON A.PO_HEADER_ID = C.PO_HEADER_ID AND A.PO_LINE_ID = C.PO_LINE_ID AND A.TERM_INST_TYPE = C.TERM_INST_TYPE 
 WHERE (NVL(A.QUANTITY,0) - NVL(A.QUANTITY_BILLED,0))>0 AND (NVL (C.QUANTITY, 0) - NVL (C.BILLED_QTY, 0))>0 AND A.PO_HEADER_ID =  ".$PO_ID." AND A.ORG_ID =  ".$ORG_ID." AND C.VENDOR_NUM='$header_ven_emp_number'";

if (isset($PO_NUM_TXT)&& ($PO_NUM_TXT!='0')){
	$SQL = "SELECT A.TERM_INST_TYPE,'".$invcnum."' INV_NUM,'".$txtInvDate."' INV_DATE,(NVL (C.QUANTITY, 0) - NVL (C.BILLED_QTY, 0))  QUANTITY,A.PRIMARY_UOM_CODE,A.UNIT_PRICE,0 PRICE_VAR,(A.ITEM_CODE||' - '||A.DESCRIPTION)ITEM,A.ITEM_ID,(A.VENDOR_NUM||' - '||NVL(B.KNOWN_AS, A.VENDOR_NAME))VENDOR,A.VENDOR_NUM,A.PO_LINE_ID,A.PO_HEADER_ID,A.ORG_ID,A.VENDOR_ACCOUNT,0 TRANSACTION_ID,
0 UNIT_COST, AP_APPS.AP_APP_TRANS_SET_S.NEXTVAL AS PAYMENT_LINE_ID,C.PAYMENT_SCHEDULE_ID  FROM  AP_APPS.PO_APPROVED A LEFT OUTER JOIN AR.HZ_PARTIES B ON A.PARTY_ID = B.PARTY_ID  INNER JOIN AP_APPS.AP_PAYMENTS_SCHEDULE_ALL C ON A.PO_HEADER_ID = C.PO_HEADER_ID AND A.PO_LINE_ID = C.PO_LINE_ID AND A.TERM_INST_TYPE = C.TERM_INST_TYPE 
 WHERE (NVL(A.QUANTITY,0) - NVL(A.QUANTITY_BILLED,0))>0 AND (NVL (C.QUANTITY, 0) - NVL (C.BILLED_QTY, 0))>0 AND UPPER(A.PO_NUM) like  '%".$PO_NUM_TXT."' AND UPPER(VENDOR_NUM)=UPPER(TRIM('".$vendor_id."')) AND A.ORG_ID =  ".$ORG_ID." AND C.VENDOR_NUM='$header_ven_emp_number'";

}
 
}

if ($matchtype==2){	
$SQL = "SELECT A.TERM_INST_TYPE,'".$invcnum."' INV_NUM,'".$txtInvDate."' INV_DATE,A.RECEIPT_NUM,  0 UNIT_COST,(NVL (C.QUANTITY, 0) - NVL (C.BILLED_QTY, 0))QUANTITY,A.PRIMARY_UOM_CODE,(VALUE_RECEIVED/A.QUANTITY)UNIT_PRICE,0 PRICE_VAR,(A.ITEM_CODE||' - '||A.DESCRIPTION)ITEM,A.ITEM_ID,(A.VENDOR_NUM||' - '||NVL(B.KNOWN_AS, A.VENDOR_NAME))VENDOR,A.VENDOR_NUM,A.PO_LINE_ID,A.PO_HEADER_ID,".$ORG_ID." ORG_ID,A.VENDOR_ACCOUNT,A.TRANSACTION_ID, AP_APPS.AP_APP_TRANS_SET_S.NEXTVAL AS PAYMENT_LINE_ID,C.PAYMENT_SCHEDULE_ID 
 FROM  AP_APPS.PO_RECEIVED_DTL A LEFT OUTER JOIN AR.HZ_PARTIES B ON A.PARTY_ID = B.PARTY_ID INNER JOIN AP_APPS.AP_PAYMENTS_SCHEDULE_ALL C ON A.PO_HEADER_ID = C.PO_HEADER_ID AND A.PO_LINE_ID = C.PO_LINE_ID AND A.TRANSACTION_ID = C.RCV_TRANSACTION_ID AND A.TERM_INST_TYPE = C.TERM_INST_TYPE 
 WHERE  (NVL (C.QUANTITY, 0) - NVL (C.BILLED_QTY, 0))>0 AND A.RECEIPT_NUM =  '".$rcv_num."' AND A.ORGANIZATION_ID =  ".$ORGANIZATION_ID." AND C.VENDOR_NUM='$header_ven_emp_number'";
}

if ($matchtype==3){	
$SQL = "SELECT TERM_INST_TYPE,'".$invcnum."' INV_NUM,'".$txtInvDate."' INV_DATE,A.RECEIPT_NUM,  0 UNIT_COST,QUANTITY,PRIMARY_UOM_CODE,(VALUE_RECEIVED/QUANTITY)UNIT_PRICE,0 PRICE_VAR,(ITEM_CODE||' - '||A.DESCRIPTION)ITEM,ITEM_ID,(VENDOR_NUM||' - '||NVL(B.KNOWN_AS, A.VENDOR_NAME))VENDOR,VENDOR_NUM,PO_LINE_ID,PO_HEADER_ID,".$ORG_ID." ORG_ID,VENDOR_ACCOUNT,TRANSACTION_ID, AP_APPS.AP_APP_TRANS_SET_S.NEXTVAL AS PAYMENT_LINE_ID 
 FROM  AP_APPS.PO_ACCEPTED_DTL A LEFT OUTER JOIN AR.HZ_PARTIES B ON A.PARTY_ID = B.PARTY_ID
 WHERE A.RECEIPT_NUM =  '".$rcv_num."' AND A.ORGANIZATION_ID =  ".$ORGANIZATION_ID." AND A.VENDOR_NUM='$header_ven_emp_number'";
}

if ($matchtype==4){
//(0-(VALUE_RECEIVED/QUANTITY))PRICE_VAR		
$SQL = "SELECT A.TERM_INST_TYPE,'".$invcnum."' INV_NUM,'".$txtInvDate."' INV_DATE,A.RECEIPT_NUM,A.RECEIPT_DOC_NUM, (VALUE_RECEIVED/A.QUANTITY)UNIT_COST,(NVL (C.QUANTITY, 0) - NVL (C.BILLED_QTY, 0))QUANTITY,A.PRIMARY_UOM_CODE,A.UNIT_PRICE,0 PRICE_VAR,(A.ITEM_CODE||' - '||A.DESCRIPTION)ITEM,A.ITEM_ID,(A.VENDOR_NUM||' - '||NVL(B.KNOWN_AS, A.VENDOR_NAME))VENDOR,A.VENDOR_NUM,A.PO_LINE_ID,A.PO_HEADER_ID,".$ORG_ID." ORG_ID,A.VENDOR_ACCOUNT,A.TRANSACTION_ID, AP_APPS.AP_APP_TRANS_SET_S.NEXTVAL AS PAYMENT_LINE_ID,C.PAYMENT_SCHEDULE_ID 
 FROM  AP_APPS.PO_DELIVERED_BILL_FOLLOW_UP A LEFT OUTER JOIN AR.HZ_PARTIES B ON A.PARTY_ID = B.PARTY_ID INNER JOIN AP_APPS.AP_PAYMENTS_SCHEDULE_ALL C ON A.PO_HEADER_ID = C.PO_HEADER_ID AND A.PO_LINE_ID = C.PO_LINE_ID AND A.TRANSACTION_ID = C.RCV_TRANSACTION_ID AND A.TERM_INST_TYPE = C.TERM_INST_TYPE
 WHERE NVL(A.QUANTITY_REMAIN,0) > 0 AND (NVL (C.QUANTITY, 0) - NVL (C.BILLED_QTY, 0))>0 AND (A.RECEIPT_DOC_NUM =  '".$rcv_num."' OR A.RECEIPT_NUM =  '".$rcv_num."') AND A.ORGANIZATION_ID =  ".$ORGANIZATION_ID." AND C.VENDOR_NUM='$header_ven_emp_number'";
}

if ($SUB_INV == 'IPROC'){
$SQL = "SELECT A.TERM_INST_TYPE,'".$invcnum."' INV_NUM,'".$txtInvDate."' INV_DATE,A.RECEIPT_NUM,A.RECEIPT_DOC_NUM, (VALUE_RECEIVED/A.QUANTITY)UNIT_COST,(NVL (A.QUANTITY, 0) - NVL (A.QUANTITY_BILLED, 0))QUANTITY,A.PRIMARY_UOM_CODE,A.UNIT_PRICE,0 PRICE_VAR,(A.ITEM_CODE||' - '||A.DESCRIPTION)ITEM,A.ITEM_ID,(A.VENDOR_NUM||' - '||NVL(B.KNOWN_AS, A.VENDOR_NAME))VENDOR,A.VENDOR_NUM,A.PO_LINE_ID,A.PO_HEADER_ID,".$ORG_ID." ORG_ID,A.VENDOR_ACCOUNT,A.TRANSACTION_ID, AP_APPS.AP_APP_TRANS_SET_S.NEXTVAL AS PAYMENT_LINE_ID,0 PAYMENT_SCHEDULE_ID 
 FROM  AP_APPS.PO_DELIVERED_BILL_FOLLOW_UP A LEFT OUTER JOIN AR.HZ_PARTIES B ON A.PARTY_ID = B.PARTY_ID 
 WHERE NVL(A.QUANTITY_REMAIN,0) > 0 AND (NVL (A.QUANTITY, 0) - NVL (A.QUANTITY_BILLED, 0))>0 AND (A.RECEIPT_DOC_NUM =  '".$rcv_num."' OR A.RECEIPT_NUM =  '".$rcv_num."') AND A.ORGANIZATION_ID =  ".$ORGANIZATION_ID;	
}
$ROWCOUNT=0;
$RS  = odbc_exec($CONNECT , $SQL);
while(odbc_fetch_row($RS)){
//========
$SQL ="SELECT
(SELECT ATTRIBUTE2 FROM AP.AP_SUPPLIER_SITES_ALL WHERE VENDOR_ID = A.VENDOR_ID AND ATTRIBUTE2 IS NOT NULL AND ROWNUM=1 AND ORG_ID =".$ORG_ID.") AS TAX_FILE_NUM,
(SELECT ATTRIBUTE3 FROM AP.AP_SUPPLIER_SITES_ALL WHERE VENDOR_ID = A.VENDOR_ID AND ATTRIBUTE2 IS NOT NULL AND ROWNUM=1 AND ORG_ID =".$ORG_ID.") AS TAX_REG_NUM
FROM AP.AP_SUPPLIERS A
WHERE A.SEGMENT1 ='".odbc_result($RS, "VENDOR_NUM")."'";
$TAX_DATA='';
$RS1  = odbc_exec($CONNECT , $SQL);
while(odbc_fetch_row($RS1)){
	$TAX_DATA=$TAX_DATA.' <b>Tax File Num.:</b>'.odbc_result($RS1, "TAX_FILE_NUM");
	$TAX_DATA=$TAX_DATA.'<br> <b>Tax Reg. Num.:</b> '.odbc_result($RS1, "TAX_REG_NUM");
}
//========	
$ROWCOUNT = $ROWCOUNT+1;
//print '<tr>';
print '<input type="text" id="MATCH_INV_NUM" value="'.odbc_result($RS, "INV_NUM").'" onKeyDown="javascript:HandleGridMatchKeyEvent(event,this.parentNode.parentNode.rowIndex);" ondblclick="EDIT_PRICE_OVERLAY(this.parentNode.parentNode.rowIndex)" readonly="readonly" /></td>';
print '<input type="text" value="'.odbc_result($RS, "INV_DATE").'" onKeyDown="javascript:HandleGridMatchKeyEvent(event,this.parentNode.parentNode.rowIndex);" ondblclick="EDIT_PRICE_OVERLAY(this.parentNode.parentNode.rowIndex)" readonly="readonly" /></td>';
print '<input type="text" value="'.odbc_result($RS, "QUANTITY").'" onKeyDown="javascript:HandleGridMatchKeyEvent(event,this.parentNode.parentNode.rowIndex);" ondblclick="EDIT_PRICE_OVERLAY(this.parentNode.parentNode.rowIndex)" readonly="readonly" /></td>';
print '<input type="text" value="'.odbc_result($RS, "PRIMARY_UOM_CODE").'" onKeyDown="javascript:HandleGridMatchKeyEvent(event,this.parentNode.parentNode.rowIndex);" ondblclick="EDIT_PRICE_OVERLAY(this.parentNode.parentNode.rowIndex)" readonly="readonly" /></td>';
print '<input type="text" value="'.odbc_result($RS, "UNIT_COST").'" onKeyDown="javascript:HandleGridMatchKeyEvent(event,this.parentNode.parentNode.rowIndex);" ondblclick="EDIT_PRICE_OVERLAY(this.parentNode.parentNode.rowIndex)" readonly="readonly" /></td>';
print '<input type="text" value="'.odbc_result($RS, "UNIT_PRICE").'" onKeyDown="javascript:HandleGridMatchKeyEvent(event,this.parentNode.parentNode.rowIndex);" ondblclick="EDIT_PRICE_OVERLAY(this.parentNode.parentNode.rowIndex)" readonly="readonly" /></td>';
print '<input type="text" value="'.odbc_result($RS, "UNIT_PRICE").'" id="UNIT_PRICE'.$ROWCOUNT.'" onKeyDown="javascript:HandleGridMatchKeyEvent(event,this.parentNode.parentNode.rowIndex);" ondblclick="EDIT_PRICE_OVERLAY(this.parentNode.parentNode.rowIndex)" readonly="readonly" /></td>';
print '<input type="text" value="'.odbc_result($RS, "PRICE_VAR").'" onKeyDown="javascript:HandleGridMatchKeyEvent(event,this.parentNode.parentNode.rowIndex);" ondblclick="EDIT_PRICE_OVERLAY(this.parentNode.parentNode.rowIndex)" readonly="readonly" /></td>';
print '<input type="text" value="'.odbc_result($RS, "QUANTITY")*odbc_result($RS, "UNIT_PRICE").'" onKeyDown="javascript:HandleGridMatchKeyEvent(event,this.parentNode.parentNode.rowIndex);" ondblclick="EDIT_PRICE_OVERLAY(this.parentNode.parentNode.rowIndex)" readonly="readonly" /></td>';
print '<input type="text" value="14" onKeyDown="javascript:HandleGridMatchKeyEvent(event,this.parentNode.parentNode.rowIndex);" ondblclick="EDIT_PRICE_OVERLAY(this.parentNode.parentNode.rowIndex)" readonly="readonly" /></td>';
print '<input type="text" value="0" onKeyDown="javascript:HandleGridMatchKeyEvent(event,this.parentNode.parentNode.rowIndex);" ondblclick="EDIT_PRICE_OVERLAY(this.parentNode.parentNode.rowIndex)" readonly="readonly" /></td>';
print '<input type="text" value="'.(odbc_result($RS, "QUANTITY")*odbc_result($RS, "UNIT_PRICE"))*0.14.'" onKeyDown="javascript:HandleGridMatchKeyEvent(event,this.parentNode.parentNode.rowIndex);" ondblclick="EDIT_PRICE_OVERLAY(this.parentNode.parentNode.rowIndex)" readonly="readonly" /></td>';
print '<input type="text" value="0" onKeyDown="javascript:HandleGridMatchKeyEvent(event,this.parentNode.parentNode.rowIndex);" ondblclick="EDIT_PRICE_OVERLAY(this.parentNode.parentNode.rowIndex)" readonly="readonly" /></td>';
print '<input type="text" value="0" onKeyDown="javascript:HandleGridMatchKeyEvent(event,this.parentNode.parentNode.rowIndex);" ondblclick="EDIT_PRICE_OVERLAY(this.parentNode.parentNode.rowIndex)" readonly="readonly" /></td>';
print '<input type="text" value="1" onKeyDown="javascript:HandleGridMatchKeyEvent(event,this.parentNode.parentNode.rowIndex);" ondblclick="EDIT_PRICE_OVERLAY(this.parentNode.parentNode.rowIndex)" readonly="readonly" /></td>';
print '<input type="text" value="'.(odbc_result($RS, "QUANTITY")*odbc_result($RS, "UNIT_PRICE"))*0.01.'" onKeyDown="javascript:HandleGridMatchKeyEvent(event,this.parentNode.parentNode.rowIndex);" ondblclick="EDIT_PRICE_OVERLAY(this.parentNode.parentNode.rowIndex)" readonly="readonly" /></td>';
print '<input type="text" value="0" onKeyDown="javascript:HandleGridMatchKeyEvent(event,this.parentNode.parentNode.rowIndex);" ondblclick="EDIT_PRICE_OVERLAY(this.parentNode.parentNode.rowIndex)" readonly="readonly" /></td>';
print '<input type="text" value="0" onKeyDown="javascript:HandleGridMatchKeyEvent(event,this.parentNode.parentNode.rowIndex);" ondblclick="EDIT_PRICE_OVERLAY(this.parentNode.parentNode.rowIndex)" readonly="readonly" /></td>';
print '<input type="text" value="0" onKeyDown="javascript:HandleGridMatchKeyEvent(event,this.parentNode.parentNode.rowIndex);" ondblclick="EDIT_PRICE_OVERLAY(this.parentNode.parentNode.rowIndex)" readonly="readonly" /></td>';
print '<input type="text" value="'.odbc_result($RS, "RECEIPT_NUM").'" onKeyDown="javascript:HandleGridMatchKeyEvent(event,this.parentNode.parentNode.rowIndex);" ondblclick="EDIT_PRICE_OVERLAY(this.parentNode.parentNode.rowIndex)" readonly="readonly" /></td>';
print '<input type="text" value="'.odbc_result($RS, "RECEIPT_DOC_NUM").'" onKeyDown="javascript:HandleGridMatchKeyEvent(event,this.parentNode.parentNode.rowIndex);" ondblclick="EDIT_PRICE_OVERLAY(this.parentNode.parentNode.rowIndex)" readonly="readonly" /></td>';
print '<input type="text" value="'.odbc_result($RS, "ITEM").'" onKeyDown="javascript:HandleGridMatchKeyEvent(event,this.parentNode.parentNode.rowIndex);" ondblclick="EDIT_PRICE_OVERLAY(this.parentNode.parentNode.rowIndex)" readonly="readonly" /></td>';
print '<input type="text" value="'.odbc_result($RS, "VENDOR").'" onKeyDown="javascript:HandleGridMatchKeyEvent(event,this.parentNode.parentNode.rowIndex);" ondblclick="EDIT_PRICE_OVERLAY(this.parentNode.parentNode.rowIndex)" readonly="readonly" /></td>';
print '<input type="hidden" id="ORG_ID" value="'.odbc_result($RS, "ORG_ID").'"  />';
print '<input type="hidden" id="PO_HEADER_ID"  			value="'.odbc_result($RS, "PO_HEADER_ID").'"  />';
print '<input type="hidden" id="PO_LINE_ID"  				value="'.odbc_result($RS, "PO_LINE_ID").'"  />';
print '<input type="hidden" id="TRANSACTION_ID"  			value="'.odbc_result($RS, "TRANSACTION_ID").'"  />';
print '<input type="hidden" id="ITEM_ID"  				value="'.odbc_result($RS, "ITEM_ID").'"  />';
print '<input type="hidden" id="INV_PERIOD"  				value="'.$period.'"  />';
print '<input type="hidden" id="VENDOR_CODE"  			value="'.odbc_result($RS, "VENDOR_NUM").'"  />';
print '<input type="hidden" id="MATCH_TYPE_ID"  			value="'.odbc_result($RS, "TERM_INST_TYPE").'"  />';
print '<input type="hidden" id="GLACCOUNT"  				value="'.odbc_result($RS, "VENDOR_ACCOUNT").'"  />';
print '<input type="hidden" id="COSTCENTER"  				value=""  />';
print '<input type="hidden" id="PENALTY_GLACCOUNT"  		value=""  />';
print '<input type="hidden" id="PENALTY_GLCOSTCENTER"  	value=""  />';
print '<input type="hidden" id="PAYMENT_LINE_ID"  	value="'.odbc_result($RS, "PAYMENT_LINE_ID").'"  />';
print '<input type="hidden" id="TAX_DATA"  	value="'.$TAX_DATA.'"  />';
print '<input type="hidden" id="PAYMENT_SCHEDULE_ID"  	value="'.odbc_result($RS, "PAYMENT_SCHEDULE_ID").'"  />';

print '</td>';
print odbc_result($RS, "PAYMENT_SCHEDULE_ID").odbc_result($RS, "PO_LINE_ID").odbc_result($RS, "TRANSACTION_ID").odbc_result($RS, "QUANTITY").'</td>';

/*
//for validation
print odbc_result($RS, "PO_LINE_ID").'</td>';
print '0</td>'; //TRANSACTION_ID
print odbc_result($RS, "ITEM").'</td>';
print odbc_result($RS, "QUANTITY").'</td>';
print odbc_result($RS, "PRIMARY_UOM_CODE").'</td>';
*/



print '</tr>';

}

//-- CLOSE DATABASE CONNECTION
odbc_close($CONNECT);
exit();
}
//RETURN NULL RECORD WHERE VENDOR ENABLED FLAG==N
else
{
$check_flag='N';
print '</td>';print '</td>';print '</td>';print '</td>';print '</td>';print '</td>';print '</td>';print '</td>';print '</td>';print '</td>';print '</td>';print '</td>';print '</td>';print '</td>';print '</td>';print '</td>';print '</td>';print '</td>';print '</td>';print '</td>';print '</td>';print '</td>';print '</td>';print '</td>';print '</td>';
print $check_flag.'</td>';
print '</tr>';
}
}
?>
<?php  
//<!---------------------------------------------------------------------------------------------> 
if (isset($action) && ($action =="GET_PAY_TYPES")) {
	
header('Content-Type:text/html; charset=windows-1256');	
$SQL = "SELECT LOOKUP_CODE , DESCRIPTION  FROM AP_APPS.FND_LOOKUP_VALUES   WHERE LOOKUP_TYPE = 'AP_PAYMENT_TERMS' AND NVL(END_DATE_ACTIVE,SYSDATE+1) > SYSDATE ORDER BY LOOKUP_CODE";

$RS  = odbc_exec($CONNECT , $SQL);
print "<option value='0'>--</option>";
while(odbc_fetch_row($RS)){
print   "<option value='".odbc_result($RS, 1)."'>".odbc_result($RS, 1)." - ".odbc_result($RS, 2)."</option>";
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
print   "<option value='".odbc_result($RS, 1)."'>".odbc_result($RS, 1)." </option>";
}


//-- CLOSE DATABASE CONNECTION
odbc_close($CONNECT);
exit();
}
?>
<?php  
//<!---------------------------------------------------------------------------------------------> 
if (isset($action) && ($action =="GET_SUBINVENTORIES")) {
	
header('Content-Type:text/html; charset=windows-1256');	
$SQL = "SELECT * FROM INV_SUBINVENTORIES WHERE ORGANIZATION_ID = ".$ORGANIZATION_ID."  ORDER BY 2";

$RS  = odbc_exec($CONNECT , $SQL);
print "<option value='0'>--</option>";
while(odbc_fetch_row($RS)){
print   "<option value='".odbc_result($RS, 2)."'>".odbc_result($RS, 2)." - ".odbc_result($RS, 3)." </option>";
}
print   "<option value='IPROC'>i-Procurement</option>";


//-- CLOSE DATABASE CONNECTION
odbc_close($CONNECT);
exit();
}
?>
<?php  
//<!---------------------------------------------------------------------------------------------> 
if (isset($action) && ($action =="GET_PURCHASE_ORDERS")) {
	
header('Content-Type:text/html; charset=windows-1256');	
$SQL = "SELECT DISTINCT PO_HEADER_ID,PO_NUM FROM AP_APPS.PO_APPROVED WHERE (NVL(QUANTITY,0) - NVL(QUANTITY_BILLED,0))>0 AND ORG_ID=".$ORG_ID." AND VENDOR_NUM='".$vendor_id."' ORDER BY PO_HEADER_ID DESC";

$RS  = odbc_exec($CONNECT , $SQL);
print "<option value='0'>--</option>";
while(odbc_fetch_row($RS)){
print   "<option value='".odbc_result($RS, 1)."'>".odbc_result($RS, 2)."</option>";
}


//-- CLOSE DATABASE CONNECTION
odbc_close($CONNECT);
exit();
}
?>
<?php  
//<!---------------------------------------------------------------------------------------------> 
if (isset($action) && ($action =="GET_SHIPMENTS")) {
	
header('Content-Type:text/html; charset=windows-1256');	
$SQL = "SELECT BATCH_NAME FROM AP.AP_BATCHES_ALL WHERE  ATTRIBUTE_CATEGORY = 'Shipment Details' AND ATTRIBUTE1='".$vendor_id."' ";

$RS  = odbc_exec($CONNECT , $SQL);
print "<option value='0'>--</option>";
while(odbc_fetch_row($RS)){
print   "<option value='".odbc_result($RS, 1)."'>".odbc_result($RS, 1)."</option>";
}


//-- CLOSE DATABASE CONNECTION
odbc_close($CONNECT);
exit();
}
?>
<?php  
/*<!---------------------------------------------------------------------------------------------> */
if (isset($action) && ($action =="GET_RECEIPTS")) {
header('Content-Type:text/html; charset=windows-1256');	
if ($matchtype==4){
$SQL = "SELECT DISTINCT RECEIPT_DOC_NUM
 FROM AP_APPS.PO_DELIVERED_BILL_FOLLOW_UP
 WHERE NVL(QUANTITY_REMAIN,0)> 0 AND ORGANIZATION_ID =  ".$ORGANIZATION_ID;
 if ((isset($period) && ($period != '0')&& ($PO_NUM_TXT=='0'))||(isset($SUB_INV) && ($SUB_INV != '0')&& ($PO_NUM_TXT=='0'))){$SQL=$SQL."  AND SUBINVENTORY_CODE = '".$SUB_INV."' AND TO_CHAR (TRANSACTION_DATE, 'MON-YY',  'NLS_DATE_LANGUAGE=AMERICAN') = '".$period."'";}
 if (isset($PO_ID) && ($PO_ID != '0')&& ($PO_NUM_TXT=='0')){$SQL=$SQL."  AND PO_HEADER_ID=".$PO_ID;}
 if (isset($PO_NUM_TXT)&& ($PO_NUM_TXT!='0')){$SQL=$SQL."  AND PO_num like '%".$PO_NUM_TXT."'";}
 $SQL=$SQL."  ORDER BY RECEIPT_DOC_NUM";

}


if (($matchtype==2) && isset($PO_ID) && ($PO_ID != '0')){
$SQL = "SELECT DISTINCT RECEIPT_NUM 
 FROM AP_APPS.PO_RECEIVED
 WHERE ORGANIZATION_ID =  ".$ORGANIZATION_ID;
 if (isset($PO_ID) && ($PO_ID != '0')){$SQL=$SQL."  AND PO_HEADER_ID=".$PO_ID;}
 $SQL=$SQL."  ORDER BY TO_CHAR(RECEIPT_NUM)";
}

if (($matchtype==3) && isset($PO_ID) && ($PO_ID != '0')){
$SQL = "SELECT DISTINCT RECEIPT_NUM 
 FROM AP_APPS.PO_ACCEPTED_DTL
 WHERE ORGANIZATION_ID =  ".$ORGANIZATION_ID;
 $SQL=$SQL."  AND PO_HEADER_ID=".$PO_ID;
 //if (isset($PO_ID) && ($PO_ID != '0')){$SQL=$SQL."  AND PO_HEADER_ID=".$PO_ID;}
 $SQL=$SQL."  ORDER BY TO_CHAR(RECEIPT_NUM)";
}

if ($SUB_INV == 'IPROC'){
 $SQL = "SELECT DISTINCT NVL(RCV.ATTRIBUTE2,TO_CHAR(SH.RECEIPT_NUM)) 
 FROM PO.RCV_TRANSACTIONS RCV 
 INNER JOIN RCV_SHIPMENT_HEADERS SH ON SH.SHIPMENT_HEADER_ID = RCV.SHIPMENT_HEADER_ID  
 INNER JOIN PO.PO_HEADERS_ALL H ON RCV.PO_HEADER_ID = H.PO_HEADER_ID 
 WHERE RCV.ORGANIZATION_ID =  ".$ORGANIZATION_ID."
 AND upper(TO_CHAR(H.SEGMENT1)) like '".$SUB_INV."%'
  AND RCV.TRANSACTION_TYPE = 'DELIVER'
 AND TO_CHAR (RCV.TRANSACTION_DATE, 'MON-YY',   'NLS_DATE_LANGUAGE=AMERICAN') = '".$period."' ORDER BY NVL(RCV.ATTRIBUTE2,TO_CHAR(SH.RECEIPT_NUM))";
}



$RS  = odbc_exec($CONNECT , $SQL);
print "<option value='0'>-- </option>";
while(odbc_fetch_row($RS)){
print   "<option value='".odbc_result($RS, 1)."'>".odbc_result($RS, 1)." </option>";
}

//-- CLOSE DATABASE CONNECTION
odbc_close($CONNECT);
exit();
}
?>
<?php
//<!---------------------------------------------------------------------------------------------> 
if (isset($action) && ($action =="SEARCH_VENDOR_GET_RESULT")) {
$COUNT_MYRS = 0;	
header('Content-Type:text/html; charset=windows-1256');
print '<div  style="height:150px;  width:760px; overflow:scroll" >';

print '<table border="1" cellspacing="0" cellpadding="0" id="items_results">';
print '<thead>';
print '<tr>';
print '<th width="15%">Code</th>';
print '<th width="25%">Name</th>';
print '<th width="15%">Tax File</th>';
print '<th width="15%">Tax Reg.</th>';
print '<th>Site</th>';
print '<th>Account</th>';

print '</tr>';
print '</thead>';

$sql = "SELECT A.SEGMENT1, A.VENDOR_NAME, B.VENDOR_SITE_CODE, C.SEGMENT2,B.ATTRIBUTE2,B.ATTRIBUTE3
FROM AP.AP_SUPPLIERS A
INNER JOIN AP.AP_SUPPLIER_SITES_ALL B ON A.VENDOR_ID= B.VENDOR_ID
LEFT OUTER JOIN GL.GL_CODE_COMBINATIONS C ON B.ACCTS_PAY_CODE_COMBINATION_ID = C.CODE_COMBINATION_ID

WHERE  NVL(A.END_DATE_ACTIVE,SYSDATE+1) > SYSDATE
 AND A.enabled_flag = 'Y' AND B.ORG_ID=  ".$ORG_ID." and (A.SEGMENT1 LIKE '".$search_item."' OR UPPER(A.VENDOR_NAME) LIKE UPPER('".$search_item_1."') OR  UPPER(B.VENDOR_SITE_CODE) LIKE UPPER('".$search_item_1."') )
order by A.SEGMENT1";

$res = odbc_exec($CONNECT, $sql);
print '<tbody>';
while(odbc_fetch_row($res)){
	$COUNT_MYRS = $COUNT_MYRS + 1;
	print '<tr>';
	print '<td style="background:#FFFFFF; width:15% "><a href="#" onclick="javascript:SELECT_VENDOR(';
	print "'".odbc_result($res, 1)."',";
	print "'".odbc_result($res, 2)."'";
	print ')">'.odbc_result($res, 1).'</a></td>';
	print '<td style="background:#FFFFFF; width:26% ">'.odbc_result($res, 2).'</td>';
	print '<td style="background:#FFFFFF; width:15%  ">'.odbc_result($res, 5).'</td>';
	print '<td style="background:#FFFFFF; width:15%  ">'.odbc_result($res, 6).'</td>';
	print '<td style="background:#FFFFFF; ">'.odbc_result($res, 3).'</td>';
	print '<td style="background:#FFFFFF; ">'.odbc_result($res, 4).'</td>';
	print '</tr>';
}
print '</tbody>';
print '</table>';

if ($COUNT_MYRS==0){
	print '<h3 class="blink_me" style="color:red">no data found for your search criteria ... !</h3>';
}
print '</div>';
//-- CLOSE DATABASE CONNECTION
odbc_close($CONNECT);

exit();
}
?>
<?php
//<!---------------------------------------------------------------------------------------------> 
if (isset($action) && ($action =="SEARCH_VENDOR_NAME")) {

header('Content-Type:text/html; charset=windows-1256');

$sql = "SELECT  A.VENDOR_NAME
FROM AP.AP_SUPPLIERS A
INNER JOIN AP.AP_SUPPLIER_SITES_ALL B ON A.VENDOR_ID= B.VENDOR_ID
LEFT OUTER JOIN GL.GL_CODE_COMBINATIONS C ON B.ACCTS_PAY_CODE_COMBINATION_ID = C.CODE_COMBINATION_ID

WHERE  NVL(A.END_DATE_ACTIVE,SYSDATE+1) > SYSDATE
 AND A.enabled_flag = 'Y' AND B.ORG_ID=  ".$ORG_ID." and A.SEGMENT1 LIKE '".$search_item_1."' 
order by A.SEGMENT1";

$RESULT ='No data found';
$res = odbc_exec($CONNECT, $sql);
while(odbc_fetch_row($res)){	
$RESULT = odbc_result($res, 1);
}
print $RESULT;
//-- CLOSE DATABASE CONNECTION
odbc_close($CONNECT);

exit();
}
?>