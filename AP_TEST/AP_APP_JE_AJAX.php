<script>
<!--------------------------------------------------------------------- -->

$(document).ready(function() {
    $('#recJEGrid').DataTable( {
		

		"paging":   false,
        "ordering": false,
        "info":     false,
		keys: true,
		"searching": false,
        "scrollY": 200,
        "scrollX": true
    } );
$('#recJEGrid').on('key-focus', function (e, datatable, cell) {
    var inputFieldInSelectedTd = $(cell.node()).find('input');
    if (inputFieldInSelectedTd) {
      inputFieldInSelectedTd.focus();	  
   }
});	
	
} );
<!------------------------------------------------------------------------------------------------> 
var JE_ROW_IDX = -1;
<!------------------------------------------------------------------------------------------------> 

function ClearJE(contain){
	var oForm = document.getElementById(contain);
	var frm_elements = oForm.elements;
	for (i = 0; i < frm_elements.length; i++){
		field_type = frm_elements[i].type.toLowerCase();
		switch (field_type)
		{
		case "text":
		case "password":
		case "textarea":
		case "hidden":
			frm_elements[i].value = "0";
			break;		
		case "select-one":
		case "select-multi":
			frm_elements[i].selectedIndex = 0;
			break;
		default:
			break;
		}
	}
} 
<!------------------------------------------------------------------------------------------------> 
function VaildateHeader_ManualJE() {
	if (JE_HEADER_ID !=0){
		document.getElementById("status").innerHTML = '<div class="alert-box errormsg">You could not update records which have a posted journal entry.</div>';
		return false;	
	}
	if (document.getElementById('cboCurr').selectedIndex < 1){
		alert('Vaildation: No Currency.');
		document.getElementById('cboCurr').focus();
		return false;	
	}
	var txtNotes = document.getElementById("txtNotes");
	if ((txtNotes.value =='')||(txtNotes.value.trim().length==0)){
		txtNotes.value='--';
	}
	var txtPayeeName = document.getElementById("txtPayeeName");
	if ((txtPayeeName.value =='')||(txtPayeeName.value.trim().length==0)){
		alert('Vaildation:\n\nPayee name is Required.');
		txtPayeeName.focus();
		txtPayeeName.select();
		return false;
	}
    var CASH_GLACCOUNT = document.getElementById('cbocashacc').options[document.getElementById('cbocashacc').selectedIndex].value;
	if (CASH_GLACCOUNT.trim().length < 2){
		alert('Vaildation:\n\nCash Account is Required.');
		document.getElementById('cbocashacc').focus();
		document.getElementById('cbocashacc').select();
		return false;
	}
	
	
	var txtDocDate = document.getElementById("txtDocDate");
    if (isNaN(txtDocDate.value) ||(txtDocDate.value.length < 8)||(txtDocDate.value.trim() == "")||(eval(txtDocDate.value.trim().substr(4, 2))>12)||(eval(txtDocDate.value.trim().substr(4, 2))<1)||(eval(txtDocDate.value.trim().substr(6, 2))>31)||(eval(txtDocDate.value.trim().substr(6, 2))<1) ) {
	  alert('Vaildation:\n\nTransaction Date is not valid..\n\nTransaction Date Format: [YYYYMMDD].');
	  txtDocDate.focus();
	  txtDocDate.select();	
	  return false;
    }  
	return true;
}
<!------------------------------------------------------------------------------------------------> 

function CompleteJournalEntry(callerid){
	if (eval(callerid)==0){
		if (!VaildateHeader_ManualJE()){
			return;
		}
	}
	if ((JE_ROW_IDX < 0 )&&(EditJE_TYPE=='AP_PAYMENT')){
		alert('Journal entry is freezed');
		return;
	}
	if (!confirm('Existed journal line(s) will be Modified. Are you sure?')){return;}
	var uid =  "<?php print $uid ; ?>";
	var ORG_ID =  "<?php print $ORG_ID; ?>";
	var COMPANY = "<?php print $COMPANY ; ?>";
	var MATCH_TYPE_ID = -1;
	var JE_LINE_NUM = -1;
	var PO_HEADER_ID = 0;
	var PO_NUM = '';
	var INVOICE_NUM = '';
	var RECEIPT_NUM = '';
	var SHIP = 0;
	var PAY_SCHEDULE_ID = 0;
	
	if (document.getElementById('cboCurr').selectedIndex < 1){
		alert('Entry Error: No Currency.');
		document.getElementById('cboCurr').focus();
		return;	
	}
	if (document.getElementById('cboJEPO').selectedIndex != -1){PO_NUM=document.getElementById('cboJEPO').options[document.getElementById('cboJEPO').selectedIndex].value;}
	if (document.getElementById('cboSHIP').selectedIndex != -1){SHIP=document.getElementById('cboSHIP').options[document.getElementById('cboSHIP').selectedIndex].value;}
	if (document.getElementById('cboSCHED').selectedIndex != -1){PAY_SCHEDULE_ID=document.getElementById('cboSCHED').options[document.getElementById('cboSCHED').selectedIndex].value;}
	var EXP =0;
	if (document.getElementById('cboEXP').selectedIndex != -1){EXP =document.getElementById('cboEXP').options[document.getElementById('cboEXP').selectedIndex].value;}
	var VENDOR_CODE = document.getElementById('vendorid').value;
	var mj = document.getElementById('cboMJ').options[document.getElementById('cboMJ').selectedIndex].value;
	var mi = 0;
	if (document.getElementById('cboMI').selectedIndex != -1){mi=document.getElementById('cboMI').options[document.getElementById('cboMI').selectedIndex].value;}
	var WAT_TAX_RATE = 0;
	if (document.getElementById('cboWAT').selectedIndex >0){WAT_TAX_RATE = document.getElementById('cboWAT').options[document.getElementById('cboWAT').selectedIndex].value;}
	var VALUE_ADD_TAX_RATE = 0;
	if (document.getElementById('cboVAT').selectedIndex >0){VALUE_ADD_TAX_RATE=document.getElementById('cboVAT').options[document.getElementById('cboVAT').selectedIndex].value;}
	var PENALTY_AMOUNT = 0;
	INVOICE_NUM = document.getElementById('invje').value;
	var SALES_R_FLAG = -1;
	if (document.getElementById('cboirrsal').selectedIndex >0){SALES_R_FLAG=document.getElementById('cboirrsal').options[document.getElementById('cboirrsal').selectedIndex].value;}
	var SALES_TAX_RATE = 0;
	if (document.getElementById('cboSAL').selectedIndex >0){SALES_TAX_RATE=document.getElementById('cboSAL').options[document.getElementById('cboSAL').selectedIndex].value;}
	var CURR = document.getElementById('cboCurr').options[document.getElementById('cboCurr').selectedIndex].value;
	var CURR_RATE = document.getElementById('txtCurrRate').value;
	var JE_TYPE = EditJE_TYPE;
	var CASH_AMOUNT = document.getElementById('txtAmount').value.replace(/,/gi, '');
	var GLACCOUNT = document.getElementById('accnumid').value;
	var COSTCENTER = document.getElementById('ccnumid').value;
	var CASH_GLACCOUNT = document.getElementById('cbocashacc').options[document.getElementById('cbocashacc').selectedIndex].value;
	var CR_AMT = document.getElementById('cr').value.replace(/,/gi, '');
	var DR_AMT = document.getElementById('dr').value.replace(/,/gi, '');
	
	
	if (isNaN(CASH_AMOUNT)){document.getElementById('txtAmount').value = 0;}
	if (document.getElementById('cboCurr').selectedIndex <=0){
		alert('Currency is Required');
		document.getElementById('cboCurr').focus();
		return;
	}
	if ((isNaN(CURR_RATE))||(eval(CURR_RATE)<=0)||(CURR_RATE=='')||(CURR_RATE.trim().length==0)){
		document.getElementById('txtCurrRate').value = 1;
		CURR_RATE = document.getElementById('txtCurrRate').value;
	}
	
	if (((isNaN(DR_AMT))||(eval(DR_AMT)<0)||(DR_AMT=='')||(DR_AMT.trim().length==0))&&((isNaN(CASH_AMOUNT))||(eval(CASH_AMOUNT)<0)||(CASH_AMOUNT=='')||(CASH_AMOUNT.trim().length==0))){
		alert('Paid amount is ZERO');
		document.getElementById('txtAmount').focus();
		document.getElementById('txtAmount').select();
		return;
	}
	if (CASH_GLACCOUNT.trim().length < 2){
		alert('Cash Account number is not valid');
		document.getElementById('cbocashacc').focus();
		document.getElementById('cbocashacc').select();
		return;
	}
	
	if ((isNaN(DR_AMT))||(eval(DR_AMT)<0)||(DR_AMT=='')||(DR_AMT.trim().length==0)){DR_AMT = 0;}
	if ((isNaN(PENALTY_AMOUNT))||(eval(PENALTY_AMOUNT)<0)||(PENALTY_AMOUNT=='')||(PENALTY_AMOUNT.trim().length==0)){PENALTY_AMOUNT = 0;}
	if ((isNaN(CASH_AMOUNT))||(eval(CASH_AMOUNT)<0)||(CASH_AMOUNT=='')||(CASH_AMOUNT.trim().length==0)){CASH_AMOUNT = 0;}
	if ((isNaN(CR_AMT))||(eval(CR_AMT)<0)||(CR_AMT=='')||(CR_AMT.trim().length==0)){CR_AMT = 0;}
	if ((isNaN(DR_AMT))||(eval(DR_AMT)<0)||(DR_AMT=='')||(DR_AMT.trim().length==0)){DR_AMT = 0;}
	if ((GLACCOUNT=='')||(GLACCOUNT.trim().length==0)){GLACCOUNT = '';}
	if ((COSTCENTER=='')||(COSTCENTER.trim().length==0)){COSTCENTER = '';}
	if ((VENDOR_CODE=='')||(VENDOR_CODE.trim().length==0)){VENDOR_CODE = '';}
	if ((eval(callerid)==0)&&(JE_TYPE!='AP_PAYMENT')){JE_TYPE='AP_MANUAL_ALL';}
	if ((eval(callerid)==1)&&(JE_TYPE!='AP_PAYMENT')){JE_TYPE='AP_MANUAL_LN';}
	if (eval(callerid)==1){
		if ((eval(DR_AMT)==0)&&(eval(CR_AMT)==0)){
			alert('Line Entry Error:\nNo JE line amount. Debit and/or Credit are ZERO');
			//return;
		}
	}
	
	var GET_JE_TABLE_PARAMS ='';
	GET_JE_TABLE_PARAMS = JE_HEADER_ID+','+ORG_ID+','+JE_LINE_NUM+','+MATCH_TYPE_ID+','+PAYMENT_ID+','+PO_HEADER_ID+',\''+PO_NUM+'\',\''+INVOICE_NUM+'\',\''+RECEIPT_NUM+'\',\''+SHIP+'\',\''+EXP+'\',\''+mj+'\',\''+mi+'\',\''+VENDOR_CODE+'\','+eval(WAT_TAX_RATE)+','+eval(VALUE_ADD_TAX_RATE)+','+eval(PENALTY_AMOUNT)+','+eval(SALES_R_FLAG)+','+eval(SALES_TAX_RATE)+',\''+JE_TYPE+'\',\''+GLACCOUNT+'\',\''+COSTCENTER+'\','+eval(CR_AMT)+','+eval(DR_AMT)+','+eval(CURR_RATE)+',\''+CASH_GLACCOUNT+'\','+eval(CASH_AMOUNT)+',\''+CURR+'\',\''+COMPANY+'\','+PAY_SCHEDULE_ID;
//------------------------------------------
	 // Create our XMLHttpRequest object
    var hr = new XMLHttpRequest();
    // Create some variables we need to send to our PHP file
    var url = "AP_APP_JE_DB.php";
	var vars = "GET_JE_TABLE_PARAMS="+GET_JE_TABLE_PARAMS+"&action=GET_JE_TABLE_PARAMS";
    hr.open("POST", url, true);
	
	hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded; charset=utf-8");
	hr.setRequestHeader("Content-length", vars.length);
	hr.setRequestHeader("Connection", "close");
    // Access the onreadystatechange event for the XMLHttpRequest object
    hr.onreadystatechange = function() {
	    if(hr.readyState == 4 && hr.status == 200) {
			document.getElementById("status").innerHTML ='';
		    var return_data = hr.responseText;			
			var datarows = return_data.split("</tr>");
			var datarows_count = datarows.length-1;
			
			if (eval(datarows_count) <=0){
			errors = eval(errors)+1;
			errors_msg =return_data;
			document.getElementById("status").innerHTML = '<div class="alert-box errormsg">'+errors_msg+'</div>';
			alert(return_data);
			return;	
			}
			
			var t = $('#recJEGrid').DataTable();
			if (eval(callerid)==0){
			if (MatchedJE_FLAG=='Y'){return;}
			t
			.clear()
			.draw();
			}
			if (eval(callerid)==1){
			if (JE_ROW_IDX >=1){t.row(eval(JE_ROW_IDX)-1).remove().draw();}
			JE_ROW_IDX = -1;	
			}
			var errors=0;
			var errors_msg ='';
			
			
			  for(var i = 0 ; i < datarows_count; i++) { 
    			var datacols =datarows[i].split("</td>");
				
				
				if (eval(datacols[9])>0){
					errors = eval(errors)+1;
					errors_msg =errors_msg+datacols[10]+'<br>';
					//continue;
				}
				
				
				
				
				
				t.row.add( [datacols[0],
							datacols[1],
							datacols[2],
							datacols[3],
							datacols[4],
							datacols[5],
							datacols[6],
							datacols[7],
							datacols[8]
							 ] ).draw( false );
							 
			  

   			  }
			  
			  
       //If table is in the tab, you need to adjust headers when tab becomes visible
			  $($.fn.dataTable.tables(true)).DataTable()
			  .columns.adjust();
			  //ClearJE('JE1');
			  //ClearJE('JE2');	
			  ClearJE('JE3');
			  
  			  UpdateJEntrySummaryGrid();
			  if (eval(errors)>0){
				  document.getElementById("status").innerHTML = '<div class="alert-box errormsg">'+errors_msg+'</div>';
			  }
			  
			  //-----------------------------			  
			  var CashNetAmount = 0;	
			  var table1 = document.getElementById("recJEGrid");
 			  var rowCount1 = table1.rows.length;
			  for(var i=1; i<rowCount1; i++) {
			   var row = table1.rows[i];
			   if (row.cells[0].childNodes[0].value==CASH_GLACCOUNT){
					CashNetAmount =eval(CashNetAmount)+eval(row.cells[5].childNodes[0].value.replace(/,/gi, ''))-eval(row.cells[4].childNodes[0].value.replace(/,/gi, ''));
			   }
			  }
   	          document.getElementById("txtAmount").value=eval(CashNetAmount).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
			  //-----------------------------
		}
    }
    // Send the data to PHP now... and wait for response
    hr.send(vars); // Actually execute the request	
	document.getElementById("status").innerHTML = '<div class="alert-box warning"><span>Processing: </span>please wait .....</div>';
	
}

<!------------------------------------------------------------------------------------------------> 
function GET_PO_JE_LIST(vendor_id) {
	
	var uid =  "<?php print $uid ; ?>";
	var ORG_ID =  "<?php print $ORG_ID; ?>";
	 // Create our XMLHttpRequest object
    var hr = new XMLHttpRequest();
    // Create some variables we need to send to our PHP file
    var url = "AP_APP_JE_DB.php";
	var vars = "ORG_ID="+ORG_ID+"&vendor_id="+vendor_id+"&uid="+uid+"&action=GET_PO_JE_LIST";
    hr.open("POST", url, true);
  
	hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded; charset=utf-8");
	hr.setRequestHeader("Content-length", vars.length);
	hr.setRequestHeader("Connection", "close");
    // Access the onreadystatechange event for the XMLHttpRequest object
    hr.onreadystatechange = function() {
	    if(hr.readyState == 4 && hr.status == 200) {
		    var return_data = hr.responseText;				
			document.getElementById("cboJEPO").innerHTML = return_data;
			document.getElementById("status").innerHTML = '';
	    }
    }
    // Send the data to PHP now... and wait for response
    hr.send(vars); // Actually execute the request
	document.getElementById("status").innerHTML = '<div class="alert-box warning"><span>Processing: </span>please wait .....</div>';					
}
<!--------------------------------------------------------------------------------------------->
<!------------------------------------------------------------------------------------------------> 
function GET_LOOKUP(mlookup,melm) {
	var COMPANY = "<?php print $COMPANY ; ?>";
	 // Create our XMLHttpRequest object
    var hr = new XMLHttpRequest();
    // Create some variables we need to send to our PHP file
    var url = "AP_APP_JE_DB.php";
	var vars = "COMPANY="+COMPANY+"&lookup=1&action="+mlookup;
    hr.open("POST", url, true);
  
	hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded; charset=utf-8");
	hr.setRequestHeader("Content-length", vars.length);
	hr.setRequestHeader("Connection", "close");
    // Access the onreadystatechange event for the XMLHttpRequest object
    hr.onreadystatechange = function() {
	    if(hr.readyState == 4 && hr.status == 200) {
		    var return_data = hr.responseText;
			document.getElementById(melm).innerHTML = return_data;
			document.getElementById("status").innerHTML ='';			
		}
    }
    // Send the data to PHP now... and wait for response
    hr.send(vars); // Actually execute the request
	document.getElementById("status").innerHTML = '<div class="alert-box warning"><span>Processing: </span>please wait .....</div>';
}
<!------------------------------------------------------------------------------------------------> 
function GET_MINOR(mjcode) {
	var uid =  "<?php print $uid ; ?>";
	var ORG_ID =  "<?php print $ORG_ID; ?>"
	
	 // Create our XMLHttpRequest object
    var hr = new XMLHttpRequest();
    // Create some variables we need to send to our PHP file
    var url = "AP_APP_JE_DB.php";
	var vars = "ORG_ID="+ORG_ID+"&uid="+uid+"&mjcode="+mjcode+"&action=GET_MINOR";
	
    hr.open("POST", url, true);
  
	hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded; charset=utf-8");
	hr.setRequestHeader("Content-length", vars.length);
	hr.setRequestHeader("Connection", "close");
    // Access the onreadystatechange event for the XMLHttpRequest object
    hr.onreadystatechange = function() {
	    if(hr.readyState == 4 && hr.status == 200) {
		    var return_data = hr.responseText;
			document.getElementById("cboMI").innerHTML = return_data;
			document.getElementById("status").innerHTML = '';
			
		}
    }
    // Send the data to PHP now... and wait for response
    hr.send(vars); // Actually execute the request
	document.getElementById("status").innerHTML = '<div class="alert-box warning"><span>Processing: </span>please wait .....</div>';

}
<!------------------------------------------------------------------------------------------------> 
function GET_MINOR_JE(mjcode,minor) {
	var uid =  "<?php print $uid ; ?>";
	var ORG_ID =  "<?php print $ORG_ID; ?>"
	
	 // Create our XMLHttpRequest object
    var hr = new XMLHttpRequest();
    // Create some variables we need to send to our PHP file
    var url = "AP_APP_JE_DB.php";
	var vars = "ORG_ID="+ORG_ID+"&uid="+uid+"&mjcode="+mjcode+"&action=GET_MINOR";
	
    hr.open("POST", url, true);
  
	hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded; charset=utf-8");
	hr.setRequestHeader("Content-length", vars.length);
	hr.setRequestHeader("Connection", "close");
    // Access the onreadystatechange event for the XMLHttpRequest object
    hr.onreadystatechange = function() {
	    if(hr.readyState == 4 && hr.status == 200) {
		    var return_data = hr.responseText;
			document.getElementById("cboMI").innerHTML = return_data;
			for (var j=0; j<document.getElementById("cboMI").length; j++){
				 if (document.getElementById("cboMI").options[j].value == minor){
					 document.getElementById("cboMI").options[j].selected = true;
				 }
			 }
			document.getElementById("status").innerHTML = '';
			
		}
    }
    // Send the data to PHP now... and wait for response
    hr.send(vars); // Actually execute the request
	document.getElementById("status").innerHTML = '<div class="alert-box warning"><span>Processing: </span>please wait .....</div>';

}
<!------------------------------------------------------------------------------------------------> 
function GET_AP_TRADE_PAY_SCHEDULE(SHIP_NUM,PS) {
	var uid =  "<?php print $uid ; ?>";
	var ORG_ID =  "<?php print $ORG_ID; ?>"
	var CURR = document.getElementById('cboCurr').options[document.getElementById('cboCurr').selectedIndex].value;
	
	 // Create our XMLHttpRequest object
    var hr = new XMLHttpRequest();
    // Create some variables we need to send to our PHP file
    var url = "AP_APP_JE_DB.php";
	var vars = "CURR="+CURR+"&ORG_ID="+ORG_ID+"&uid="+uid+"&SHIPMENT_NUM="+SHIP_NUM+"&action=GET_AP_TRADE_PAY_SCHEDULE";
    hr.open("POST", url, true);
  
	hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded; charset=utf-8");
	hr.setRequestHeader("Content-length", vars.length);
	hr.setRequestHeader("Connection", "close");
    // Access the onreadystatechange event for the XMLHttpRequest object
    hr.onreadystatechange = function() {
	    if(hr.readyState == 4 && hr.status == 200) {
		    var return_data = hr.responseText;
			document.getElementById("cboSCHED").innerHTML = return_data;
			document.getElementById("status").innerHTML = '';
			document.getElementById("cboSCHED").value=PS;
			
		}
    }
    // Send the data to PHP now... and wait for response
    hr.send(vars); // Actually execute the request
	document.getElementById("status").innerHTML = '<div class="alert-box warning"><span>Processing: </span>please wait .....</div>';

}
<!------------------------------------------------------------------------------------------------> 
function GET_SHIPMENTS_E(vendor_id,SHIP,PAY_SCH) {
	
	var uid =  "<?php print $uid ; ?>";
	var ORG_ID =  "<?php print $ORG_ID; ?>";
	 // Create our XMLHttpRequest object
    var hr = new XMLHttpRequest();
    // Create some variables we need to send to our PHP file
    var url = "AP_APP_MATCH_DB.php";
	var vars = "ORG_ID="+ORG_ID+"&vendor_id="+vendor_id+"&uid="+uid+"&action=GET_SHIPMENTS";
    hr.open("POST", url, true);
  
	hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded; charset=utf-8");
	hr.setRequestHeader("Content-length", vars.length);
	hr.setRequestHeader("Connection", "close");
    // Access the onreadystatechange event for the XMLHttpRequest object
    hr.onreadystatechange = function() {
	    if(hr.readyState == 4 && hr.status == 200) {
		    var return_data = hr.responseText;				
			document.getElementById("cboSHIP").innerHTML = return_data;
			document.getElementById("status").innerHTML = '';	
			document.getElementById("cboSHIP").value=SHIP;			
	 		GET_AP_TRADE_PAY_SCHEDULE(SHIP,PAY_SCH);	
	    }
    }
    // Send the data to PHP now... and wait for response
    hr.send(vars); // Actually execute the request
	document.getElementById("status").innerHTML = '<div class="alert-box warning"><span>Processing: </span>please wait .....</div>';					
}
<!--------------------------------------------------------------------------------------------->
function ValidateJELinesErrors(){
	var errCount = 0;
	var errMsg = '';
	var tJE = $('#recJEGrid').DataTable();
	if (tJE.data().count()){		 
		 var table = document.getElementById("recJEGrid");
		 var rowCount = table.rows.length;
		 for (var i=1; i<rowCount; i++) {
			 var row = table.rows[i]; 
			 var colHidden=row.cells[8];
			 for (var j=0; j<colHidden.childNodes.length; j++){		 
				if (colHidden.childNodes[j].id=='ERR_NUM'){
					if (eval(colHidden.childNodes[j].value.trim()) > 0){
						errCount=eval(errCount)+1;
					}
				}
				if (colHidden.childNodes[j].id=='ERR_MSG'){
					errMsg=errMsg+colHidden.childNodes[j].value+'\n';
				}		
			 }			 
		 }
	}else{
		alert('Entry Error: \n No Journal Entry.');
		return false;
	}
	
	if (eval(errCount) > 0){
		alert('Entry Error: \n'+errMsg);
		return false;
	}
	return true;
}
<!--------------------------------------------------------------------------------------------->

function BuildGLJELines(){
	BuilderGLJELines = '';
 	
	var DR_AMOUNT = '0';
	var CR_AMOUNT = '0';
	var PAYEE_ID = 0;
	var SALES_TAX_RATE = 0;
	var SALES_R_FLAG = 0;
	var VALUE_ADD_TAX_RATE = 0;
	var WAT_TAX_RATE = 0;
	var PENALTY_AMOUNT = 0;
	var EXPENSE_TYPE = 0;
	var IR_SAL_TAX_AMT = 0;
	var MATCH_TYPE_ID = 0;
	var LINE_TYPE_DFF = 'STANDARD';
	var ACCOUNT_NUM = '';
	var COST_CENTER = '';
	var VENDOR_CODE = '';
	var SHIPMENT_NUM = '';
	var PAY_SCHEDULE_ID = 0;
	var MAJOR_CODE = '';
	var MINOR_CODE = '';
	var PO_NUM = '';
	var INVOICE_NUM = '';
	var RECEIPT_NUM = '';		 
		
	var table = document.getElementById("recJEGrid");
	var rowCount = table.rows.length;
		
	for (var i=1; i<rowCount; i++) {
		x = i;
		var row = table.rows[i]; 
		var  GLACCOUNT_ID =row.cells[0];			
		for (var j=0; j<GLACCOUNT_ID.childNodes.length; j++){
			if (GLACCOUNT_ID.childNodes[j].id=='GLACCOUNT'){ACCOUNT_NUM=GLACCOUNT_ID.childNodes[j].value;}
		}
		
		
		COST_CENTER = row.cells[1].childNodes[0].value;
		DR_AMOUNT = row.cells[4].childNodes[0].value;
		DR_AMOUNT = DR_AMOUNT.replace(/,/gi, '') ;
		CR_AMOUNT = row.cells[5].childNodes[0].value;
		CR_AMOUNT = CR_AMOUNT.replace(/,/gi, '') ;
		var colHidden=row.cells[8];
		for (var j=0; j<colHidden.childNodes.length; j++){
			if (colHidden.childNodes[j].id=='WAT_TAX_RATE'){WAT_TAX_RATE = eval(colHidden.childNodes[j].value);}
			if (colHidden.childNodes[j].id=='VALUE_ADD_TAX_RATE'){VALUE_ADD_TAX_RATE= eval(colHidden.childNodes[j].value);}
			if (colHidden.childNodes[j].id=='SALES_TAX_RATE'){SALES_TAX_RATE = eval(colHidden.childNodes[j].value);}
			if (colHidden.childNodes[j].id=='SALES_R_FLAG'){SALES_R_FLAG= eval(colHidden.childNodes[j].value);}
			if (colHidden.childNodes[j].id=='PENALTY_AMOUNT'){PENALTY_AMOUNT= eval(colHidden.childNodes[j].value);}
			if (colHidden.childNodes[j].id=='VENDOR_CODE'){VENDOR_CODE = colHidden.childNodes[j].value;}
			if (colHidden.childNodes[j].id=='SHIPMENT_NUM'){SHIPMENT_NUM= colHidden.childNodes[j].value;}
			if (colHidden.childNodes[j].id=='EXPENSE_TYPE'){EXPENSE_TYPE= colHidden.childNodes[j].value;}
			if (colHidden.childNodes[j].id=='MAJOR_CODE'){MAJOR_CODE= colHidden.childNodes[j].value;}		
			if (colHidden.childNodes[j].id=='MINOR_CODE'){MINOR_CODE= colHidden.childNodes[j].value;}
			if (colHidden.childNodes[j].id=='LINE_TYPE_DFF'){LINE_TYPE_DFF= colHidden.childNodes[j].value;}
			if (colHidden.childNodes[j].id=='IR_SAL_TAX_AMT'){IR_SAL_TAX_AMT= colHidden.childNodes[j].value;}
			if (colHidden.childNodes[j].id=='PO_NUM'){PO_NUM= colHidden.childNodes[j].value;}
			if (colHidden.childNodes[j].id=='INVOICE_NUM'){INVOICE_NUM= colHidden.childNodes[j].value;}
			if (colHidden.childNodes[j].id=='MATCH_TYPE_ID'){MATCH_TYPE_ID= colHidden.childNodes[j].value;}
			if (colHidden.childNodes[j].id=='RECEIPT_NUM'){RECEIPT_NUM= colHidden.childNodes[j].value;}
			if (colHidden.childNodes[j].id=='PAY_SCHEDULE_ID'){PAY_SCHEDULE_ID= colHidden.childNodes[j].value;}
		}
		
		BuilderGLJELines = BuilderGLJELines + " SELECT ";
		BuilderGLJELines = BuilderGLJELines + PAYMENT_ID + ",";
		BuilderGLJELines = BuilderGLJELines + DR_AMOUNT+ ",";
		BuilderGLJELines = BuilderGLJELines + CR_AMOUNT+ ",";		
		BuilderGLJELines = BuilderGLJELines + PAYEE_ID+ ",";
		BuilderGLJELines = BuilderGLJELines + SALES_TAX_RATE+ ",";
		BuilderGLJELines = BuilderGLJELines + SALES_R_FLAG+ ",";
		BuilderGLJELines = BuilderGLJELines + VALUE_ADD_TAX_RATE+ ",";
		BuilderGLJELines = BuilderGLJELines + WAT_TAX_RATE+ ",";
		BuilderGLJELines = BuilderGLJELines + PENALTY_AMOUNT+ ",";
		BuilderGLJELines = BuilderGLJELines + EXPENSE_TYPE+ ",";
		BuilderGLJELines = BuilderGLJELines + IR_SAL_TAX_AMT+ ",";
		BuilderGLJELines = BuilderGLJELines + MATCH_TYPE_ID+ ",";
		BuilderGLJELines = BuilderGLJELines +"'"+LINE_TYPE_DFF+"',";
		BuilderGLJELines = BuilderGLJELines +"'"+ACCOUNT_NUM+"',";
		BuilderGLJELines = BuilderGLJELines +"'"+COST_CENTER+"',";
		BuilderGLJELines = BuilderGLJELines +"'"+VENDOR_CODE+"',";
		BuilderGLJELines = BuilderGLJELines +"'"+SHIPMENT_NUM+"',";
		BuilderGLJELines = BuilderGLJELines +"'"+MAJOR_CODE+"',";
		BuilderGLJELines = BuilderGLJELines +"'"+MINOR_CODE+"',";
		BuilderGLJELines = BuilderGLJELines +"'"+PO_NUM+"',";
		BuilderGLJELines = BuilderGLJELines +"'"+INVOICE_NUM+"',";
		BuilderGLJELines = BuilderGLJELines +"'"+RECEIPT_NUM+"',";
		BuilderGLJELines = BuilderGLJELines + PAY_SCHEDULE_ID;
		BuilderGLJELines = BuilderGLJELines + " FROM DUAL";
		
		x = " UNION ALL ";
		if ((i+1)==rowCount){x="";}
			
		if (i==1){
			BuilderGLJELines = " SELECT "; 
			BuilderGLJELines = BuilderGLJELines + PAYMENT_ID + ' AS PAYMENT_ID,';
			BuilderGLJELines = BuilderGLJELines + DR_AMOUNT + ' AS DR_AMOUNT,';
			BuilderGLJELines = BuilderGLJELines + CR_AMOUNT + ' AS CR_AMOUNT,'; 
			BuilderGLJELines = BuilderGLJELines + PAYEE_ID+ " AS PAYEE_ID,";
			BuilderGLJELines = BuilderGLJELines + SALES_TAX_RATE + ' AS SALES_TAX_RATE,';
			BuilderGLJELines = BuilderGLJELines + SALES_R_FLAG + ' AS SALES_R_FLAG,';
			BuilderGLJELines = BuilderGLJELines + VALUE_ADD_TAX_RATE + ' AS VALUE_ADD_TAX_RATE,';
			BuilderGLJELines = BuilderGLJELines + WAT_TAX_RATE + ' AS WAT_TAX_RATE,';
			BuilderGLJELines = BuilderGLJELines + PENALTY_AMOUNT + ' AS PENALTY_AMOUNT,';
			BuilderGLJELines = BuilderGLJELines + EXPENSE_TYPE + ' AS EXPENSE_TYPE,';
			BuilderGLJELines = BuilderGLJELines + IR_SAL_TAX_AMT + ' AS IR_SAL_TAX_AMT,';
			BuilderGLJELines = BuilderGLJELines + MATCH_TYPE_ID + ' AS MATCH_TYPE_ID,';
			BuilderGLJELines = BuilderGLJELines + "'"+LINE_TYPE_DFF+"'  AS LINE_TYPE_DFF,";
			BuilderGLJELines = BuilderGLJELines + "'"+ACCOUNT_NUM+"'  AS ACCOUNT_NUM,";
			BuilderGLJELines = BuilderGLJELines + "'"+COST_CENTER+"'  AS COST_CENTER,";
			BuilderGLJELines = BuilderGLJELines + "'"+VENDOR_CODE+"'  AS VENDOR_CODE,";
			BuilderGLJELines = BuilderGLJELines + "'"+SHIPMENT_NUM+"'  AS SHIPMENT_NUM,";
			BuilderGLJELines = BuilderGLJELines + "'"+MAJOR_CODE+"'  AS MAJOR_CODE,";
			BuilderGLJELines = BuilderGLJELines + "'"+MINOR_CODE+"'  AS MINOR_CODE,";
			BuilderGLJELines = BuilderGLJELines + "'"+PO_NUM+"'  AS PO_NUM,";
			BuilderGLJELines = BuilderGLJELines + "'"+INVOICE_NUM+"'  AS INVOICE_NUM,";
			BuilderGLJELines = BuilderGLJELines + "'"+RECEIPT_NUM+"'  AS RECEIPT_NUM,";
			BuilderGLJELines = BuilderGLJELines + PAY_SCHEDULE_ID+ " AS PAY_SCHEDULE_ID";
			BuilderGLJELines = BuilderGLJELines + " FROM DUAL";
		}
		BuilderGLJELines = BuilderGLJELines +x;
	}	
	return BuilderGLJELines;
}

<!--------------------------------------------------------------------------------------------->

function EDIT_JENTRY(rowidx){
	if (JE_HEADER_ID !=0){
		document.getElementById("status").innerHTML = '<div class="alert-box errormsg">You could not update records which have a posted journal entry.</div>';
		return false;	
	}
 if (rowidx > 0){
	 var table = document.getElementById("recJEGrid");
	 var rowCount = table.rows.length;
	 var row = table.rows[rowidx]; 
	 var mj_id ='';
	 var mi_id ='';
	 var SHIP_NUM_E ='';
	 var PAY_SCH=0;
     var GLACCOUNT_ID =row.cells[0];
	 var ACCOUNT_NUM ='';			
	 for (var j=0; j<GLACCOUNT_ID.childNodes.length; j++){
		if (GLACCOUNT_ID.childNodes[j].id=='GLACCOUNT'){ACCOUNT_NUM=GLACCOUNT_ID.childNodes[j].value;}
	 }
	 
	 document.getElementById("accnumid").value = ACCOUNT_NUM;
	 document.getElementById("accnumdesc").value = row.cells[2].childNodes[0].value;
	 document.getElementById("ccnumid").value = row.cells[1].childNodes[0].value;
	 document.getElementById("ccnumdesc").value = row.cells[3].childNodes[0].value;
	 document.getElementById("dr").value = row.cells[4].childNodes[0].value;
	 document.getElementById("cr").value = row.cells[5].childNodes[0].value;
	 
	 var colHidden=row.cells[8];
	 for (var i=0; i<colHidden.childNodes.length; i++){
		if (colHidden.childNodes[i].id=='WAT_TAX_RATE'){
			var cboWAT = document.getElementById("cboWAT");
			for (var j=0; j<cboWAT.length; j++){
				 if (eval(cboWAT.options[j].value) == eval(colHidden.childNodes[i].value)){
					 cboWAT.options[j].selected = true;
				 }
			 }
		}
		if (colHidden.childNodes[i].id=='VALUE_ADD_TAX_RATE'){
			var cboVAT = document.getElementById("cboVAT");
			for (var j=0; j<cboVAT.length; j++){
				 if (eval(cboVAT.options[j].value) == eval(colHidden.childNodes[i].value)){
					 cboVAT.options[j].selected = true;
				 }
			 }
		}
		if (colHidden.childNodes[i].id=='SALES_TAX_RATE'){
			var cboSAL = document.getElementById("cboSAL");
			for (var j=0; j<cboSAL.length; j++){
				 if (eval(cboSAL.options[j].value) == eval(colHidden.childNodes[i].value)){
					 cboSAL.options[j].selected = true;
				 }
			 }
		}
		if (colHidden.childNodes[i].id=='SALES_R_FLAG'){
			var cboirrsal = document.getElementById("cboirrsal");
			for (var j=0; j<cboirrsal.length; j++){
				 if (eval(cboirrsal.options[j].value) == eval(colHidden.childNodes[i].value)){
					 cboirrsal.options[j].selected = true;
				 }
			 }
		}
		if (colHidden.childNodes[i].id=='INVOICE_NUM'){
			var invje = document.getElementById("invje");
			invje.value = colHidden.childNodes[i].value;
		}
		if (colHidden.childNodes[i].id=='VENDOR_CODE'){
			var vendorid = document.getElementById("vendorid");
			vendorid.value = colHidden.childNodes[i].value;
		}
		if (colHidden.childNodes[i].id=='SHIPMENT_NUM'){
			SHIP_NUM_E = colHidden.childNodes[i].value;
		}
		if (colHidden.childNodes[i].id=='EXPENSE_TYPE'){
			var cboEXP = document.getElementById("cboEXP");
			for (var j=0; j<cboEXP.length; j++){
				 if (cboEXP.options[j].value == colHidden.childNodes[i].value){
					 cboEXP.options[j].selected = true;
				 }
			 }
		}
		if (colHidden.childNodes[i].id=='MAJOR_CODE'){
			var cboMJ = document.getElementById("cboMJ");
			for (var j=0; j<cboMJ.length; j++){
				 if (cboMJ.options[j].value == colHidden.childNodes[i].value){
					 cboMJ.options[j].selected = true;
					 mj_id = colHidden.childNodes[i].value;
				 }
			 }
		}
		if (colHidden.childNodes[i].id=='PAY_SCHEDULE_ID'){
			PAY_SCH = colHidden.childNodes[i].value;
		}
		if (colHidden.childNodes[i].id=='JE_TYPE'){
			if (colHidden.childNodes[i].value=='AP_PAYMENT'){
				document.getElementById("cboWAT").disabled=true;
				document.getElementById("vendorid").disabled=true;
				document.getElementById("cboirrsal").disabled=true;
				document.getElementById("cboVAT").disabled=true;
				document.getElementById("cboSAL").disabled=true;
				document.getElementById("search_vend").disabled=true;
				document.getElementById("invje").readOnly=true;
				document.getElementById("dr").readOnly=true;
				document.getElementById("cr").readOnly=true;
				document.getElementById("accnumid").readOnly=true;
				document.getElementById("search_acc").disabled=true;
				document.getElementById("ccnumid").readOnly=true;
				document.getElementById("search_cc").disabled=true;
				EditJE_TYPE='AP_PAYMENT';
			}else{
				document.getElementById("cboWAT").disabled=false;
				document.getElementById("vendorid").disabled=false;
				document.getElementById("cboirrsal").disabled=false;
				document.getElementById("cboVAT").disabled=false;
				document.getElementById("cboSAL").disabled=false;
				document.getElementById("search_vend").disabled=false;
				document.getElementById("invje").readOnly=false;
				document.getElementById("dr").readOnly=false;
				document.getElementById("cr").readOnly=false;
				document.getElementById("accnumid").readOnly=false;
				document.getElementById("search_acc").disabled=false;
				document.getElementById("ccnumid").readOnly=false;
				document.getElementById("search_cc").disabled=false;
				EditJE_TYPE='';
			}
		}
		if (colHidden.childNodes[i].id=='MINOR_CODE'){
			mi_id = colHidden.childNodes[i].value;				 
		}
		
	 }
	 
	 //SEARCH_VENDOR_NAME(vendorid.value,'vendorn');
	 GET_MINOR_JE(mj_id,mi_id);
	 GET_SHIPMENTS_E(vendorid.value,SHIP_NUM_E,PAY_SCH);
	 JE_ROW_IDX = rowidx;
 } 	

}
<!------------------------------------------------------------------------------------------------> 
function CreateMatchedAccounting(paymentid){
	EditJE_TYPE='';
	var uid =  "<?php print $uid ; ?>";
	var ORG_ID =  "<?php print $ORG_ID; ?>";
	var ORGANIZATION_ID =  "<?php print $ORGANIZATION_ID; ?>";
	// Create our XMLHttpRequest object
    var hr = new XMLHttpRequest();

	
    // Create some variables we need to send to our PHP file
    var url = "AP_APP_JE_DB.php";
	var vars = "ORGANIZATION_ID="+ORGANIZATION_ID+"&ORG_ID="+ORG_ID+"&paymentid="+paymentid+"&uid="+uid+"&action=CreateMatchedAccounting";
	
    hr.open("POST", url, true);
  
	hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded; charset=utf-8");
	hr.setRequestHeader("Content-length", vars.length);
	hr.setRequestHeader("Connection", "close");
    // Access the onreadystatechange event for the XMLHttpRequest object
    hr.onreadystatechange = function() {
	    if(hr.readyState == 4 && hr.status == 200) {
		    var return_data = hr.responseText;
			var datarows = return_data.split("</tr>");
			document.getElementById("status").innerHTML = '';
	
			 
			  var t = $('#recJEGrid').DataTable();
			  t
				.clear()
				.draw();
			  var datarows_count = datarows.length-1;
			  for(var i = 0 ; i < datarows_count; i++) {
    			var datacols =datarows[i].split("</td>");
				
				
				t.row.add( [datacols[0],
							datacols[1],
							datacols[2],
							datacols[3],
							datacols[4],
							datacols[5],
							datacols[6],
							datacols[7],
							datacols[8]
							 ] ).draw( false );

   			  }
			  
			  
       //If table is in the tab, you need to adjust headers when tab becomes visible
			  $($.fn.dataTable.tables(true)).DataTable()
			  .columns.adjust();
			  UpdateJEntrySummaryGrid();
			  alert('FYI:\n Payment Draft Journal Entry has been created. Please, review it.');
			  document.getElementById("JEOpen").click();
			  document.getElementById("create_m_je").disabled=true;	  			  
	    }
    }
    // Send the data to PHP now... and wait for response
    hr.send(vars); // Actually execute the request
	document.getElementById("status").innerHTML = '<div class="alert-box warning"><span>Create Draft Journal Entry: </span>please wait .....</div>';
}
<!------------------------------------------------------------------------------------------------> 
function UpdateJEntrySummaryGrid() {
 var TOT_E_D = 0;
 var TOT_E_C = 0;
 var TOT_A_D = 0;
 var TOT_A_C = 0;
 var TOT_JE_VAR = 0;
 var table = document.getElementById("recJEGrid");
 var rowCount = table.rows.length;
 
 for(var i=1; i<rowCount; i++) {
	var row = table.rows[i];
	TOT_E_D = eval(TOT_E_D)+eval(row.cells[4].childNodes[0].value.replace(/,/gi, ''));
	TOT_E_C = eval(TOT_E_C)+eval(row.cells[5].childNodes[0].value.replace(/,/gi, ''));
	TOT_A_D = eval(TOT_A_D)+eval(row.cells[6].childNodes[0].value.replace(/,/gi, ''));
	TOT_A_C = eval(TOT_A_C)+eval(row.cells[7].childNodes[0].value.replace(/,/gi, ''));
 }
 
 TOT_JE_VAR = eval(TOT_E_D)-eval(TOT_E_C); 
 document.getElementById("TotEnteredD").value=eval(TOT_E_D).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
 document.getElementById("TotEnteredC").value=eval(TOT_E_C).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
 document.getElementById("TotAcctD").value=eval(TOT_A_D).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
 document.getElementById("TotAcctC").value=eval(TOT_A_C).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
 document.getElementById("JEVariance").value=eval(TOT_JE_VAR).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}); 	
 AdjustCashAmount();
}
<!------------------------------------------------------------------------------------------------> 
function VALIDATE_CROSSV_RULE(X,Y) {
	
	if (((X.trim()=='0')&&(Y.trim()=='0'))||(X.trim()=='0')||(X.trim()=='')||(X.trim().length==0)||(Y.trim().length==0)||(Y.trim()=='')){
		alert('Cross Validation Rule Error..\nAccount code combination is not valid..\n\nPlease correct your accounts entry and try again.');
		return false;
	}
	
	if ((X.trim()!='')&&(Y.trim()!='')){
		var ACCOUNT_NUM = X.trim().substr(0, 1);
		var CCENTER_NUM = Y.trim();
		var ACCOUNT_TYPE_IS_NOT_EXP = ["1", "2", "4", "9"];
		var ACCOUNT_TYPE ='';
		
		if(ACCOUNT_TYPE_IS_NOT_EXP.indexOf(ACCOUNT_NUM) != -1){
			ACCOUNT_TYPE = 'A';
		} else{
			ACCOUNT_TYPE = 'E';
		}
		
		if ((CCENTER_NUM=='0')&&(ACCOUNT_TYPE == 'E')){
			alert('Cross Validation Rule Error..\nAccount code combination is not valid..\n\nPlease correct your accounts entry and try again.');
			return false;
		}
		
		if ((CCENTER_NUM!='0')&&(ACCOUNT_TYPE == 'A')){
			alert('Cross Validation Rule Error..\nAccount code combination is not valid..\n\nPlease correct your accounts entry and try again.');
			return false;
		}
	}
	
	
	return true;
}
<!---------------------------------------------------------------------------------------------> 	
function AdjustCashAmount(){
var CASH_GLACCOUNT = document.getElementById('cbocashacc').value;
var SUM_AMOUNT = '0';
var M_RATE = document.getElementById('txtCurrRate').value;
var TOT_DR_AMOUNT = '0';
var TOT_CR_AMOUNT = '0';
var TOT_JE_VAR = 0;
var table = document.getElementById("recJEGrid");
var rowCount = table.rows.length;
for (var i=1; i<rowCount; i++) {
	var row = table.rows[i];
	if (row.cells[0].childNodes[0].value!=CASH_GLACCOUNT){
	TOT_DR_AMOUNT = eval(TOT_DR_AMOUNT)+eval(row.cells[4].childNodes[0].value.replace(/,/gi, '')) ;
	TOT_CR_AMOUNT = eval(TOT_CR_AMOUNT)+eval(row.cells[5].childNodes[0].value.replace(/,/gi, '')) ; 
	}	
}

SUM_AMOUNT = eval(TOT_DR_AMOUNT)-eval(TOT_CR_AMOUNT);
document.getElementById("txtAmount").value=eval(eval(SUM_AMOUNT)*eval(M_RATE)).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});

for (var i=1; i<rowCount; i++) {
	var row = table.rows[i];
	if (row.cells[0].childNodes[0].value==CASH_GLACCOUNT){
		if (eval(SUM_AMOUNT) > 0 ){
		row.cells[5].childNodes[0].value = SUM_AMOUNT ;
		row.cells[4].childNodes[0].value = 0 ;
		row.cells[7].childNodes[0].value = eval(SUM_AMOUNT)*eval(M_RATE) ;
		row.cells[6].childNodes[0].value = 0;
		}
		if (eval(SUM_AMOUNT) <= 0 ){
		row.cells[5].childNodes[0].value = 0;
		row.cells[4].childNodes[0].value = SUM_AMOUNT ;
		row.cells[7].childNodes[0].value = 0;
		row.cells[6].childNodes[0].value = eval(SUM_AMOUNT)*eval(M_RATE) ;
		}		
		break;
	}
}

TOT_DR_AMOUNT = 0 ;
TOT_CR_AMOUNT = 0 ; 
	
for (var i=1; i<rowCount; i++) {
	var row = table.rows[i];
	TOT_DR_AMOUNT = eval(TOT_DR_AMOUNT)+eval(row.cells[4].childNodes[0].value.replace(/,/gi, '')) ;
	TOT_CR_AMOUNT = eval(TOT_CR_AMOUNT)+eval(row.cells[5].childNodes[0].value.replace(/,/gi, '')) ; 
}

document.getElementById("TotEnteredD").value=eval(TOT_DR_AMOUNT).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
document.getElementById("TotEnteredC").value=eval(TOT_CR_AMOUNT).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
document.getElementById("TotAcctD").value=eval(eval(TOT_DR_AMOUNT)*eval(M_RATE)).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
document.getElementById("TotAcctC").value=eval(eval(TOT_CR_AMOUNT)*eval(M_RATE)).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});


TOT_JE_VAR = eval(TOT_DR_AMOUNT)-eval(TOT_CR_AMOUNT);

document.getElementById("JEVariance").value=eval(TOT_JE_VAR).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});

}

<!---------------------------------------------------------------------------------------------> 	
function AddJELine(){
		
			var X =document.getElementById("accnumid").value;
			var Y =document.getElementById("ccnumid").value;
			if(VALIDATE_CROSSV_RULE(X,Y)){
			CompleteJournalEntry(1);
			document.getElementById("cboWAT").disabled=false;
			document.getElementById("vendorid").disabled=false;
			document.getElementById("cboirrsal").disabled=false;
			document.getElementById("cboVAT").disabled=false;
			document.getElementById("cboSAL").disabled=false;
			document.getElementById("search_vend").disabled=false;
			document.getElementById("invje").readOnly=false;
			document.getElementById("dr").readOnly=false;
			document.getElementById("cr").readOnly=false;
			document.getElementById("accnumid").readOnly=false;
			document.getElementById("search_acc").disabled=false;
			document.getElementById("ccnumid").readOnly=false;
			document.getElementById("search_cc").disabled=false;
			document.getElementById("cboMJ").selectedIndex =0;
			GET_MINOR(0);
			}
			document.getElementById("accnumid").focus();
			document.getElementById("accnumid").select();
			
			EditJE_TYPE='';
			
}

<!--------------------------------------------------------------------------------------------->
function HandleGridJEKeyEvent(event,Z,JE_TYPE){
var x = event.which || event.keyCode;
if ((x==46)&&(JE_TYPE!='AP_PAYMENT')){
if (confirm('Are you sure, you want to delete the current record?')){	
  var oTable = $('#recJEGrid').dataTable();
  // Immediately remove the first row
  oTable.fnDeleteRow(Z-1);
  UpdateJEntrySummaryGrid();
   //-----------------------------			  
	var CashNetAmount = 0;	
	var table1 = document.getElementById("recJEGrid");
 	var rowCount1 = table1.rows.length;
	for(var i=1; i<rowCount1; i++) {
	  var row = table1.rows[i];
	  if (row.cells[0].childNodes[0].value==CASH_GLACCOUNT){
		CashNetAmount =eval(CashNetAmount)+eval(row.cells[5].childNodes[0].value.replace(/,/gi, ''))-eval(row.cells[4].childNodes[0].value.replace(/,/gi, ''));
	  }
	}
   	document.getElementById("txtAmount").value=eval(CashNetAmount).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
	//-----------------------------
}
}
}
<!---------------------------------------------------------------------------------------------> 	
function HandleJEAmounts(event,elmid){
	var x = event.which || event.keyCode;
	if (x == 13){
		event.preventDefault();
		
		if (elmid=='accnumid'){
			document.getElementById("ccnumid").focus();
			document.getElementById("ccnumid").select();
		}
		if (elmid=='ccnumid'){
			document.getElementById("dr").focus();
			document.getElementById("dr").select();
		}
		if (elmid=='dr'){
			document.getElementById("cr").focus();
			document.getElementById("cr").select();
		}
		if (elmid=='cr'){
			var X =document.getElementById("accnumid").value;
			var Y =document.getElementById("ccnumid").value;
			if(VALIDATE_CROSSV_RULE(X,Y)){
			CompleteJournalEntry(1);
			document.getElementById("cboWAT").disabled=false;
			document.getElementById("vendorid").disabled=false;
			document.getElementById("cboirrsal").disabled=false;
			document.getElementById("cboVAT").disabled=false;
			document.getElementById("cboSAL").disabled=false;
			document.getElementById("search_vend").disabled=false;
			document.getElementById("invje").readOnly=false;
			document.getElementById("dr").readOnly=false;
			document.getElementById("cr").readOnly=false;
			document.getElementById("accnumid").readOnly=false;
			document.getElementById("search_acc").disabled=false;
			document.getElementById("ccnumid").readOnly=false;
			document.getElementById("search_cc").disabled=false;
			document.getElementById("cboMJ").selectedIndex =0;
			GET_MINOR(0);
			}
			document.getElementById("accnumid").focus();
			document.getElementById("accnumid").select();
		}
	}
}
<!---------------------------------------------------------------------------------------------> 	
function HandleKeyEvent6(event){
	var x = event.which || event.keyCode;
	if (x == 13){
		event.preventDefault();
		SEARCH_CCENTERS_GET_RESULT();
	}
}
<!--------------------------------------------------------------------------------------------->
function SEARCH_CCENTERS_GET_RESULT() {
	var uid =  "<?php print $uid ; ?>";
	var ORG_ID =  "<?php print $ORG_ID; ?>";
	var COMPANY = "<?php print $COMPANY ; ?>";
	var search_item = document.getElementById("search_CCENTERS").value;
	search_item  = search_item.replace("'", "");
	search_item  = search_item.replace('"', '');
	search_item  = search_item.replace('&', ',');
	search_item  = search_item.replace(':', '-');
	search_item  = search_item.replace('#', '');
	search_item  = search_item.replace('$', 'USD');
	search_item  = search_item.replace('@', '-at-');
	search_item  = search_item.replace('^', '');
	search_item  = search_item.replace('/', '-');
	search_item  = search_item.replace('\\', '-');
	search_item  = search_item.replace('?', '');
	search_item  = search_item.replace('!', '');
	search_item  = search_item.trim();
	search_item  = search_item.replace(/^\s+|\s+$/gm,'');
	search_item  = search_item.toUpperCase();
	search_item  = search_item.replace("CREATE", "");
	search_item  = search_item.replace("DROP", "");
	search_item  = search_item.replace("UPDATE", "");
	search_item  = search_item.replace("INSERT", "");
	search_item  = search_item.replace("SELECT", "");
	search_item  = search_item.replace("DELETE", "");
	search_item  = search_item.replace("TRUNCATE", "");
	search_item  = search_item.replace("GRANT", "");
	search_item  = search_item.replace("REVOKE", "");
	search_item = encodeURI(search_item);
	 // Create our XMLHttpRequest object
    var hr = new XMLHttpRequest();
    // Create some variables we need to send to our PHP file
    var url = "AP_APP_JE_DB.php";
	var vars ="COMPANY="+COMPANY+"&ORG_ID="+ORG_ID+"&uid="+uid+"&search_item="+search_item+"&action=SEARCH_CCENTERS_GET_RESULT";
    hr.open("POST", url, true);
    // Set content type header information for sending url encoded variables in the request
    //hr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=utf-8');
	hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded; charset=utf-8");
	hr.setRequestHeader("Content-length", vars.length);
	hr.setRequestHeader("Connection", "close");
    // Access the onreadystatechange event for the XMLHttpRequest object
    hr.onreadystatechange = function() {
	    if(hr.readyState == 4 && hr.status == 200) {
		    var return_data = hr.responseText;
			document.getElementById("search_CCENTERS_results").innerHTML = return_data;
			document.getElementById("status").innerHTML = '';

	    }
    }
    // Send the data to PHP now... and wait for response to update the status div
    hr.send(vars); // Actually execute the request
	document.getElementById("status").innerHTML = '<div class="alert-box warning"><span>Processing: </span>please wait .....</div>';
			
}
<!---------------------------------------------------------------------------------------------> 
function SEARCH_CCENTERS_OVERLAY() {
	var el = document.getElementById("SEARCH_CCENTERS_OVERLAY");
	el.style.visibility = (el.style.visibility == "visible") ? "hidden" : "visible";
	if (el.style.visibility == "visible"){
	document.getElementById("search_CCENTERS").focus();
	document.getElementById("search_CCENTERS").select();
	}
}
<!---------------------------------------------------------------------------------------------> 		


function SELECT_CCENTER(str_id,str_n) {
	
	document.getElementById("ccnumid").value = str_id;
	document.getElementById("ccnumdesc").value = str_n;
	
	el = document.getElementById("SEARCH_CCENTERS_OVERLAY");
	el.style.visibility = (el.style.visibility == "visible") ? "hidden" : "visible";
	   
				
}
<!---------------------------------------------------------------------------------------------> 
<!--------------------------------------------------------------------------------------------->
function SEARCH_ACC_NAME(search_item,search_id,type_id,mType) {
	var uid =  "<?php print $uid ; ?>";
	var ORG_ID =  "<?php print $ORG_ID; ?>";
	search_item  = search_item.replace("'", "");
	search_item  = search_item.replace('"', '');
	search_item  = search_item.replace('&', ',');
	search_item  = search_item.replace(':', '-');
	search_item  = search_item.replace('#', '');
	search_item  = search_item.replace('$', 'USD');
	search_item  = search_item.replace('@', '-at-');
	search_item  = search_item.replace('^', '');
	search_item  = search_item.replace('/', '-');
	search_item  = search_item.replace('\\', '-');
	search_item  = search_item.replace('?', '');
	search_item  = search_item.replace('!', '');
	search_item  = search_item.trim();
	search_item  = search_item.replace(/^\s+|\s+$/gm,'');
	search_item  = search_item.toUpperCase();
	search_item  = search_item.replace("CREATE", "");
	search_item  = search_item.replace("DROP", "");
	search_item  = search_item.replace("UPDATE", "");
	search_item  = search_item.replace("INSERT", "");
	search_item  = search_item.replace("SELECT", "");
	search_item  = search_item.replace("DELETE", "");
	search_item  = search_item.replace("TRUNCATE", "");
	search_item  = search_item.replace("GRANT", "");
	search_item  = search_item.replace("REVOKE", "");
	search_item = encodeURI(search_item);
	 // Create our XMLHttpRequest object
    var hr = new XMLHttpRequest();
    // Create some variables we need to send to our PHP file
    var url = "AP_APP_JE_DB.php";
	var vars =  "EditJE_TYPE="+EditJE_TYPE+"&mType="+mType+"&ORG_ID="+ORG_ID+"&uid="+uid+"&search_item="+search_item+"&action=SEARCH_ACC_NAME";
    hr.open("POST", url, true);
    // Set content type header information for sending url encoded variables in the request
    //hr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=utf-8');
	hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded; charset=utf-8");
	hr.setRequestHeader("Content-length", vars.length);
	hr.setRequestHeader("Connection", "close");
    // Access the onreadystatechange event for the XMLHttpRequest object
    hr.onreadystatechange = function() {
	    if(hr.readyState == 4 && hr.status == 200) {
		    var return_data = hr.responseText;
			var datacols =return_data.split("</td>");
			document.getElementById(search_id).value = datacols[1];
			document.getElementById("status").innerHTML = '';
			if (eval(datacols[0])==0){
				document.getElementById(type_id).value = '0';
			}
	    }
    }
    // Send the data to PHP now... and wait for response to update the status div
    hr.send(vars); // Actually execute the request
	document.getElementById("status").innerHTML = '<div class="alert-box warning"><span>Processing: </span>please wait .....</div>';			
}
<!---------------------------------------------------------------------------------------------> 	
function HandleKeyEvent5(event){
	if (eval(JE_HEADER_ID) !=0){
	event.preventDefault();
	event.stopPropagation();
	document.getElementById("status").innerHTML = '<div class="alert-box errormsg">You could not update records which have a posted journal entry.</div>';
	return false;	
	}
	var x = event.which || event.keyCode;
	if (x == 13){
		event.preventDefault();
		SEARCH_ACCOUNTS_GET_RESULT();
	}
}
<!--------------------------------------------------------------------------------------------->
function SEARCH_ACCOUNTS_GET_RESULT() {
	var uid =  "<?php print $uid ; ?>";
	var ORG_ID =  "<?php print $ORG_ID; ?>";
	var COMPANY = "<?php print $COMPANY ; ?>";
	var search_item = document.getElementById("search_ACCOUNTS").value;
	search_item  = search_item.replace("'", "");
	search_item  = search_item.replace('"', '');
	search_item  = search_item.replace('&', ',');
	search_item  = search_item.replace(':', '-');
	search_item  = search_item.replace('#', '');
	search_item  = search_item.replace('$', 'USD');
	search_item  = search_item.replace('@', '-at-');
	search_item  = search_item.replace('^', '');
	search_item  = search_item.replace('/', '-');
	search_item  = search_item.replace('\\', '-');
	search_item  = search_item.replace('?', '');
	search_item  = search_item.replace('!', '');
	search_item  = search_item.trim();
	search_item  = search_item.replace(/^\s+|\s+$/gm,'');
	search_item  = search_item.toUpperCase();
	search_item  = search_item.replace("CREATE", "");
	search_item  = search_item.replace("DROP", "");
	search_item  = search_item.replace("UPDATE", "");
	search_item  = search_item.replace("INSERT", "");
	search_item  = search_item.replace("SELECT", "");
	search_item  = search_item.replace("DELETE", "");
	search_item  = search_item.replace("TRUNCATE", "");
	search_item  = search_item.replace("GRANT", "");
	search_item  = search_item.replace("REVOKE", "");
	search_item = encodeURI(search_item);
	 // Create our XMLHttpRequest object
    var hr = new XMLHttpRequest();
    // Create some variables we need to send to our PHP file
    var url = "AP_APP_JE_DB.php";
	var vars = "COMPANY="+COMPANY+"&EditJE_TYPE="+EditJE_TYPE+"&ORG_ID="+ORG_ID+"&uid="+uid+"&search_item="+search_item+"&action=SEARCH_ACCOUNTS_GET_RESULT";
    hr.open("POST", url, true);
    // Set content type header information for sending url encoded variables in the request
    //hr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=utf-8');
	hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded; charset=utf-8");
	hr.setRequestHeader("Content-length", vars.length);
	hr.setRequestHeader("Connection", "close");
    // Access the onreadystatechange event for the XMLHttpRequest object
    hr.onreadystatechange = function() {
	    if(hr.readyState == 4 && hr.status == 200) {
		    var return_data = hr.responseText;
			document.getElementById("search_ACCOUNTS_results").innerHTML = return_data;
			document.getElementById("status").innerHTML = '';
	    }
    }
    // Send the data to PHP now... and wait for response to update the status div
    hr.send(vars); // Actually execute the request
	document.getElementById("status").innerHTML = '<div class="alert-box warning"><span>Processing: </span>please wait .....</div>';		
}
<!---------------------------------------------------------------------------------------------> 
function SEARCH_ACCOUNTS_OVERLAY() {
	var el = document.getElementById("SEARCH_ACCOUNTS_OVERLAY");
	el.style.visibility = (el.style.visibility == "visible") ? "hidden" : "visible";
	if (el.style.visibility == "visible"){
	document.getElementById("search_ACCOUNTS").focus();
	document.getElementById("search_ACCOUNTS").select();
	}
}
<!---------------------------------------------------------------------------------------------> 		


function SELECT_ACCOUNT(str_id,str_n) {
	
	document.getElementById("accnumid").value = str_id;
	document.getElementById("accnumdesc").value = str_n;
	
	el = document.getElementById("SEARCH_ACCOUNTS_OVERLAY");
	el.style.visibility = (el.style.visibility == "visible") ? "hidden" : "visible";
	   
				
}
<!---------------------------------------------------------------------------------------------> 
		
</script>