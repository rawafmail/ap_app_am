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
 //$src1="../Libfn/images/amriyalogo.png";
 $src1="../NewLib/112.jpg";
}  
elseif($ORG_ID==111)
{
  //$src1="../Libfn/images/EuropeanLogo.jpg";
  $src1="../NewLib/113.jpg";
}
elseif($ORG_ID==156)
{
	//$src1="../../Libfn/images/pharcologo.png";
	$MY_COMPANY_TITLE='Pharco Pharmaceutical Ind.';
	$ORGANIZATION_ID = 155;
	$src1="../NewLib/155.jpg";
	$DB_LINK ='@PH.PHARCOERP.COM';
}
elseif($ORG_ID==656)
{
	$MY_COMPANY_TITLE='Pharco-B Pharmaceutical Ind.';
	$ORGANIZATION_ID = 635;		
	$DB_LINK ='@PBI.PHARCOERP.COM';
	$src1="../NewLib/635.jpg";
}

elseif($ORG_ID==615)
{
	$MY_COMPANY_TITLE='Techopharma Pharmaceutical Ind.';
	$ORGANIZATION_ID = 675;	
	$DB_LINK ='@TECHNO_DB.PHARCOERP.COM';
	$src1="../NewLib/615.jpg";
}
elseif($ORG_ID==776)
{
	$MY_COMPANY_TITLE='Safe Pharmaceutical Ind.';
    $ORGANIZATION_ID = 796;
	$DB_LINK ='@SAFE70.PHARCOERP.COM';
	$src1="../NewLib/796.jpg";
}
elseif($ORG_ID==715)
{
	
$MY_COMPANY_TITLE='Pharco-B Chemicals Ind.';
$ORGANIZATION_ID = 735;		
$DB_LINK ='@SARA_DB.PHARCOERP.COM';
$src1="../NewLib/735.jpg";
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
<title>Checks Delivered Report</title>
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
    var table = $('#example').DataTable({
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
    <td width="50%" style="font-size:18px;text-align:center"><b>Checks Delivered Report</td>
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



$SQL = "
SELECT COUNT (*) AS TOTAL_PAYMENT,
SUM (NET_AMOUNT) AS NET_AMOUNT,
--BANK_ACCOUNT_NUM,
--DESCRIPTION,
NVL(VENDOR_CODE,'OTHER') AS VENDOR_CODE,
VENDOR_NAME,
ORG_ID
FROM (SELECT B.PAYMENT_ID,
		B.VENDOR_CODE,
		--A.BANK_ACCOUNT_NUM,
		--C.DESCRIPTION,
		B.NET_AMOUNT,
		S.VENDOR_NAME,
		A.ORG_ID,
		TO_NUMBER (TO_CHAR (A.POST_DATE,'YYYYMMDD')) AS POST_DATE

   FROM AP_APPS.AP_APP_PAYMENTS_HEADERS".$DB_LINK." A
		INNER JOIN
		(  SELECT DISTINCT PAYMENT_ID,
						   VENDOR_CODE,
						   MATCH_TYPE_ID,
						   SUM (NET_AMOUNT)     AS NET_AMOUNT,
						   ORG_ID
			 FROM AP_APPS.AP_APP_PAYMENTS_DETAILS".$DB_LINK."
		 GROUP BY PAYMENT_ID,
				  VENDOR_CODE,
				  MATCH_TYPE_ID,
				  ORG_ID) B
			ON A.PAYMENT_ID = B.PAYMENT_ID AND A.ORG_ID = B.ORG_ID
		LEFT OUTER JOIN AP.AP_SUPPLIERS".$DB_LINK." S ON B.VENDOR_CODE = S.SEGMENT1	
  WHERE NVL (B.MATCH_TYPE_ID, 0) > 0 AND A.STATUS_CODE = 'POSTED' AND (A.REVERSE_JE_HEADER_ID IS NULL OR A.REVERSE_JE_HEADER_ID=0)
  AND TO_NUMBER (TO_CHAR (A.POST_DATE,'YYYYMMDD'))>=$FROM_DATE
  AND TO_NUMBER (TO_CHAR (A.POST_DATE,'YYYYMMDD'))<=$TO_DATE
  AND A.ORG_ID=$ORG_ID)
GROUP BY VENDOR_CODE,
VENDOR_NAME,
--BANK_ACCOUNT_NUM,
--DESCRIPTION,
ORG_ID
ORDER BY VENDOR_CODE";
// PRINT "<PRE>";
//  print $SQL;
// PRINT "</PRE>";
//exit;

print "<table border='1' cellspacing = '0' id='example' cellpadding='2px' align ='center' width=100%>";
		print '<thead>';		
		print '<tr>';	
		print '<th bgcolor="#B0C4DE" align="center" width="13%" style="padding-bottom:3px; padding-top:3px"><font size=2><b>Supplier Code</b></font></th>';
		print '<th bgcolor="#B0C4DE" align="center" width="13%" style="padding-bottom:3px; padding-top:3px"><font size=2><b>description </b></font></th>';
		print '<th bgcolor="#B0C4DE" align="center" width="13%" style="padding-bottom:3px; padding-top:3px"><font size=2><b>Amount </b></font></th>';
		print '<th bgcolor="#B0C4DE" align="center" width="13%" style="padding-bottom:3px; padding-top:3px"><font size=2><b>Total Payment </b></font> </th>';

		//print '<th bgcolor="#B0C4DE" align="center" width="13%" style="padding-bottom:3px; padding-top:3px"><font size=2><b>Bank Account </b></font></th>';
		//print '<th bgcolor="#B0C4DE" align="center" width="13%" style="padding-bottom:3px; padding-top:3px"><font size=2><b>Description </b></font></th>';
				print '</tr>';
		print '</thead>';
        print '<tbody>';
//---------------------------- Data

$RS = odbc_exec($CONNECT , $SQL);


//----------------- Display Data ----------------------	
while(odbc_fetch_row($RS)){
	$TOTAL_PAYMENT=odbc_result($RS,"TOTAL_PAYMENT");
	$NET_AMOUNT=odbc_result($RS,"NET_AMOUNT");
	//$BANK_ACCOUNT_NUM=odbc_result($RS,"BANK_ACCOUNT_NUM");
	//$DESCRIPTION=odbc_result($RS,"DESCRIPTION");
	$VENDOR_CODE=odbc_result($RS,"VENDOR_CODE");
	$VENDOR_NAME=odbc_result($RS,"VENDOR_NAME");
	print '<tr>';
	print '<td  style="text-align:CENTER "><font size=2>' .$VENDOR_CODE. '</font></td>';
	print '<td  style="text-align:LEFT "><font size=2>' .$VENDOR_NAME. '</font></td>';
	print '<td style="text-align:right;"><font size=2>'.number_format($NET_AMOUNT, 2, '.', ',').'</font></td>';
	print "<td align = 'left'  style='padding-left:15px'>";
	print "<a href='http://".$_SERVER['SERVER_NAME']."/IIS_WEB/AP_TEST/CHECK_POSTED_DETAILS.PHP?ORG_ID=$ORG_ID&FROM_DATE=$FROM_DATE&TO_DATE=$TO_DATE&VENDOR_CODE=$VENDOR_CODE&BANK_ACCOUNT_NUM=$BANK_ACCOUNT_NUM' target='_blank'>";
	print "<b><font size=2>".$TOTAL_PAYMENT."  </a></td>";
	//print '<td  style="text-align:CENTER "><font size=2>' .$BANK_ACCOUNT_NUM. '</font></td>';
	//print '<td  style="text-align:CENTER "><font size=2>' .$DESCRIPTION. '</font></td>';

	print '</tr>';	
}


//--------------------------------------------------------------------

		odbc_close($CONNECT); 
?>
</TBODY>
</table>
    <!--======================================== -->
    </td>
  </tr>

 
</table>
</body>
</html>