<?php

set_time_limit(50000);
//ini_set('display_errors', 'On');
error_reporting(E_ALL);
header("Expires: 0");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

//-- MAIN VARIABLES ===================

$ORG_ID = $_REQUEST["ORG_ID"];
$FROM_DATE = $_REQUEST["FROM_DATE"];
$TO_DATE = $_REQUEST["TO_DATE"];
$VENDOR_CODE = $_REQUEST["VENDOR_CODE"];
$BANK_ACCOUNT_NUM = $_REQUEST["BANK_ACCOUNT_NUM"];
$MY_COMPANY_TITLE = '';
//print $MONTH_MM;

switch ($COMPANY_CODE) {
   	case 91:
        $MY_COMPANY_TITLE='Amriya Pharmaceutical Ind.';
        break;
   	case 111:
        $MY_COMPANY_TITLE='European Egyptian Pharmaceutical Ind.';
		break;	
} 

if ($ORG_ID==91)
{
 $src1="../NewLib/112.jpg";
 $SERVER_NAME='192.168.100.11/IIS_WEB/Purchasing/po_local.php';
 $CORPORG=91;

}  
elseif($ORG_ID==111)
{
  //$src1="../Libfn/images/EuropeanLogo.jpg";
  $src1="../NewLib/113.jpg";
  $SERVER_NAME='192.168.100.11/IIS_WEB/Purchasing/po_local.php';
  $CORPORG=111;

}
elseif($ORG_ID==156)
{
	//$src1="../../Libfn/images/pharcologo.png";
	$src1="../NewLib/155.jpg";
	$MY_COMPANY_TITLE='Pharco Pharmaceutical Ind.';
	$ORGANIZATION_ID = 155;
	$CORPORG=1075;
	$DB_LINK ='@PH.PHARCOERP.COM';
	$SERVER_NAME='192.168.100.44/IIS_WEB/Purchasing/po_local_pharco.php';
}
elseif($ORG_ID==656)
{
	$src1="../NewLib/635.jpg";
	$MY_COMPANY_TITLE='Pharco-B Pharmaceutical Ind.';
	$ORGANIZATION_ID = 635;		
	$DB_LINK ='@PBI.PHARCOERP.COM';
	$CORPORG=1075;
	$SERVER_NAME='192.168.100.44/IIS_WEB/Purchasing/po_local_pharcob.php';
}

elseif($ORG_ID==615)
{
	$src1="../NewLib/615.jpg";
	$MY_COMPANY_TITLE='Techopharma Pharmaceutical Ind.';
	$ORGANIZATION_ID = 675;	
	$DB_LINK ='@TECHNO_DB.PHARCOERP.COM';
	$CORPORG=1075;
	$SERVER_NAME='192.168.100.44/IIS_WEB/Purchasing/techno_po_followup.php';
}
elseif($ORG_ID==776)
{
	$src1="../NewLib/796.jpg";
	$MY_COMPANY_TITLE='Safe Pharmaceutical Ind.';
    $ORGANIZATION_ID = 796;
	$DB_LINK ='@SAFE70.PHARCOERP.COM';
	$CORPORG=1075;
	$SERVER_NAME='192.168.100.44/IIS_WEB/Purchasing/Safe_po_followup.php';
}
elseif($ORG_ID==715)
{
$src1="../NewLib/735.jpg";
$MY_COMPANY_TITLE='Pharco-B Chemicals Ind.';
$ORGANIZATION_ID = 735;		
$DB_LINK ='@SARA_DB.PHARCOERP.COM';
}
else
{
  $src1="../../Libfn/images/pharcologo.png";
}
//=====================================


?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1256" />
<title>Checks Delivered Details Report</title>
<link rel="stylesheet" type="text/css" href="../lib2/DataTables/datatables.min.css"/>
<link rel="stylesheet" type="text/css" href="../lib3/bootstrap.min.css"/>

<script type="text/javascript" src="../lib2/jquery-3.3.1.js"></script>
<script type="text/javascript" src="../lib2/DataTables/datatables.min.js"></script>

<script>
$(document).ready(function() {
	$('#my_data_table').DataTable( {
	"bInfo" : false,
	"paging": false,
	"fixedHeader": true
	} );
} );

$(document).ready(function() {
    var table = $('#example').removeAttr('width').DataTable({
        columnDefs: [
            { width: 100, targets: 7 },
			{ width: 100, targets: 3 },
			{ width: 50, targets: 1 }
        ],
		// scrollY:        "300px",
        scrollX:        true,
        scrollCollapse: true,
	"fixedHeader": true,
	"hover": true,
	"bFilter": true,
	"bLengthChange": true,
	"bInfo" : false
	});
    $('#example tbody')
        .on( 'mouseenter', 'td', function () {
            var colIdx = table.cell(this).index().column;
            $( table.cells().nodes() ).removeClass( 'highlight' );
            $( table.column( colIdx ).nodes() ).addClass( 'highlight' );
        } );

} );


$(document).ready(function() {
    $('#example2').DataTable();
} );


</script>

</head>

<body style="margin-left:5%">
<table width="95%"  cellspacing="0" cellpadding="3px">
 </tr>
  <tr><td>
  <?php //require('../../libfn/user_info.php'); ?>
  </td></tr>
<tr><td bgcolor="#CCCCCC"></td></tr>
  <tr>
    <td>
  
    
  <table width="100%" cellspacing="0" cellpadding="0" style="font-family:Arial, Helvetica, sans-serif;">
  
  <tr>
    <td width="25%" ><img src='<?php print $src1; ?>' width="180px" height="60px" /></td>
    <td width="50%" style="font-size:18px;text-align:center"><b>Checks Delivered Details Report</td>
    <td width="25%" style="font-size:12px;">
    
    
<!------------------------------------------ -->    

<!------------------------------------------ -->
   

    </td>
  </tr>
</table>

</td>


  </tr>
  <tr><td bgcolor="#CCCCCC"></td></tr>
  <?php
  
	print '<tr>';
   print  '<td>';
   // <!--======================================== -->

//-- DATABASE CONNECTION 

$CONNECT = odbc_connect('PROD', 'apps', 'apps');



$SQL = "SELECT A.ORG_ID,
A.PAYMENT_ID,
B.VENDOR_CODE,
S.VENDOR_NAME,
A.CHEQUE_NUM              AS CHEQUE_NUM,
A.BANK_ACCOUNT_NUM        AS BANK_NUM,
C.DESCRIPTION ACCOUNT_DESC,
A.PAYMENT_DOC_NUM,
DECODE(A.CASH_FLAG,0,'Cheque',1,'Cash',2,'Bank Transfer',3,'Adjustment') AS PAYMENT_TYPE,
A.NOTES,
TO_CHAR(A.CHEQUE_MATURITY_DATE,'YYYY-MM-DD') CHEQUE_MATURITY_DATE,
TO_CHAR(A.POST_DATE,'YYYY-MM-DD') AS POST_DATE,
SUM(B.INV_VALUE) AS INV_VALUE,
SUM(B.SAL_TAX_VAL)            AS VAT_VAL,
SUM(B.AWT_VAL) AS AWT_VAL,
SUM(B.VAT_VAL)                 AS SAL_TAX_VAL,
SUM(B.PENALTY_AMOUNT) AS PENALTY_AMOUNT,
SUM(B.NET_AMOUNT) as NET_AMOUNT,
C.SEGMENT1                AS PO_NUM
--NVL(WF.APPROVER4_IS_SIGN,'NO') AS SIGNED1,
--NVL(WF.APPROVER5_IS_SIGN,'NO') AS SIGNED2,
--NVL(WF.RECIVED_FLAG,'NO') AS RECIVED_FLAG,
--NVL(WF.RECIVED_DATE,'') AS RECIVED_DATE--,
--NVL(PT.DOC_DESCRIPTION,'-') AS PAYMENT_TERM
FROM AP_APPS.AP_APP_PAYMENTS_HEADERS".$DB_LINK."  A
INNER JOIN AP_APPS.AP_APP_PAYMENTS_DETAILS".$DB_LINK." B
    ON A.PAYMENT_ID = B.PAYMENT_ID
INNER JOIN PO.PO_HEADERS_ALL".$DB_LINK." C ON B.PO_HEADER_ID = C.PO_HEADER_ID
--LEFT OUTER JOIN Pharco_GL_CALL_WF_History".$DB_LINK." WF ON A.JE_HEADER_ID=WF.WF_JOURNAL_HEADERID
--LEFT OUTER JOIN AP_APPS.AP_PO_TERMS_VW".$DB_LINK." PT ON PT.PO_HEADER_ID=B.PO_HEADER_ID
LEFT OUTER JOIN AP_SUPPLIERS".$DB_LINK." S ON B.VENDOR_CODE = S.SEGMENT1	
LEFT OUTER JOIN
		(SELECT A.FLEX_VALUE_MEANING, A.DESCRIPTION
		   FROM APPLSYS.FND_FLEX_VALUES_TL".$DB_LINK." A
				INNER JOIN APPLSYS.FND_FLEX_VALUES".$DB_LINK." B
					ON A.FLEX_VALUE_ID = B.FLEX_VALUE_ID
				INNER JOIN APPLSYS.FND_FLEX_VALUE_SETS".$DB_LINK." C
					ON B.FLEX_VALUE_SET_ID = C.FLEX_VALUE_SET_ID
		  WHERE C.FLEX_VALUE_SET_ID = 1014148 AND A.LANGUAGE = 'AR') C
			ON A.BANK_ACCOUNT_NUM = C.FLEX_VALUE_MEANING
WHERE NVL (B.MATCH_TYPE_ID, 0) > 0 AND A.STATUS_CODE = 'POSTED' AND (A.REVERSE_JE_HEADER_ID IS NULL OR A.REVERSE_JE_HEADER_ID=0)
AND A.ORG_ID=$ORG_ID
AND B.VENDOR_CODE='".$VENDOR_CODE."'
--AND A.BANK_ACCOUNT_NUM='".$BANK_ACCOUNT_NUM."'
AND TO_NUMBER (TO_CHAR (A.POST_DATE,'YYYYMMDD'))>=$FROM_DATE 
AND TO_NUMBER (TO_CHAR (A.POST_DATE,'YYYYMMDD'))<=$TO_DATE
GROUP BY
A.ORG_ID,
A.PAYMENT_ID,
B.VENDOR_CODE,
S.VENDOR_NAME,
A.CHEQUE_NUM ,
A.BANK_ACCOUNT_NUM,
C.DESCRIPTION,
A.PAYMENT_DOC_NUM,
A.CASH_FLAG,
A.NOTES,
A.CHEQUE_MATURITY_DATE,
A.POST_DATE,
C.SEGMENT1
--WF.APPROVER4_IS_SIGN,
--WF.APPROVER5_IS_SIGN
--WF.RECIVED_FLAG,
--WF.RECIVED_DATE--,
--PT.DOC_DESCRIPTION
ORDER BY PAYMENT_DOC_NUM";
// PRINT "<PRE>";
// print $SQL;
// PRINT "</PRE>";
//exit;

print "<table border='1' cellspacing = '0' id='example' cellpadding='2px' align ='center' width=100%>";
		print '<thead>';		
		print '<tr>';	
		print '<td bgcolor="#B0C4DE" align="center"  style="padding-bottom:3px; padding-top:3px"><font size=2><b>Vendor Code</b></font> </td>';
		print '<td bgcolor="#B0C4DE" align="center"  style="padding-bottom:3px; padding-top:3px"><font size=2><b>Name</b></font> </td>';
		print '<td bgcolor="#B0C4DE" align="center"  style="padding-bottom:3px; padding-top:3px"><font size=2><b>Bank Account </b></font></td>';
		print '<td bgcolor="#B0C4DE" align="center"  style="padding-bottom:3px; padding-top:3px"><font size=2><b>Description </b></font></td>';

		print '<td bgcolor="#B0C4DE" align="center"  style="padding-bottom:3px; padding-top:3px"><font size=2><b>Cheque Num </b></font></td>';
		print '<td bgcolor="#B0C4DE" align="center" style="padding-bottom:3px; padding-top:3px"><font size=2><b>Due Date</b></font></td>';
		print '<td bgcolor="#B0C4DE" align="center"  style="padding-bottom:3px; padding-top:3px"><font size=2><b>Payment Document </b></font></td>';
		print '<td bgcolor="#B0C4DE" align="center"  style="padding-bottom:3px; padding-top:3px"><font size=2><b>Description </b></font></td>';
		print '<td bgcolor="#B0C4DE" align="center"  style="padding-bottom:3px; padding-top:3px"><font size=2><b>Payment Type </b></font></td>';

		print '<td bgcolor="#B0C4DE" align="center" style="padding-bottom:3px; padding-top:3px"><font size=2><b>Post Date</b></font></td>';
		
		// print '<th bgcolor="#B0C4DE" align="center"  style="padding-bottom:3px; padding-top:3px"><font size=2><b>Cheque Signed 1</b></font></th>';
		// print '<th bgcolor="#B0C4DE" align="center"  style="padding-bottom:3px; padding-top:3px"><font size=2><b>Cheque Signed 2</b></font></th>';
		// print '<th bgcolor="#B0C4DE" align="center" style="padding-bottom:3px; padding-top:3px"><font size=2><b>Cheque Received</b></font></th>';
		//print '<th bgcolor="#B0C4DE" align="center" style="padding-bottom:3px; padding-top:3px"><font size=2><b>Payment Term</b></font></th>';

		print '<td bgcolor="#B0C4DE" align="center"  style="padding-bottom:3px; padding-top:3px"><font size=2><b>Invoice Value</b></font></td>';
		print '<td bgcolor="#B0C4DE" align="center"  style="padding-bottom:3px; padding-top:3px"><font size=2><b>VAT Value </b></font></td>';
		print '<td bgcolor="#B0C4DE" align="center"  style="padding-bottom:3px; padding-top:3px"><font size=2><b>AWT Value </b></font></td>';
		print '<td bgcolor="#B0C4DE" align="center"  style="padding-bottom:3px; padding-top:3px"><font size=2><b>Sales Tax Value </b></font></td>';
		print '<td bgcolor="#B0C4DE" align="center"  style="padding-bottom:3px; padding-top:3px"><font size=2><b> Penalty Value </b></font></td>';
		print '<td bgcolor="#B0C4DE" align="center"  style="padding-bottom:3px; padding-top:3px"><font size=2><b>Net Amount </b></font></td>';
		print '<td bgcolor="#B0C4DE" align="center"  style="padding-bottom:3px; padding-top:3px"><font size=2><b>PO</b></font></td>';
		print '</tr>';
		print '</thead>';
        print '<tbody>';
//---------------------------- Data

$RS = odbc_exec($CONNECT , $SQL);
$TOTAL_AMOUNT=0;

//----------------- Display Data ----------------------	
while(odbc_fetch_row($RS)){

    $VENDOR_CODE=odbc_result($RS,"VENDOR_CODE");
	$VENDOR_NAME=odbc_result($RS,"VENDOR_NAME");
	$CHEQUE_NUM=odbc_result($RS,"CHEQUE_NUM");
	$BANK_NUM=odbc_result($RS,"BANK_NUM");
	$PAYMENT_DOC_NUM=odbc_result($RS,"PAYMENT_DOC_NUM");
	$NOTES=odbc_result($RS,"NOTES");
	$CHEQUE_MATURITY_DATE=odbc_result($RS,"CHEQUE_MATURITY_DATE");
	$INV_VALUE=odbc_result($RS,"INV_VALUE");
	$VAT_VAL=odbc_result($RS,"VAT_VAL");
	$AWT_VAL=odbc_result($RS,"AWT_VAL");
	$SAL_TAX_VAL=odbc_result($RS,"SAL_TAX_VAL");
	$PENALTY_AMOUNT=odbc_result($RS,"PENALTY_AMOUNT");
	$NET_AMOUNT=odbc_result($RS,"NET_AMOUNT");
	$PO_NUM=odbc_result($RS,"PO_NUM");
	$POST_DATE=odbc_result($RS,"POST_DATE");
	$ACCOUNT_DESC=odbc_result($RS,"ACCOUNT_DESC");
	$SIGNED1=odbc_result($RS,"SIGNED1");
	$SIGNED2=odbc_result($RS,"SIGNED2");
	$RECIVED_DATE=odbc_result($RS,"RECIVED_DATE");
	//$PAYMENT_TERM=odbc_result($RS,"PAYMENT_TERM");
	$PAYMENT_TYPE=odbc_result($RS,"PAYMENT_TYPE");
	$TOTAL_AMOUNT=$TOTAL_AMOUNT + $NET_AMOUNT;

	print '<tr>';

	print '<td  style="text-align:RIGHT "><font size=2>' .$VENDOR_CODE. '</font></td>';
	print '<td  style="text-align:RIGHT "><font size=2>' .$VENDOR_NAME. '</font></td>';
	print '<td  style="text-align:CENTER "><font size=2>' .$BANK_NUM. '</font></td>';
	print '<td  style="text-align:CENTER "><font size=2>' .$ACCOUNT_DESC. '</font></td>';
	print '<td  style="text-align:CENTER "><font size=2>' .$CHEQUE_NUM. '</font></td>';
	Print '<td  style="text-align:CENTER "><font size=2>' .$CHEQUE_MATURITY_DATE. '</font></td>';
	print '<td  style="text-align:CENTER "><font size=2>' .$PAYMENT_DOC_NUM. '</font></td>';
	print '<td  style="text-align:CENTER "><font size=2>' .$NOTES. '</font></td>';
	print '<td  style="text-align:CENTER "><font size=2>' .$PAYMENT_TYPE. '</font></td>';
	Print '<td  style="text-align:CENTER "><font size=2>' .$POST_DATE. '</font></td>';
	// Print '<td  style="text-align:CENTER "><font size=2>' .$SIGNED1. '</font></td>';
	// Print '<td  style="text-align:CENTER "><font size=2>' .$SIGNED2. '</font></td>';
	// Print '<td  style="text-align:CENTER "><font size=2>' .$RECIVED_DATE. '</font></td>';
	//Print '<td  style="text-align:CENTER "><font size=2>' .$PAYMENT_TERM. '</font></td>';
	print '<td style="text-align:right;"><font size=2>'.number_format($INV_VALUE, 0, '.', ',').'</font></td>';
	print '<td style="text-align:right;"><font size=2>'.number_format($VAT_VAL, 0, '.', ',').'</font></td>';
	print '<td style="text-align:right;"><font size=2>'.number_format($AWT_VAL, 0, '.', ',').'</font></td>';
	print '<td style="text-align:right;"><font size=2>'.number_format($SAL_TAX_VAL, 0, '.', ',').'</font></td>';
	print '<td style="text-align:right;"><font size=2>'.number_format($PENALTY_AMOUNT, 0, '.', ',').'</font></td>';
	print '<td style="text-align:right;"><font size=2>'.number_format($NET_AMOUNT, 0, '.', ',').'</font></td>';

	print "<td align = 'left'  style='padding-left:15px'>";
	print "<a href='http://".$SERVER_NAME."?org_id=$CORPORG&f_po=$PO_NUM' target='_blank'>";
	print "<b><font size=2>".$PO_NUM."  </a></td>";

	//print '<td  style="text-align:LEFT "><font size=2>' .$PO_NUM. '</font></td>';
	print '</tr>';	
}


//--------------------------------------------------------------------

		odbc_close($CONNECT); 
?>
</TBODY>
<TFOOT>
<?PHP
echo '<td COLSPAN="15">Total</td>';

print '<td style="text-align:right;"><B><font size=2>'.number_format($TOTAL_AMOUNT, 0, '.', ',').'</font></B></td>';
echo '<td></td>';

?>
</TFOOT>
</table>
    <!--======================================== -->
    </td>
  </tr>

 
</table>
</body>
</html>