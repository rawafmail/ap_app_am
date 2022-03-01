<script>

<!---------------------------------------------------------------------------------------------> 

function SELECT_PAYEE(str_id,str_n) {
	document.getElementById("txtPayeeNum").value = str_id;
	document.getElementById("txtPayeeName").value = str_n;
	el = document.getElementById("SEARCH_PAYEE_OVERLAY");
	el.style.visibility = (el.style.visibility == "visible") ? "hidden" : "visible";
}
<!---------------------------------------------------------------------------------------------> 
function SEARCH_PAYEE_OVERLAY() {
	var el = document.getElementById("SEARCH_PAYEE_OVERLAY");
	el.style.visibility = (el.style.visibility == "visible") ? "hidden" : "visible";
	if (el.style.visibility == "visible"){
		document.getElementById("search_PAYEE_N").value="%";
		SEARCH_PAYEE_GET_RESULT();
	document.getElementById("search_PAYEE_N").focus();
	document.getElementById("search_PAYEE_N").select();
	}
}
<!---------------------------------------------------------------------------------------------> 
function SEARCH_PAYEE_GET_RESULT(){
	var uid =  "<?php print $uid ; ?>";
	var ORG_ID =  "<?php print $ORG_ID; ?>";
	var search_item = search_txt(document.getElementById("search_PAYEE_N").value);
	var ven_emp_text = document.getElementById("ven_emp_text").value;
	
	 // Create our XMLHttpRequest object
    var hr = new XMLHttpRequest();
    // Create some variables we need to send to our PHP file
    var url = "AP_APP_MAIN_DB.php";
	var vars = "ORG_ID="+ORG_ID+"&uid="+uid+"&search_item="+search_item+"&ven_emp_text="+ven_emp_text+"&action=SEARCH_PAYEE_GET_RESULT";
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
			document.getElementById("search_PAYEE_results").innerHTML = return_data;
	    }
    }
    // Send the data to PHP now... and wait for response to update the status div
    hr.send(vars); // Actually execute the request
	document.getElementById("search_PAYEE_results").innerHTML = '<div class="alert-box warning"><span>Processing: </span>please wait .....</div>';					
}
<!---------------------------------------------------------------------------------------------> 
function SEARCH_PAYMENT_CLEAR(){
	document.getElementById("search_PAYMENT_results").innerHTML ='';
	Clear("SEARCH_PAY_FIELDS");	
}
<!--------------------------------------------------------------------------------------------->
function search_txt(txt){
	var search_item= txt;
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
	return search_item;
}
<!--------------------------------------------------------------------------------------------->
function SEARCH_PAYMENT_GET_RESULT() {
	var el = document.getElementById("SEARCH_PAYMENT_OVERLAY");
	if (el.style.visibility == "visible"){
		
	var uid =  "<?php print $uid ; ?>";
	var ORG_ID =  "<?php print $ORG_ID; ?>";
	var search_PAYMETHOD='';
	var search_PAYACCOUNT='';
	var search_FDATE=document.getElementById('search_FDATE').value.trim();
	var search_TDATE=document.getElementById('search_TDATE').value.trim();
	var search_JENUM=document.getElementById('search_JENUM').value.trim();
	var search_PAYMENT=document.getElementById('search_PAYMENT').value.trim();
	var search_AMOUNT =0;
	var search_STATUS ='';
	
	if (!isNaN(document.getElementById('search_AMOUNT').value) && (eval(document.getElementById('search_AMOUNT').value)!=0)){search_AMOUNT = document.getElementById('search_AMOUNT').value;}
	var search_PAYEE = search_txt(document.getElementById("search_PAYEE").value);
	var search_NOTE = search_txt(document.getElementById("search_NOTE").value);
	if (document.getElementById('search_STATUS').selectedIndex != -1){search_STATUS=document.getElementById('search_STATUS').options[document.getElementById('search_STATUS').selectedIndex].value;}
	if (document.getElementById('search_PAYMETHOD').selectedIndex != -1){search_PAYMETHOD=document.getElementById('search_PAYMETHOD').options[document.getElementById('search_PAYMETHOD').selectedIndex].value;}
	if (document.getElementById('search_PAYACCOUNT').selectedIndex != -1){search_PAYACCOUNT=document.getElementById('search_PAYACCOUNT').options[document.getElementById('search_PAYACCOUNT').selectedIndex].value;}
	
	 // Create our XMLHttpRequest object
    var hr = new XMLHttpRequest();
    // Create some variables we need to send to our PHP file
    var url = "AP_APP_MAIN_DB.php";
	var vars ="search_JENUM="+search_JENUM+"&search_STATUS="+search_STATUS+"&search_AMOUNT="+search_AMOUNT+"&search_PAYMETHOD="+search_PAYMETHOD+"&search_PAYACCOUNT="+search_PAYACCOUNT+"&search_FDATE="+search_FDATE+"&search_TDATE="+search_TDATE+"&search_PAYMENT="+search_PAYMENT+"&search_PAYEE="+search_PAYEE+"&search_NOTE="+search_NOTE+"&ORG_ID="+ORG_ID+"&uid="+uid+"&action=SEARCH_PAYMENT_GET_RESULT";
	
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
			document.getElementById("search_PAYMENT_results").innerHTML = return_data;
	    }
    }
    // Send the data to PHP now... and wait for response to update the status div
    hr.send(vars); // Actually execute the request
	document.getElementById("search_PAYMENT_results").innerHTML = '<div class="alert-box warning"><span>Processing: </span>please wait .....</div>';
	}
}
<!---------------------------------------------------------------------------------------------> 
function SEARCH_PAYMENT_OVERLAY() {
	var el = document.getElementById("SEARCH_PAYMENT_OVERLAY");
	el.style.visibility = (el.style.visibility == "visible") ? "hidden" : "visible";
	if (el.style.visibility == "visible"){
	document.getElementById("search_PAYMENT").focus();
	document.getElementById("search_PAYMENT").select();
	}
}
<!------------------------------------------------------------------------------------------------> 
function APPROVE_PAY() {
	if (!confirm('Are you sure?')){return;}
	var uid =  "<?php print $_REQUEST["uid"]; ?>";
	var ORG_ID = "<?php print $ORG_ID; ?>";
	var LEDGER_ID = "<?php print $LEDGER_ID; ?>";
	var JEAdjustFlag =document.getElementById('cbopay_mthd').options[document.getElementById('cbopay_mthd').selectedIndex].value;
	var JENum = '0';
	
	if (eval(JEAdjustFlag)==3){
		JENum = prompt("Please enter your Journal Number:");
		if (JENum == null || JENum.trim() == '' || JENum.trim() == '0') {
			alert("The APPROVE action cancelled.");
			return;
		}
	}
	
	 // Create our XMLHttpRequest object
    var hr = new XMLHttpRequest();
    // Create some variables we need to send to our PHP file
    var url = "AP_APP_MAIN_DB.php";
	var vars = "JENum="+JENum+"&LEDGER_ID="+LEDGER_ID+"&PAYMENT_ID="+PAYMENT_ID+"&ORG_ID="+ORG_ID+"&uid="+uid+"&action=APPROVE";
    hr.open("POST", url, true);
  
	hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded; charset=utf-8");
	hr.setRequestHeader("Content-length", vars.length);
	hr.setRequestHeader("Connection", "close");
    // Access the onreadystatechange event for the XMLHttpRequest object
    hr.onreadystatechange = function() {
	    if(hr.readyState == 4 && hr.status == 200) {
			document.getElementById("status").innerHTML ='';
			var return_data = hr.responseText;
			alert(return_data);
			document.getElementById("btnPost").disabled = true;
			document.getElementById("btnAPRV").disabled = true;
			document.getElementById("btnPAY").disabled = false;
        	document.getElementById("btnSave").disabled = true;
	   
	    }
    }
    // Send the data to PHP now... and wait for response
    hr.send(vars); // Actually execute the request
	document.getElementById("status").innerHTML = '<div class="alert-box warning">please wait .....</div>';
			
}
<!------------------------------------------------------------------------------------------------> 
function PAY_PAY() {
	
	if (!confirm('Are you sure?')){return;}
	
	var uid =  "<?php print $_REQUEST["uid"]; ?>";
	var ORG_ID = "<?php print $ORG_ID; ?>";
	var LEDGER_ID = "<?php print $LEDGER_ID; ?>";
	var JEAdjustFlag =document.getElementById('cbopay_mthd').options[document.getElementById('cbopay_mthd').selectedIndex].value;
	var JENum = '0';
	
	if (eval(JEAdjustFlag)==3){
		JENum = prompt("Please enter your Journal Number:");
		if (JENum == null || JENum.trim() == '' || JENum.trim() == '0') {
			alert("The APPROVE action cancelled.");
			return;
		}
	}
	
	 // Create our XMLHttpRequest object
    var hr = new XMLHttpRequest();
    // Create some variables we need to send to our PHP file
    var url = "AP_APP_MAIN_DB.php";
	var vars = "JENum="+JENum+"&LEDGER_ID="+LEDGER_ID+"&PAYMENT_ID="+PAYMENT_ID+"&ORG_ID="+ORG_ID+"&uid="+uid+"&action=PAY";
    hr.open("POST", url, true);
  
	hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded; charset=utf-8");
	hr.setRequestHeader("Content-length", vars.length);
	hr.setRequestHeader("Connection", "close");
    // Access the onreadystatechange event for the XMLHttpRequest object
    hr.onreadystatechange = function() {
	    if(hr.readyState == 4 && hr.status == 200) {
			document.getElementById("status").innerHTML ='';
			var return_data = hr.responseText;
			alert(return_data);
			document.getElementById("btnPost").disabled = false;
			document.getElementById("btnAPRV").disabled = true;
			document.getElementById("btnPAY").disabled = true;
        	document.getElementById("btnSave").disabled = true;	
	    }
    }
    // Send the data to PHP now... and wait for response
    hr.send(vars); // Actually execute the request
	document.getElementById("status").innerHTML = '<div class="alert-box warning">please wait .....</div>';
			
}

<!------------------------------------------------------------------------------------------------> 
function POST_JE() {
	
	var uid =  "<?php print $_REQUEST["uid"]; ?>";
	var ORG_ID = "<?php print $ORG_ID; ?>";
	var LEDGER_ID = "<?php print $LEDGER_ID; ?>";
	var JEAdjustFlag =document.getElementById('cbopay_mthd').options[document.getElementById('cbopay_mthd').selectedIndex].value;
	var JENum = '0';
	
	if (eval(JEAdjustFlag)==3){
		JENum = prompt("Please enter your Journal Number:");
		if (JENum == null || JENum.trim() == '' || JENum.trim() == '0') {
			alert("The POST action cancelled.");
			return;
		}
	}
	
	 // Create our XMLHttpRequest object
    var hr = new XMLHttpRequest();
    // Create some variables we need to send to our PHP file
    var url = "AP_APP_MAIN_DB.php";
	var vars = "JENum="+JENum+"&LEDGER_ID="+LEDGER_ID+"&PAYMENT_ID="+PAYMENT_ID+"&ORG_ID="+ORG_ID+"&uid="+uid+"&action=POST_JE";
    hr.open("POST", url, true);
  
	hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded; charset=utf-8");
	hr.setRequestHeader("Content-length", vars.length);
	hr.setRequestHeader("Connection", "close");
    // Access the onreadystatechange event for the XMLHttpRequest object
    hr.onreadystatechange = function() {
	    if(hr.readyState == 4 && hr.status == 200) {
			var return_data = hr.responseText;
			document.getElementById("status").innerHTML ='';		    
			GET_POSTED_JE_NUMBER(return_data);			
			document.getElementById("btnPost").disabled = true;
			document.getElementById("btnAPRV").disabled = true;
			document.getElementById("btnPAY").disabled = true;
        	document.getElementById("btnSave").disabled = true;	
	    }
    }
    // Send the data to PHP now... and wait for response
    hr.send(vars); // Actually execute the request
	document.getElementById("status").innerHTML = '<div class="alert-box warning"><span>Transferring Journal to GL: </span>please wait .....</div>';
			
}
<!------------------------------------------------------------------------------------------------> 
function GET_POSTED_JE_NUMBER(p_result) {
	
	var uid =  "<?php print $_REQUEST["uid"]; ?>";
	var ORG_ID = "<?php print $ORG_ID; ?>";
	 // Create our XMLHttpRequest object
    var hr = new XMLHttpRequest();
    // Create some variables we need to send to our PHP file
    var url = "AP_APP_MAIN_DB.php";
	var vars = "PAYMENT_ID="+PAYMENT_ID+"&ORG_ID="+ORG_ID+"&uid="+uid+"&action=GET_POSTED_JE_NUMBER";
    hr.open("POST", url, true);
  
	hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded; charset=utf-8");
	hr.setRequestHeader("Content-length", vars.length);
	hr.setRequestHeader("Connection", "close");
    // Access the onreadystatechange event for the XMLHttpRequest object
    hr.onreadystatechange = function() {
	    if(hr.readyState == 4 && hr.status == 200) {
		    var return_data = hr.responseText;
			document.getElementById("status").innerHTML ='';
			var datacols =return_data.split("</td>");
			var datacols_count = datacols.length-1;
			if (eval(datacols_count) > 0){
				JE_HEADER_ID = eval(datacols[0].trim());
				alert('Journal Number: '+datacols[1].trim()+ ' have been transferred to GL Successfully.');
			}else{
				alert(p_result +'\n'+return_data);
			}
	    }
    }
    // Send the data to PHP now... and wait for response
    hr.send(vars); // Actually execute the request
	document.getElementById("status").innerHTML = '<div class="alert-box warning"><span>'+p_result+'<br>Get Journal Number from GL: </span>please wait .....</div>';			
}
<!------------------------------------------------------------------------------------------------> 
function REVERSE_JE() {
	
	var uid =  "<?php print $_REQUEST["uid"]; ?>";
	var ORG_ID = "<?php print $ORG_ID; ?>";
	var LEDGER_ID = "<?php print $LEDGER_ID; ?>";
	var JENum = '0';
	var JEType = '0';
	
	JENum = prompt("Please enter your Reverse Journal Number:");
	if (JENum == null || JENum.trim() == '' || JENum.trim() == '0') {
		alert("The [REVERSE] action cancelled.");
		return;
	}
	
	JEType = prompt("Please enter your Journal Category / Type:");
	if (JEType == null || JEType.trim() == '' || JEType.trim() == '0') {
		alert("The [REVERSE] action cancelled.");
		return;
	}
	
	
	 // Create our XMLHttpRequest object
    var hr = new XMLHttpRequest();
    // Create some variables we need to send to our PHP file
    var url = "AP_APP_MAIN_DB.php";
	var vars = "JEType="+JEType+"&JENum="+JENum+"&LEDGER_ID="+LEDGER_ID+"&PAYMENT_ID="+PAYMENT_ID+"&ORG_ID="+ORG_ID+"&uid="+uid+"&action=DELETE_POSTED_JE";
    hr.open("POST", url, true);
  
	hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded; charset=utf-8");
	hr.setRequestHeader("Content-length", vars.length);
	hr.setRequestHeader("Connection", "close");
    // Access the onreadystatechange event for the XMLHttpRequest object
    hr.onreadystatechange = function() {
	    if(hr.readyState == 4 && hr.status == 200) {
			var return_data = hr.responseText;
			document.getElementById("status").innerHTML ='';
			alert(return_data);
	    }
    }
    // Send the data to PHP now... and wait for response
    hr.send(vars); // Actually execute the request
	document.getElementById("status").innerHTML = '<div class="alert-box warning"><span>Reversing Transaction: </span>please wait .....</div>';
			
}

<!------------------------------------------------------------------------------------------------> 
function UPDATE_DATA_NEW () {
	
	var uid =  "<?php print $_REQUEST["uid"]; ?>";
	var ORG_ID = "<?php print $ORG_ID; ?>";
	 // Create our XMLHttpRequest object
    var hr = new XMLHttpRequest();
    // Create some variables we need to send to our PHP file
    var url = "AP_APP_MAIN_DB.php";
	var vars = "ORG_ID="+ORG_ID+"&uid="+uid+"&action=UPDATE_DATA_NEW";
    hr.open("POST", url, true);
  
	hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded; charset=utf-8");
	hr.setRequestHeader("Content-length", vars.length);
	hr.setRequestHeader("Connection", "close");
    // Access the onreadystatechange event for the XMLHttpRequest object
    hr.onreadystatechange = function() {
	    if(hr.readyState == 4 && hr.status == 200) {
			
		    var return_data = hr.responseText;
			
			document.getElementById("status").innerHTML = '';
			var datacols =return_data.split("</td>");
			var datarows_count = datacols.length-1;			
			if (eval(datarows_count) <=0){
			document.getElementById("status").innerHTML = '<div class="alert-box errormsg">'+return_data+'</div>';
			alert(return_data);
			return;	
			}
			
			PAYMENT_ID = eval(datacols[0].trim());
			
			if (ActiveTab=='JE'){UPDATE_DATA(PAYMENT_ID ,BuildHeaderSQL(),'',BuildGLJELines());}
			if (ActiveTab=='PM'){UPDATE_DATA(PAYMENT_ID ,BuildHeaderSQL(),BuildMSQL(),'');}
									
	    }
    }
    // Send the data to PHP now... and wait for response
    hr.send(vars); // Actually execute the request
	document.getElementById("status").innerHTML = '<div class="alert-box warning"><span>GET A NEW PAYMENT ID: </span>please wait .....</div>';
}
<!------------------------------------------------------------------------------------------------> 
function GET_NEW_DOCNUM (PAYMENT_ID) {
	
	var uid =  "<?php print $_REQUEST["uid"]; ?>";
	var ORG_ID = "<?php print $ORG_ID; ?>";
	 // Create our XMLHttpRequest object
    var hr = new XMLHttpRequest();
    // Create some variables we need to send to our PHP file
    var url = "AP_APP_MAIN_DB.php";
	var vars = "PAYMENT_ID="+PAYMENT_ID+"&ORG_ID="+ORG_ID+"&uid="+uid+"&action=GET_NEW_DOCNUM";
    hr.open("POST", url, true);
  
	hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded; charset=utf-8");
	hr.setRequestHeader("Content-length", vars.length);
	hr.setRequestHeader("Connection", "close");
    // Access the onreadystatechange event for the XMLHttpRequest object
    hr.onreadystatechange = function() {
	    if(hr.readyState == 4 && hr.status == 200) {
			if (document.getElementById('cbopay_mthd').options[document.getElementById('cbopay_mthd').selectedIndex].value==1){
			document.getElementById("btnAPRV").disabled = false;
			}
		    var return_data = hr.responseText;			
			var datacols =return_data.split("</td>");
			document.getElementById("status").innerHTML = '';
			document.getElementById("txtDocNum").value=datacols[0].trim();
			GET_PREPAY_VENDORS_LOV(PAYMENT_ID);
			if (ActiveTab=='PM'){CreateMatchedAccounting(PAYMENT_ID);}
			
					
			frmMode =2;						
	    }
    }
    // Send the data to PHP now... and wait for response
    hr.send(vars); // Actually execute the request
	document.getElementById("status").innerHTML = '<div class="alert-box warning"><span>GET A NEW DOC. NUMBER: </span>please wait .....</div>';
}
<!------------------------------------------------------------------------------------------------> 
function UPDATE_DATA (PAYMENT_ID ,HEADER_DATA ,M_DETAIL_DATA ,JE_DATA ) {
		
	var uid =  "<?php print $_REQUEST["uid"]; ?>";
	var ORG_ID = "<?php print $ORG_ID; ?>";
	 // Create our XMLHttpRequest object
    var hr = new XMLHttpRequest();
    // Create some variables we need to send to our PHP file
    var url = "AP_APP_MAIN_DB.php";
	var vars = "ORG_ID="+ORG_ID+"&PAYMENT_ID="+PAYMENT_ID+"&uid="+uid+"&action=UPDATE_DATA&JE_DATA="+JE_DATA+"&M_DETAIL_DATA="+M_DETAIL_DATA+"&HEADER_DATA="+HEADER_DATA;
	
    hr.open("POST", url, true);
	  
	hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded; charset=utf-8");
	hr.setRequestHeader("Content-length", vars.length);
	hr.setRequestHeader("Connection", "close");
    // Access the onreadystatechange event for the XMLHttpRequest object
    hr.onreadystatechange = function() {
	    if(hr.readyState == 4 && hr.status == 200) {
		    var return_data = hr.responseText;			
			document.getElementById("status").innerHTML = return_data;
			if (return_data.trim() !=''){return;}
			//if ((ActiveTab=='JE')&&(JE_HEADER_ID==0)&&(frmMode==2)){
				//if (confirm('Do you want to transfer the current Journal Entry to GL now?')){
					//POST_JE();
				//}
			//}			
			if (frmMode==1){
				GET_NEW_DOCNUM (PAYMENT_ID);
			}
			
			if (frmMode==2){
				GET_PREPAY_VENDORS_LOV(PAYMENT_ID);
				if ((ActiveTab=='PREAPP')||(ActiveTab=='PM')){CreateMatchedAccounting(PAYMENT_ID);}
			}
	    }
    }
    // Send the data to PHP now... and wait for response
    hr.send(vars); // Actually execute the request
	document.getElementById("status").innerHTML = '<div class="alert-box warning"><span>UPDATING DATA: </span>please wait .....</div>';
}
<!------------------------------------------------------------------------------------------------> 
function GET_CHEQUE_SIGNS() {
	
	var uid =  "<?php print $_REQUEST["uid"]; ?>";
	 // Create our XMLHttpRequest object
    var hr = new XMLHttpRequest();
    // Create some variables we need to send to our PHP file
    var url = "AP_APP_VOUCHER_DB.php";
	var vars = "uid="+uid+"&action=GET_CHEQUE_SIGNS";
    hr.open("POST", url, true);
  
	hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded; charset=utf-8");
	hr.setRequestHeader("Content-length", vars.length);
	hr.setRequestHeader("Connection", "close");
    // Access the onreadystatechange event for the XMLHttpRequest object
    hr.onreadystatechange = function() {
	    if(hr.readyState == 4 && hr.status == 200) {
		    var return_data = hr.responseText;
			document.getElementById("sign1").innerHTML = return_data;
			document.getElementById("sign2").innerHTML = return_data;
	    }
    }
    // Send the data to PHP now... and wait for response
    hr.send(vars); // Actually execute the request
				
}
<!------------------------------------------------------------------------------------------------> 
function GET_BANK_ACCOUNTS_LOV(CURR_CODE,CASH) {
	
	var uid =  "<?php print $_REQUEST["uid"]; ?>";
	var SHIP = 0;
	if (document.getElementById('cboSHIP').selectedIndex != -1){SHIP=document.getElementById('cboSHIP').options[document.getElementById('cboSHIP').selectedIndex].value;}
	 // Create our XMLHttpRequest object
    var hr = new XMLHttpRequest();
    // Create some variables we need to send to our PHP file
    var url = "AP_APP_MAIN_DB.php";
	var vars = "CURR_CODE="+CURR_CODE+"&uid="+uid+"&action=GET_BANK_ACCOUNTS_LOV";
    hr.open("POST", url, true);
  
	hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded; charset=utf-8");
	hr.setRequestHeader("Content-length", vars.length);
	hr.setRequestHeader("Connection", "close");
    // Access the onreadystatechange event for the XMLHttpRequest object
    hr.onreadystatechange = function() {
	    if(hr.readyState == 4 && hr.status == 200) {
		    var return_data = hr.responseText;
			document.getElementById("cbocashacc").innerHTML = return_data;
			if (CASH.trim().length > 0){document.getElementById("cbocashacc").value=CASH.trim();}
			GET_AP_TRADE_PAY_SCHEDULE(SHIP,0);
	    }
    }
    // Send the data to PHP now... and wait for response
    hr.send(vars); // Actually execute the request
				
}
<!------------------------------------------------------------------------------------------------> 
function VaildateHeader() {
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
	
	var CASH_AMOUNT = document.getElementById('txtAmount').value.replace(/,/gi, '');
	if ((isNaN(CASH_AMOUNT))||(eval(CASH_AMOUNT)<=0)||(CASH_AMOUNT=='')||(CASH_AMOUNT.trim().length==0)){
		alert('Vaildation:\n\nPaid Amount is Required.');
		document.getElementById('txtAmount').focus();
		document.getElementById('txtAmount').select();
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
function VaildateSumofAmt() {
	/*
	var CASH_GLACCOUNT = document.getElementById('cbocashacc').options[document.getElementById('cbocashacc').selectedIndex].value;
	var CASH_AMOUNT = document.getElementById('txtAmount').value.replace(/,/gi, '');
	var NetAmount = document.getElementById('NetAmount').value.replace(/,/gi, '');
	var totAmt = 0;
	
	if ((isNaN(CASH_AMOUNT))||(eval(CASH_AMOUNT)<=0)||(CASH_AMOUNT=='')||(CASH_AMOUNT.trim().length==0)){CASH_AMOUNT = 0;}
	
	var tJE = $('#recJEGrid').DataTable();
	if (tJE.data().count()){
		var table = document.getElementById("recJEGrid");
		var rowCount = table.rows.length;
		for (var i=1; i<rowCount; i++) {
			var row = table.rows[i];
			if (row.cells[0].childNodes[0].value.trim().localeCompare(CASH_GLACCOUNT.trim())==0){
				totAmt = eval(totAmt)+eval(row.cells[5].childNodes[0].value.replace(/,/gi, ''))-eval(row.cells[4].childNodes[0].value.replace(/,/gi, ''));
			}
		}
	}
		
	var recRCTGrid = $('#recRCTGrid').DataTable();
	
	if ((eval(CASH_AMOUNT)!=eval(totAmt))||((eval(CASH_AMOUNT)!=eval(NetAmount))&&(recRCTGrid.data().count()))){
		if (!confirm('Decision:\n\nThe sum of amount is not equal to the payment details and/or JE amount.\nDo you want to [Save] your changes anyway?')) {
			return false;
		}else{
			return true;
		}
	}
	*/
	
	
	
	return true;
}
<!------------------------------------------------------------------------------------------------> 
function BuildHeaderSQL(){
	var BuilderHeaderSQL ="";
	var ORG_ID = "<?php print $ORG_ID; ?>";
	var uid =  "<?php print $_REQUEST["uid"]; ?>";
	var PAYEE_ID = document.getElementById('txtPayeeNum').value.replace(/,/gi, '');
	if (PAYEE_ID==''){PAYEE_ID=0;}
	BuilderHeaderSQL = " SELECT ";
	BuilderHeaderSQL = BuilderHeaderSQL + PAYMENT_ID +" AS PAYMENT_ID,";
	BuilderHeaderSQL = BuilderHeaderSQL + "'"+document.getElementById('cboCurr').options[document.getElementById('cboCurr').selectedIndex].value+"' AS  CURRENCY_CODE,";
	BuilderHeaderSQL = BuilderHeaderSQL + "TO_DATE('" + document.getElementById('txtDocDate').value + "','YYYYMMDD') AS  PAYMENT_DOC_DATE,";
	BuilderHeaderSQL = BuilderHeaderSQL + "TO_DATE('" + document.getElementById('txtGLDATE').value + "','YYYYMMDD') AS  GL_DATE,";
	BuilderHeaderSQL = BuilderHeaderSQL + document.getElementById('txtCurrRate').value + "  AS  CURRENCY_CONVERSION_RATE,";
	BuilderHeaderSQL = BuilderHeaderSQL + "'"+document.getElementById('cbocashacc').value+"' AS  BANK_ACCOUNT_NUM,";
	BuilderHeaderSQL = BuilderHeaderSQL + document.getElementById('txtAmount').value.replace(/,/gi, '') + "  AS  SUM_AMOUNT,";	
	BuilderHeaderSQL = BuilderHeaderSQL + "'"+document.getElementById('txtPayeeName').value+"' AS  PAYEE_NAME,";
	BuilderHeaderSQL = BuilderHeaderSQL + "'"+search_txt(document.getElementById('txtNotes').value)+"' AS  NOTES,";
	if ((document.getElementById('txtChequeDate').value.trim().length!=0)&&(!isNaN(document.getElementById('txtChequeDate').value.trim()))){
		BuilderHeaderSQL = BuilderHeaderSQL + PAYEE_ID + " AS  PAYEE_ID,";
	}else{
		BuilderHeaderSQL = BuilderHeaderSQL + PAYEE_ID + " AS  PAYEE_ID,";
	}
	BuilderHeaderSQL = BuilderHeaderSQL + document.getElementById('cbopay_mthd').options[document.getElementById('cbopay_mthd').selectedIndex].value + "  AS  CASH_FLAG,";
	BuilderHeaderSQL = BuilderHeaderSQL + ORG_ID+ " AS ORG_ID,";
	BuilderHeaderSQL = BuilderHeaderSQL + JE_HEADER_ID+ " AS JE_HEADER_ID,";
	BuilderHeaderSQL = BuilderHeaderSQL + uid+ " AS USER_ID,";
	BuilderHeaderSQL = BuilderHeaderSQL + "'"+document.getElementById('txtChequeNum').value+"' AS CHEQUE_NUM,";
	BuilderHeaderSQL = BuilderHeaderSQL + "'"+document.getElementById('cboAppv1').options[document.getElementById('cboAppv1').selectedIndex].value+"' AS APPROVE_SIGN1,";
	BuilderHeaderSQL = BuilderHeaderSQL + "'"+document.getElementById('cboAppv2').options[document.getElementById('cboAppv2').selectedIndex].value+"' AS APPROVE_SIGN2,";
	if (document.getElementById('txtChequeDate').value.trim().length==8){
		BuilderHeaderSQL = BuilderHeaderSQL + "TO_DATE('" + document.getElementById('txtChequeDate').value + "','YYYYMMDD') AS  CHEQUE_MATURITY_DATE,";
	}else{
		BuilderHeaderSQL = BuilderHeaderSQL + "null AS  CHEQUE_MATURITY_DATE,";
	}
	BuilderHeaderSQL = BuilderHeaderSQL + document.getElementById('nonE').options[document.getElementById('nonE').selectedIndex].value + "  AS  NON_ENDORSEMENT,";
	
	var ven_emp_text = jQuery("#ven_emp_text").value;
	if(ven_emp_text=='undefined' || typeof ven_emp_text === "undefined"){
		ven_emp_text='0';
	}
	BuilderHeaderSQL = BuilderHeaderSQL +"'"+ ven_emp_text +"'"+ "  AS  VEN_EMP_CODE ";
	BuilderHeaderSQL = BuilderHeaderSQL +" FROM DUAL";

	return BuilderHeaderSQL;
}

<!------------------------------------------------------------------------------------------------>
/*
function CheckJEPostedBefore() {
	 // Create our XMLHttpRequest object
    var hr = new XMLHttpRequest();
    // Create some variables we need to send to our PHP file
    var url = "AP_APP_MAIN_DB.php";
	var vars = "PAYMENT_ID="+PAYMENT_ID+"&action=VaildateJEPostedBefore";
    hr.open("POST", url, true);
    
	hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded; charset=utf-8");
	hr.setRequestHeader("Content-length", vars.length);
	hr.setRequestHeader("Connection", "close");
    // Access the onreadystatechange event for the XMLHttpRequest object
    hr.onreadystatechange = function() {
	    if(hr.readyState == 4 && hr.status == 200) {
		    var return_data = hr.responseText;
			document.getElementById("status").innerHTML = '';
			if (return_data.trim().length==0){return_data=1;}
			alert(return_data.trim());
			if (return_data.trim()=='0'){return true;}
			return VaildateJEPostedBefore(eval(return_data));
		}
		return false;
    }
    // Send the data to PHP now... and wait for response
    hr.send(vars); // Actually execute the request
	document.getElementById("status").innerHTML = '<div class="alert-box warning"><span>Validate if JE posted before: </span>please wait .....</div>';
						
}
<!------------------------------------------------------------------------------------------------>

function VaildateJEPostedBefore(X) {
	if (X > 0){
		if (!confirm('Decision:\n\nThe entry has been posted to the General Ledger.\nDo you want to delete the posted Journal entry to [Save] your changes?')) {
			return false;
		}else{
			//PHARCO_PAYABLES_APP.DELETE_POSTED_JE(?)
			 // Create our XMLHttpRequest object
			var hr = new XMLHttpRequest();
			// Create some variables we need to send to our PHP file
			var url = "AP_APP_MAIN_DB.php";
			var vars = "PAYMENT_ID="+PAYMENT_ID+"&action=DELETE_POSTED_JE";
			hr.open("POST", url, true);
		  
			hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded; charset=utf-8");
			hr.setRequestHeader("Content-length", vars.length);
			hr.setRequestHeader("Connection", "close");
			// Access the onreadystatechange event for the XMLHttpRequest object
			hr.onreadystatechange = function() {
				if(hr.readyState == 4 && hr.status == 200) {
					var return_data = hr.responseText;
					document.getElementById("status").innerHTML = '';
					if (return_data.trim().length != 0){
						return false;
					}
				}
			}
			// Send the data to PHP now... and wait for response
			hr.send(vars); // Actually execute the request
			document.getElementById("status").innerHTML = '<div class="alert-box warning"><span>Deleting posted Journal: </span>please wait .....</div>';
			return true;
		}	
	}
	return true;
}
*/
</script>
