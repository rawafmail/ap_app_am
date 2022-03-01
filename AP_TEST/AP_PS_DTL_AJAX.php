<script>
<!--------------------------------------------------------------------- -->
function GET_PS_MATCH_DTL(){
	var uid =  "<?php print $_REQUEST["uid"]; ?>";
	var ORG_ID = "<?php print $ORG_ID; ?>";
	var LEDGER_ID = "<?php print $LEDGER_ID; ?>";	
	var ORGANIZATION_ID =  "<?php print $ORGANIZATION_ID; ?>";
	
	MatchedJE_FLAG='';
	 // Create our XMLHttpRequest object
    var hr = new XMLHttpRequest();
    // Create some variables we need to send to our PHP file
    var url = "AP_PS_DTL_DB.php";
	var vars = "<?php $keyvalues =$_POST['PS_ID'];
$PAYMENT_SCHEDULE_ID_LIST ='';
if(!empty($keyvalues)) {
    foreach($keyvalues as $key => $value) {
        $PAYMENT_SCHEDULE_ID_LIST = $PAYMENT_SCHEDULE_ID_LIST.'PS_ID[]='.$value.'&'; 
    }
$PAYMENT_SCHEDULE_ID_LIST =substr($PAYMENT_SCHEDULE_ID_LIST,0,strlen($PAYMENT_SCHEDULE_ID_LIST)-1);
} 
print $PAYMENT_SCHEDULE_ID_LIST ;?>&ORGANIZATION_ID="+ORGANIZATION_ID+"&LEDGER_ID="+LEDGER_ID+"&ORG_ID="+ORG_ID+"&uid="+uid+"&action=GET_PS_MATCH_DTL";
    
	hr.open("POST", url, true);
  
	hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded; charset=utf-8");
	hr.setRequestHeader("Content-length", vars.length);
	hr.setRequestHeader("Connection", "close");
    // Access the onreadystatechange event for the XMLHttpRequest object
    hr.onreadystatechange = function() {
	    if(hr.readyState == 4 && hr.status == 200) {	
				    
			var return_data = hr.responseText;
			
			document.getElementById("status").innerHTML ='' ;	
			var datarows = return_data.split("</tr>");
			var datarows_count = datarows.length-1;
			var rowexist =0;
			
			for(var i = 0 ; i < datarows_count; i++) {
				if (i==0){MatchedJE_FLAG='Y';}
    			var datacols =datarows[i].split("</td>");
				var t = $('#recRCTGrid').DataTable();
				var errorscount=0;			
				
				t.column(24).nodes().each(function (cell, j) {
					if (t.cell(j, 24).data()==datacols[24]){
						rowexist = eval(rowexist)+1;					
						errorscount =1;
					}
				});
				if (errorscount ==1){continue;}
				
				t.row.add( [datacols[0],
							datacols[1],
							datacols[2],
							datacols[3],
							datacols[4],
							datacols[5],
							datacols[6],
							datacols[7],
							datacols[8],
							datacols[9],
							datacols[10],
							datacols[11],
							datacols[12],
							datacols[13],
							datacols[14],
							datacols[15],
							datacols[16],
							datacols[17],
							datacols[18],
							datacols[19],
							datacols[20],
							datacols[21],
							datacols[22],
							datacols[23],
							datacols[24]
							 ] ).draw( false );

   			}
			  
			  if (rowexist >0){alert('Found: '+rowexist+' rows already exists');}
       //If table is in the tab, you need to adjust headers when tab becomes visible
			  $($.fn.dataTable.tables(true)).DataTable()
			  .columns.adjust();
			  if (datarows_count>0){UpdateSummaryGrid();}		  
	    }			
    }
    // Send the data to PHP now... and wait for response
    hr.send(vars); // Actually execute the request
	document.getElementById("status").innerHTML = '<div class="alert-box warning"><span>GET TRANSACTION DETAILS/LINES: </span>please wait .....</div>';
			
}

<!--------------------------------------------- AP_APP_MAIN_AJAX -->

$(document).ready(function() {
var PS_SELECTION ='<?php print $_REQUEST["PS_FLAG"]; ?>';
if (PS_SELECTION=='Y'){	
document.getElementById("btnNew").click();
GET_PS_MATCH_DTL();
}
   
} );
</script>
