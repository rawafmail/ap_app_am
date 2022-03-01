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
$VENDOR_NUM = $_POST["VENDOR_NUM"];
$TAX_FILE = $_POST["TAX_FILE"];
$TAX_REG = $_POST["TAX_REG"];
$VENDOR_ID = $_POST["VENDOR_ID"];
$TAX_DEPT_ID = $_POST["TAX_DEPT_ID"];


//-- DATABASE CONNECTION 
$CONNECT = odbc_connect('PROD', 'apps', 'apps');
?>
<?php  
/*<!---------------------------------------------------------------------------------------------> */

if (isset($action) && ($action =="GET_VENDOR_TAX_DATA")) {
header('Content-Type:text/html; charset=windows-1256');


//========
$SQL ="SELECT A.VENDOR_ID,
(SELECT ATTRIBUTE1 FROM AP.AP_SUPPLIER_SITES_ALL WHERE VENDOR_ID = A.VENDOR_ID AND ATTRIBUTE1 IS NOT NULL AND ROWNUM=1 AND ORG_ID =".$ORG_ID.") AS TAX_DEPT,
(SELECT ATTRIBUTE2 FROM AP.AP_SUPPLIER_SITES_ALL WHERE VENDOR_ID = A.VENDOR_ID AND ATTRIBUTE2 IS NOT NULL AND ROWNUM=1 AND ORG_ID =".$ORG_ID.") AS TAX_FILE_NUM,
(SELECT ATTRIBUTE3 FROM AP.AP_SUPPLIER_SITES_ALL WHERE VENDOR_ID = A.VENDOR_ID AND ATTRIBUTE3 IS NOT NULL AND ROWNUM=1 AND ORG_ID =".$ORG_ID.") AS TAX_REG_NUM
FROM AP.AP_SUPPLIERS A
WHERE A.SEGMENT1 ='".$VENDOR_NUM."'";
$TAX_DATA='';
$RS1  = odbc_exec($CONNECT , $SQL);
while(odbc_fetch_row($RS1)){
$SQL ="SELECT * FROM AP_APPS.AP_APP_AP_TAX_DEPT ORDER BY TAXJURISDICTION_ID";
$TAX_DATA='';
$RS2  = odbc_exec($CONNECT , $SQL);
$TAX_DATA_DEPT ='<select name="TAX_DEPT_ID" id="TAX_DEPT_ID">';
$TAX_DATA_DEPT_LIST ='';
while(odbc_fetch_row($RS2)){
if (odbc_result($RS1, "TAX_DEPT")==odbc_result($RS2, "TAXJURISDICTION_ID")){
$TAX_DATA_DEPT_LIST = $TAX_DATA_DEPT_LIST."<option value='".odbc_result($RS2,"TAXJURISDICTION_ID")."'  selected>".odbc_result($RS2, "TAXJURISDICTION_ID")." - ".odbc_result($RS2, "NAME")." </option>";
}else{
$TAX_DATA_DEPT_LIST = $TAX_DATA_DEPT_LIST."<option value='".odbc_result($RS2,"TAXJURISDICTION_ID")."'  >".odbc_result($RS2, "TAXJURISDICTION_ID")." - ".odbc_result($RS2, "NAME")." </option>";

}
}
$TAX_DATA_DEPT =$TAX_DATA_DEPT.$TAX_DATA_DEPT_LIST.'</select>';
$TAX_DATA=$TAX_DATA.' <b><input name="VENDOR_ID" id="VENDOR_ID" type="hidden" value="'.odbc_result($RS1, "VENDOR_ID").'" />';
$TAX_DATA=$TAX_DATA.' <b>Tax Dept.:</b>'.$TAX_DATA_DEPT;
$TAX_DATA=$TAX_DATA.' <b>Tax File Num.:</b> <input name="TAX_FILE" id="TAX_FILE" type="text" value="'.odbc_result($RS1, "TAX_FILE_NUM").'" />';
$TAX_DATA=$TAX_DATA.'<br> <b>Tax Reg. Num.:</b> <input name="TAX_REG" id="TAX_REG" type="text" value="'.odbc_result($RS1, "TAX_REG_NUM").'" />';
$TAX_DATA=$TAX_DATA.'<input name="btnSaveTaxData" type="button" value="Update Tax Data" onclick="SAVE_VENDOR_TAX_DATA();" />';
}
//========	
print $TAX_DATA;

//-- CLOSE DATABASE CONNECTION
odbc_close($CONNECT);
exit();
}
?>
<?php  
/*<!---------------------------------------------------------------------------------------------> */

if (isset($action) && ($action =="SAVE_VENDOR_TAX_DATA")) {
header('Content-Type:text/html; charset=windows-1256');


//========
$SQL ="UPDATE AP.AP_SUPPLIER_SITES_ALL SET  ATTRIBUTE1='".$TAX_DEPT_ID."',ATTRIBUTE2='".$TAX_FILE."',ATTRIBUTE3='".$TAX_REG."' WHERE VENDOR_ID=".$VENDOR_ID." AND ORG_ID=".$ORG_ID;
$success  = odbc_exec($CONNECT , $SQL);
if (!$success) {
	print('Error Details:');
	print(odbc_errormsg($CONNECT)."\n");		
}else{
print 'DONE ...';
}

//-- CLOSE DATABASE CONNECTION
odbc_close($CONNECT);
exit();
}
?>
