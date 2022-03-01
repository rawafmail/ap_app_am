
<fieldset id="JE" class="tabcontent">
<table width="100%"  cellspacing="0" cellpadding="0">
<tr>
<td   rowspan="2" valign="top">

<fieldset id="JE1" style="font-family: verdana,arial,sans-serif;font-size:12px; color:#000; padding:0">
<table border="0" cellspacing="0" cellpadding="0">
  <tr>
   <td align="right">Withholding Tax:</td><td><select name="cboWAT" id="cboWAT"></select></td>
  </tr>
  
  <tr>
    <td align="right">Value Added Tax:</td><td><select name="cboSAL" id="cboSAL"></select></td>
  </tr>
  <tr>
    
    <td align="right">Is Recover. VAT:</td>
    <td><select id="cboirrsal">
      <option value="0">No</option>
      <option value="1">Yes</option>
    </select></td>
  </tr>
  <tr>
   <td align="right">Other Tax:</td><td><select name="cboVAT" id="cboVAT"></select></td>
  </tr>
  <tr>
    <td align="right" valign="top" >Invoice Num.:</td><td valign="top"><input name="invje" type="text" id="invje" /></td>
  </tr>
</table>
</fieldset>

</td>
<!--======================================================= -->
<td rowspan="2"  valign="top">
<fieldset id="JE2" style="font-family: verdana,arial,sans-serif;font-size:12px; color:#000; padding:0">

<table border="0" cellspacing="0" cellpadding="0">
  <tr>
   <td align="right">Vendor:</td>
   <td colspan="3"><table width="100%" border="0" cellspacing="0" cellpadding="0">
     <tr>
         <td width="30%"><input name="vendorid" type="text" id="vendorid" size="20" onblur="SEARCH_VENDOR_NAME(this.value,'vendorn');" /></td>
         <td><input type="button" name="search_vend" id="search_vend"   class="S_img" onclick="javascript:SEARCH_VENDOR_OVERLAY(this.id);"  /></td>
         <td><input name="vendorn" type="text" id="vendorn" /></td>
       </tr>
 </table></td>
</tr>
  <tr>
   <td align="right">Shipment:</td><td><select name="cboSHIP" id="cboSHIP" onchange="GET_AP_TRADE_PAY_SCHEDULE(this.options[this.selectedIndex].value,0);"></select></td>
   <td>P.O.:</td>
   <td><select name="cboJEPO" id="cboJEPO" style="width:150px">
   </select></td>
  </tr>
  <tr>
    <td align="right">Pay. Schedule:</td>
    <td colspan="3"  width="75%">
    <select name="cboSCHED" id="cboSCHED">
    </select>
    </td>
  </tr>
  <tr>
    <td align="right">Expense:</td><td colspan="3"><select name="cboEXP" id="cboEXP"></select></td>
  </tr>
  <tr>
    <td align="right">Major:</td>
    <td><select name="cboMJ" id="cboMJ"  onchange="javascript:GET_MINOR(this.options[this.selectedIndex].value);" style="width:150px">
    </select></td>
    <td>Minor:</td>
    <td align="right"><select name="cboMI" id="cboMI" style="width:150px">
    </select></td>
  </tr>
  
</table>
</fieldset>
 
  </td>


<td colspan="3" valign="top">
<fieldset id="JE3" style="font-family: verdana,arial,sans-serif;font-size:12px; color:#000; padding:0">

<table border="0" cellspacing="0" cellpadding="0">
  <tr>
   <td align="right">Account:</td>
   <td width="30%"><input name="accnumid" type="text" id="accnumid" style="background-color:#FFC" onblur="SEARCH_ACC_NAME(this.value,'accnumdesc',this.id,'A');" onkeypress="HandleJEAmounts(event,this.id);" /></td>
   <td style="width:16px"><input type="button" name="search_acc" id="search_acc"  class="S_img" onclick="javascript:SEARCH_ACCOUNTS_OVERLAY();" ></td>
   <td><input name="accnumdesc" type="text" id="accnumdesc" readonly /></td>
  </tr>
  <tr>
  <td align="right">C.Center:</td>
  <td width="30%"><input name="ccnumid" type="text" id="ccnumid" onkeypress="HandleJEAmounts(event,this.id);" onblur="SEARCH_ACC_NAME(this.value,'ccnumdesc',this.id,'C');" style="background-color:#FFC"  /></td>
  <td width="16px"><input type="button" name="search_cc" id="search_cc"  class="S_img" onclick="javascript:SEARCH_CCENTERS_OVERLAY();" ></td>
  <td><input name="ccnumdesc" type="text" id="ccnumdesc" readonly  /></td>
  </tr>

  <tr>
   <td align="right">Debit:</td>
   <td colspan="3">
   <input name="dr" id="dr" type="text" size="15" style="width:145px;background-color:#FFC; text-align:right" onkeypress="HandleJEAmounts(event,this.id);" /></td>
  </tr>
  <tr>
   <td align="right">Credit:</td>
   <td colspan="3"><input name="cr" id="cr" type="text" size="15" style="width:145px;background-color:#FFC; text-align:right" onkeypress="HandleJEAmounts(event,this.id);" /></td>
  </tr>
</table>
</fieldset>

</td>
</tr>
<tr>
  <td><input type="button" name="addJELine" id="addJELine" value="Add Line" onclick="AddJELine();" /></td>
  <td><input type="button" name="btnClearLine" id="btnClearLine" value="Clear" onclick="ClearJE('JE1');ClearJE('JE2');ClearJE('JE3');" /></td>
  <td><input type="button" id="create_m_je" value="Complete Journal Entry" onclick="CompleteJournalEntry(0);" style="width:200px" /></td>
</tr>
<tr>
<td colspan="5">
<table width="100%" border="0" cellspacing="0" cellpadding="1px">
<tr>
<td>
<div style="border-style:solid;border-width:1px; border-color:#999; width:1000px">
<table id="recJEGrid" class="display cell-border compact TFtable nowrap" style="width:100%"><thead>
<tr>
<th>Account</th>
<th>C. Center</th>
<th>Account Description</th>
<th>C. Center Description</th>
<th>Entered Debit</th>
<th>Entered Credit</th>
<th>Accounted Debit</th>
<th>Accounted Credit</th>
<th></th>
<!--LINE_TYPE_DFF-->
<!--PAYMENT_ID-->
<!--WAT_TAX_RATE-->
<!--IR_SAL_TAX_AMT-->
<!--VALUE_ADD_TAX_RATE-->
<!--PENALTY_AMOUNT-->
<!--VENDOR_CODE-->
<!--PO_NUM-->
<!--INVOICE_NUM-->
<!--RECEIPT_NUM-->
<!--MATCH_TYPE_ID-->
<!--JE_TYPE-->
<!--JE_LINE_NUM-->
<!--JE_HEADER_ID-->
<!--SHIPMENT_NUM-->
<!--EXPENSE_TYPE-->
<!--MAJOR_CODE-->
<!--MINOR_CODE-->
<!--SALES_R_FLAG-->
<!--SALES_TAX_RATE-->

</tr>

</thead>
<tbody>

</tbody>
</table>
</div>
</td>


<td valign="top">
<div style="border-style:solid;border-width:1px; border-color:#999; ">

<table width="100%" border="0" cellspacing="0" cellpadding="2px" style="background-color:#EFEFEF;" >
  <tr>
    <td style="padding-bottom:5px" width="50%"><b>Summary</b></td>
    <td style="padding-bottom:5px"><b>Value</b></td>
  </tr>
  <tr>
    <td>Total Entered Dr:</td>
    <td><input id="TotEnteredD"  style="text-align: right;" type="text" readonly="readonly" /></td>
  </tr>
  <tr>
    <td>Total Entered Cr:</td>
    <td><input id="TotEnteredC" type="text"  style="text-align: right;"  readonly="readonly" /></td>
  </tr>
  <tr>
    <td>Total Account. Dr:</td>
    <td><input id="TotAcctD" type="text"  style="text-align: right;"   readonly="readonly" /></td>
  </tr>
  <tr>
    <td>Total Account. Cr:</td>
    <td><input id="TotAcctC" type="text"  style="text-align: right;"  readonly="readonly" /></td>
  </tr>
  <tr>
    <td>Variance:</td>
    <td><input id="JEVariance" type="text"  style="text-align: right; background-color:#FCF" readonly="readonly" /></td>
  </tr>
</table>
</div></td>

</tr>
</table>



</td>
</tr>
</table>

 </fieldset>

<!------------------ SEARCH ACCOUNTS ----------------->
<div id="SEARCH_ACCOUNTS_OVERLAY" class="overlay">
<div  id="find_myACCOUNTS">
<a href="#" onClick="SEARCH_ACCOUNTS_OVERLAY()" id="close" class="close">X</a>
<fieldset style="color:#000">
<legend>-- Find Account --</legend>
<table width="100%" cellspacing="0" cellpadding="0">
<tr>
 <td><input id="search_ACCOUNTS" type="text" value="%" style="width:100%"  onKeyPress="javascript:HandleKeyEvent5(event)" onKeyDown="javascript:HandleKeyEvent5(event)" onKeyUp="javascript:HandleKeyEvent5(event)" ></td><td align="left"><input id="Find" name="Find" type="button" value="Find" onClick="javascript:SEARCH_ACCOUNTS_GET_RESULT();"></td>
</tr>
<tr>
<td colspan="2" id="search_ACCOUNTS_results">



</td>
</tr>
</table>



</fieldset>
</div>
</div>

<!------------------ SEARCH CCENTERS ----------------->
<div id="SEARCH_CCENTERS_OVERLAY" class="overlay">
<div  id="find_myCCENTERS">
<a href="#" onClick="SEARCH_CCENTERS_OVERLAY()" id="close" class="close">X</a>
<fieldset style="color:#000">
<legend>-- Find Cost Center --</legend>
<table width="100%" cellspacing="0" cellpadding="0">
<tr>
 <td><input id="search_CCENTERS" type="text" value="%" style="width:100%"  onKeyPress="javascript:HandleKeyEvent6(event)" onKeyDown="javascript:HandleKeyEvent6(event)" onKeyUp="javascript:HandleKeyEvent6(event)" ></td><td align="left"><input id="Find" name="Find" type="button" value="Find" onClick="javascript:SEARCH_CCENTERS_GET_RESULT();"></td>
</tr>
<tr>
<td colspan="2" id="search_CCENTERS_results">



</td>
</tr>
</table>



</fieldset>
</div>
</div>