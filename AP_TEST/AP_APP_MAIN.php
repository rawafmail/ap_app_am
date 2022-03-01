<fieldset style="font-family: verdana,arial,sans-serif;font-size:12px; color:#000;" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr id="status1"><td colspan="2" id="status"></td></tr>   
<tr>
<td width="40%">

<!--============================================================================================ -->


<fieldset style="font-family: verdana,arial,sans-serif;font-size:12px; color:#000;" >
<table  width="100%"  border="0" cellspacing="0" cellpadding="0" >
<tr align="left">
<td align="right">Currency:</td>
<td>
<select name="cboCurr" id="cboCurr"  style="background-color:#FFC" onchange="GET_BANK_ACCOUNTS_LOV(this.options[this.selectedIndex].value,'');">
<?php
$SQL1 ="SELECT DISTINCT b.CURRENCY_CODE, t.NAME FROM fnd_currencies_tl t, fnd_currencies b  WHERE b.currency_code = t.currency_code AND b.ENABLED_FLAG = 'Y' AND t.LANGUAGE ='US' ORDER BY b.CURRENCY_CODE";
	$RS1  = odbc_exec($CONNECT , $SQL1);
	print "<option value='0'>--</option>";
	while(odbc_fetch_row($RS1)){
	print   "<option value='".odbc_result($RS1, 1)."'>".odbc_result($RS1, 1)." </option>";	
	}
?>
</select>
</td>
<td align="right">Payment Method:</td>
<td>
<select name="cbopay_mthd" id="cbopay_mthd">
  <option value="0">Cheque</option>
  <option value="1">Cash</option>
  <option value="2">Bank Transfer</option>
  <option value="3">Adjustment</option>
</select>
</td>
</tr>
<tr>
<td align="right">Rate:</td>
<td><input name="txtCurrRate" id="txtCurrRate" type="text" size="2"  style="background-color:#FFC" /></td>

<td align="right">Amount:</td>
<td><input name="txtAmount" id="txtAmount" type="text" style="background-color:#FFC; text-align:right" /></td>

</tr>
<tr>
<td align="right">Account:</td>
<td align="right" colspan="1">
<select name="cbocashacc" id="cbocashacc">
<option value="0">--</option>
</select>
</td>

<td align="right">Vendor/Emp:</td>
<td align="right" colspan="1">
<input name="ven_emp_text" id="ven_emp_text" type="text" style="background-color:#FFC;" onChange="javascript:SEARCH_PAYEE_GET_RESULT();"/>
</td>
</tr>
</table>

 

</fieldset>

</td>

<!--==================================== -->
<td>
<fieldset style="font-family: verdana,arial,sans-serif;font-size:12px; color:#000;" >
<table  width="100%"  border="0" cellspacing="0" cellpadding="0" >
<tr align="left">
<!-- 
<td align="right">Trans.Type:</td>
<td><select id="transtype"></select></td>
-->
<td align="right">Trans. Number:</td>
<td width="20%"><input name="txtDocNum" type="text" id="txtDocNum" size="12" placeholder="Auto" readonly="readonly" /></td>
<td width="16px"><input type="button" name="search_doc" id="search_doc"  class="S_img" onclick="SEARCH_PAYMENT_OVERLAY();" ></td>
<td align="right">Payee:</td>
<td><input name="txtPayeeNum" type="text" id="txtPayeeNum" size="8" onkeydown="FinfKeyEvent(event,this.id,'payeen','get_payee',0)" onblur="ReturnFindValueDB(this.value,0)"/></td>
<td width="16px"><input type="button" name="search_payee" id="search_payee"  class="S_img" onclick="SEARCH_PAYEE_OVERLAY();" ></td>
<td colspan="2"><input readonly name="txtPayeeName" type="text" id="txtPayeeName" /></td>
</tr>
<tr align="left">
  <td align="right">Trans. Date:</td>
  <td><input name="txtDocDate" id="txtDocDate" type="text" size="8"  style="background-color:#FFC" /></td>
  <td></td>
  <td align="right" valign="top">Notes:</td>
  <td colspan="4" rowspan="2"><textarea name="txtNotes" id="txtNotes"  style="width:100%;font-size:10pt;" ></textarea></td>
  </tr>
<tr align="left">
  <td align="right">GL Date:</td>
  <td><input name="txtGLDATE" id="txtGLDATE" type="text" size="8"  style="background-color:#FFC" /></td>
  <td></td>
  <td align="right"></td>
</tr>
</table>

 

</fieldset>
</td>
</tr>
<!--==================================== -->

</table>




</fieldset>


<!------------------ SEARCH PAYMENT ----------------->
<div id="SEARCH_PAYMENT_OVERLAY" class="overlay" >
<div  id="find_myPAYMENT"  style="width:730px">
<a href="#" onClick="SEARCH_PAYMENT_OVERLAY()" id="close" class="close">X</a>
<fieldset style="color:#000;" id="SEARCH_PAY_FIELDS">
<legend>-- Find Transaction --</legend>
<table width="100%" cellspacing="0" cellpadding="0" >
<tr>
<td align="right">Trans.:</td>
  <td align="left"><input id="search_PAYMENT" type="text"  style="width:150px"   ></td>  
  <td align="right">From Date:</td>
  <td align="left"><input name="search_FDATE"  id="search_FDATE" type="text" size="8" maxlength="8" placeholder="YYYYMMDD" style="width:150px" /></td>
 
</tr>
<tr>
 <td align="right">Amount:</td>
  <td align="left"><input name="search_AMOUNT" id="search_AMOUNT" type="text" style="width:150px" /></td>
  <td align="right">To Date:</td>
  <td align="left"><input name="search_TDATE"  id="search_TDATE" type="text" size="8" maxlength="8" placeholder="YYYYMMDD" style="width:150px" /></td>
</tr>
<tr>
  <td align="right">Status:</td>
  <td align="left"><select name="search_STATUS"  id="search_STATUS" style="width:150px">
  <option value="A">--</option>
  <option value="NEW">New</option>
  <option value="APPROVE">Approved</option>
  <option value="PAY">Paid</option>
  <option value="POSTED">Posted</option>
  </select></td>
  <td align="right">Payment Account:</td>
  <td align="left"><select name="search_PAYACCOUNT"  id="search_PAYACCOUNT" style="width:150px">
<?php  
$SQL = "SELECT FLEX_VALUE, DESCRIPTION FROM AP_APPS.AP_APP_BANK_ACCOUNTS ORDER BY FLEX_VALUE";
$RS  = odbc_exec($CONNECT , $SQL);
print '<option value="0">--</option>';
while(odbc_fetch_row($RS)){
print '<option value="'.odbc_result($RS,1) . '">'.odbc_result($RS,1).' - '.odbc_result($RS,2).'</option>';

}
?>
  </select></td>
</tr>
<tr>
  <td align="right">Note:</td>
  <td><input name="search_NOTE"  id="search_NOTE" type="text" value="%" /></td>
  <td align="right">Payment Method:</td>
  <td align="left"><select name="search_PAYMETHOD"  id="search_PAYMETHOD" style="width:150px">
  <option value="-1">--</option>
  <option value="0">Cheque</option>
  <option value="1">Cash</option>
  <option value="2">Bank Transfer</option>
  <option value="3">Adjustment</option>
  </select></td>
 
</tr>
<tr>
  <td align="right">Payee:</td>
  <td align="left"><input name="search_PAYEE" id="search_PAYEE" type="text" value="%" /></td>
 <td align="right">Journal No.:</td>
 <td align="left"><input name="search_JENUM" id="search_JENUM" type="text" value="%" style="width:150px" /></td>
</tr>
<tr>
  <td colspan="4" align="right"><table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
    <td align="left"><input id="Find" name="Find" type="button" value="Find" onClick="javascript:SEARCH_PAYMENT_GET_RESULT();"></td>
 	<td align="left"><input id="ClearSearch" name="ClearSearch" type="button" value="Clear" onClick="javascript:SEARCH_PAYMENT_CLEAR();"  style="width:100px"></td>
    </tr>
  </table></td>
  </tr>
<tr>
<td colspan="4">
<div  id="search_PAYMENT_results"  style="height:150px; overflow:scroll">
</div>


</td>
</tr>
</table>



</fieldset>
</div>
</div>

<!------------------ SEARCH PAYEE ----------------->
<div id="SEARCH_PAYEE_OVERLAY" class="overlay">
<div  id="find_myPAYEE">
<a href="#" onClick="SEARCH_PAYEE_OVERLAY()" id="close" class="close">X</a>
<fieldset style="color:#000">
<legend>-- Find Vendor --</legend>
<table width="100%" cellspacing="0" cellpadding="0">
<tr>
 <td><input id="search_PAYEE_N" name="search_PAYEE_N" type="text" value="%" style="width:100%"   ></td><td align="left"><input id="FindPayee" name="FindPayee" type="button" value="Find" onClick="javascript:SEARCH_PAYEE_GET_RESULT();"></td>
</tr>
<tr>
<td colspan="2" id="search_PAYEE_results">



</td>
</tr>
</table>




</fieldset>
</div>
</div>