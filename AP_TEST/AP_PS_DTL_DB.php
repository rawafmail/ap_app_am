<?php

set_time_limit(50000);
//ini_set('display_errors', 'On');
error_reporting(E_ALL);
header("Expires: 0");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

//---------------------
//-- MAIN VARIABLES
$uid = $_POST["uid"];
$action = $_POST["action"];
$ORG_ID = $_POST["ORG_ID"];
$ORGANIZATION_ID =$_POST["ORGANIZATION_ID"];
$LEDGER_ID = $_POST["LEDGER_ID"];

//-- DATABASE CONNECTION 
$CONNECT = odbc_connect('PROD', 'apps', 'apps');
?>
<?php  
/*<!---------------------------------------------------------------------------------------------> */

if (isset($action) && ($action =="GET_PS_MATCH_DTL")) {
header('Content-Type:text/html; charset=windows-1256');

//==
$PAYMENT_SCHEDULE_ID_LIST ='';
$keyvalues =$_POST['PS_ID'];

if(!empty($keyvalues)) {
    foreach($keyvalues as $key => $value) {
        $PAYMENT_SCHEDULE_ID_LIST = $PAYMENT_SCHEDULE_ID_LIST.$value.","; 
    }
$PAYMENT_SCHEDULE_ID_LIST =substr($PAYMENT_SCHEDULE_ID_LIST,0,strlen($PAYMENT_SCHEDULE_ID_LIST)-1);
}

$SQL_M = "SELECT DISTINCT TERM_INST_TYPE FROM AP_APPS.AP_PAYMENTS_SCHEDULE_ALL WHERE PAYMENT_SCHEDULE_ID IN (".$PAYMENT_SCHEDULE_ID_LIST.")";


$RS_M  = odbc_exec($CONNECT , $SQL_M);
$SQL='';
$ROWCOUNT_M=0;
while(odbc_fetch_row($RS_M)){
$ROWCOUNT_M = $ROWCOUNT_M +1;

if ($ROWCOUNT_M > 1){$SQL = $SQL . " UNION ALL ";}
$matchtype=odbc_result($RS_M, "TERM_INST_TYPE");

if ($matchtype==1){	
$SQL = $SQL . " SELECT A.TERM_INST_TYPE,'PS' INV_NUM,TO_CHAR(SYSDATE,'YYYYMMDD') INV_DATE,'' RECEIPT_NUM,'' RECEIPT_DOC_NUM,0 UNIT_COST,(NVL (C.QUANTITY, 0) - NVL (C.BILLED_QTY, 0))   QUANTITY,A.PRIMARY_UOM_CODE,A.UNIT_PRICE,0 PRICE_VAR,(A.ITEM_CODE||' - '||A.DESCRIPTION)ITEM,A.ITEM_ID,(A.VENDOR_NUM||' - '||NVL(B.KNOWN_AS, A.VENDOR_NAME))VENDOR,A.VENDOR_NUM,A.PO_LINE_ID,A.PO_HEADER_ID,A.ORG_ID,A.VENDOR_ACCOUNT,0 TRANSACTION_ID,
 AP_APPS.AP_APP_TRANS_SET_S.NEXTVAL AS PAYMENT_LINE_ID,C.PAYMENT_SCHEDULE_ID  FROM  AP_APPS.PO_APPROVED A LEFT OUTER JOIN AR.HZ_PARTIES B ON A.PARTY_ID = B.PARTY_ID INNER JOIN  AP_APPS.AP_PAYMENTS_SCHEDULE_ALL C ON A.PO_HEADER_ID = C.PO_HEADER_ID AND A.PO_LINE_ID = C.PO_LINE_ID AND A.TERM_INST_TYPE = C.TERM_INST_TYPE
 WHERE (NVL(A.QUANTITY,0) - NVL(A.QUANTITY_BILLED,0))>0  AND (NVL (C.QUANTITY, 0) - NVL (C.BILLED_QTY, 0))>0  AND C.PAYMENT_SCHEDULE_ID IN (".$PAYMENT_SCHEDULE_ID_LIST.") AND A.ORG_ID =  ".$ORG_ID;
}


if ($matchtype==4){
$SQL = $SQL . " SELECT A.TERM_INST_TYPE,'PS' INV_NUM,TO_CHAR(SYSDATE,'YYYYMMDD') INV_DATE,A.RECEIPT_NUM,A.RECEIPT_DOC_NUM, (VALUE_RECEIVED/A.QUANTITY)UNIT_COST, (NVL (C.QUANTITY, 0) - NVL (C.BILLED_QTY, 0))QUANTITY,A.PRIMARY_UOM_CODE,A.UNIT_PRICE,0 PRICE_VAR,(A.ITEM_CODE||' - '||A.DESCRIPTION)ITEM,A.ITEM_ID,(A.VENDOR_NUM||' - '||NVL(B.KNOWN_AS, A.VENDOR_NAME))VENDOR,A.VENDOR_NUM,A.PO_LINE_ID,A.PO_HEADER_ID,".$ORG_ID." ORG_ID,A.VENDOR_ACCOUNT,A.TRANSACTION_ID, AP_APPS.AP_APP_TRANS_SET_S.NEXTVAL AS PAYMENT_LINE_ID,C.PAYMENT_SCHEDULE_ID 
 FROM  AP_APPS.PO_DELIVERED_BILL_FOLLOW_UP A LEFT OUTER JOIN AR.HZ_PARTIES B ON A.PARTY_ID = B.PARTY_ID INNER JOIN  AP_APPS.AP_PAYMENTS_SCHEDULE_ALL C ON A.PO_HEADER_ID = C.PO_HEADER_ID AND A.PO_LINE_ID = C.PO_LINE_ID AND A.TRANSACTION_ID = C.RCV_TRANSACTION_ID AND A.TERM_INST_TYPE = C.TERM_INST_TYPE
 WHERE NVL(A.QUANTITY_REMAIN,0) > 0 AND (NVL (C.QUANTITY, 0) - NVL (C.BILLED_QTY, 0))>0 AND C.PAYMENT_SCHEDULE_ID IN (".$PAYMENT_SCHEDULE_ID_LIST.")  AND A.ORGANIZATION_ID =  ".$ORGANIZATION_ID;
}

}
//==



$ROWCOUNT=0;
$RS  = odbc_exec($CONNECT , $SQL);
while(odbc_fetch_row($RS)){
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
