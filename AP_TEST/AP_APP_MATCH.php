
<fieldset id="PM" class="tabcontent">
<table border="0" cellspacing="0" cellpadding="0">
<tr>
<!------------------------------------ -->
<td>
<fieldset id="PM1" style="font-family: verdana,arial,sans-serif;font-size:12px; color:#000;">
<table border="0" cellspacing="0" cellpadding="0">
<tr>
<td align="right">Payment/Match Type:</td>
<td><select name="matchtype" id="matchtype" onchange="Clear('PM2');Clear('PM2');" ></select></td>
</tr>
<tr>
<td align="right">Invoice Number:</td>
<td><input name="invcnum" id="invcnum" type="text" /></td>
</tr>
<tr>
<td align="right">Invoice Date:</td>
<td><input name="txtInvDate" id="txtInvDate" type="text" size="8" maxlength="8" placeholder="YYYYMMDD" /> </td>   
</tr>
</table>
</fieldset>
</td>
<!------------------------------------ -->

<td>
<fieldset id="PM2" style="font-family: verdana,arial,sans-serif;font-size:12px; color:#000;">
<table border="0" cellspacing="0" cellpadding="0px">
<tr>
<td align="right">Vendor:</td>
<td><input name="vendorid_1" type="text" id="vendorid_1" size="20" onblur="SEARCH_VENDOR_NAME(this.value,'vendorn_1');" /></td>
<td><input type="button" name="search_vend_1" id="search_vend_1"  class="S_img" style="width:20px" onclick="javascript:SEARCH_VENDOR_OVERLAY(this.id);" ></td>
</tr>
<tr>
  <td align="right">&nbsp;</td>
  <td colspan="2"><input name="vendorn_1" type="text" id="vendorn_1" /></td>
  </tr>
<tr>
<td align="right">Purchase Order:</td>
<td><select name="ponum_1" id="ponum_1" onchange="javascript:GET_RECEIPTS(this.options[this.selectedIndex].value,document.getElementById('invperiod').options[document.getElementById('invperiod').selectedIndex].value,document.getElementById('store').options[document.getElementById('store').selectedIndex].value);GET_MATCH_PO_DTL();">
  <option value="0">--</option> 
  
</select></td>
<td><input type="text" name="PO_NUM_TXT" id="PO_NUM_TXT" onblur="GET_MATCH_PO_DTL();" /></td>
</tr>
</table>
</fieldset>
</td>
<!------------------------------------ -->

<td>
<fieldset id="PM3" style="font-family: verdana,arial,sans-serif;font-size:12px; color:#000;">
<table border="0" cellspacing="0" cellpadding="0">
<tr>
<td align="right">Inventory Period:</td>
<td colspan="2"><select name="invperiod" id="invperiod" onchange="javascript:GET_RECEIPTS(document.getElementById('ponum_1').options[document.getElementById('ponum_1').selectedIndex].value,this.options[this.selectedIndex].value,document.getElementById('store').options[document.getElementById('store').selectedIndex].value);" style="width:140px"> </select></td>
</tr>
<tr>
<td align="right">Sub-Inventory:</td>
<td colspan="2"><select name="store" id="store" onchange="javascript:GET_RECEIPTS(document.getElementById('ponum_1').options[document.getElementById('ponum_1').selectedIndex].value,document.getElementById('invperiod').options[document.getElementById('invperiod').selectedIndex].value,this.options[this.selectedIndex].value);" style="width:140px"> </select></td>
</tr>
<tr>
<td align="right">Receipt Num:</td>
<td><select name="rcv_num" id="rcv_num" onchange="GET_MATCH_PO_DTL()" style="width:35px"> </select></td>
<td><input name="rcv_num1" id="rcv_num1" type="text" style="width:105px" onblur="GET_MATCH_PO_DTL()"/></td>
</tr>
</table>
</fieldset>
</td>
</tr>

<!--======================================== -->
<tr>
<td colspan="3" style="padding-top:5px" width="800">
<!-- -->
<table width="100%" border="0" cellspacing="0" cellpadding="1px">
<tr>
<td>
<div style="border-style:solid;border-width:1px; border-color:#999; width:920px">
<table id="recRCTGrid" class="display cell-border compact TFtable nowrap " style="width:100%"><thead>
<tr>
<th>Invoice Number</th> 
<th>Invoice Date</th> 
<th>Quantity</th> 
<th>UOM</th> 
<th>Unit Cost</th> 
<th>Invoice Price </th> 
<th>PO Unit Price</th> 
<th>Price Variance</th> 
<th>Invoiced Value</th> 
<th>VAT Rate</th> 
<th>VAT R. Flag</th> 
<th>VAT Value</th> 
<th>Other Tax Rate</th> 
<th>Other Tax Value</th> 
<th>AWT Rate</th> 
<th>AWT Value</th> 
<th>Penalty Rate</th> 
<th>Penalty Value</th> 
<th>Net Amount</th> 
<th>Receipt Number</th> 
<th>Receipt Number M</th> 
<th>Item&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th> 
<th>Vendor&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
<th></th>
<th></th>

 <!-- ORG_ID-->
 <!-- PO_HEADER_ID--> 
 <!-- PO_LINE_ID--> 
 <!-- TRANSACTION_ID--> 
 <!-- INVENTORY_ITEM_ID--> 
 <!-- SUB_INVENTORY-->
 <!-- INV_PERIOD--> 
 <!-- VENDOR_CODE--> 
 <!-- MATCH_TYPE_ID-->  
 <!-- GLACCOUNT-->  
 <!-- COSTCENTER-->  
 <!-- PENALTY_GLACCOUNT-->  
 <!-- PENALTY_GLCOSTCENTER--> 


</tr>

</thead>
<tbody>
</tbody>
</table>
</div>

</td>


<!-- -->


<td valign="top">
<div style="border-style:solid;border-width:1px; border-color:#999; width:300px">

<table width="100%" border="0" cellspacing="0" cellpadding="2px" style="background-color:#EFEFEF;" >
  <tr>
    <td style="padding-bottom:5px" width="55%"><b>Summary</b></td>
    <td style="padding-bottom:5px"><b>Value</b></td>
  </tr>
  <tr>
    <td>Net Invoice</td>
    <td><input id="TotAmt" type="text"  style="text-align: right;"  readonly="readonly" /></td>
  </tr>
  <tr>
    <td>Value Added Tax</td>
    <td><input id="SalesTax" type="text"  style="text-align: right;"  readonly="readonly" /></td>
  </tr>
  <tr>
    <td>Recoverable VAT</td>
    <td><input id="RecoverableSalesTax"  style="text-align: right;" type="text" readonly="readonly" /></td>
  </tr>
  <tr>
    <td>Sub-Total</td>
    <td><input id="SubAmt" type="text"  style="text-align: right;"  readonly="readonly" /></td>
  </tr>
  <tr>
    <td>Withholding Tax</td>
    <td><input id="WithholdingTax" type="text"  style="text-align: right;"  readonly="readonly" /></td>
  </tr>
  <tr>
    <td>Other Tax</td>
    <td><input id="ValueAddedTax" type="text"  style="text-align: right;"   readonly="readonly" /></td>
  </tr>
  <tr>
    <td>Penalty</td>
    <td><input id="ValuePenalty" type="text"  style="text-align: right;"  readonly="readonly" /></td>
  </tr>
  <tr>
    <td>Total Amount</td>
    <td><input id="NetAmount" type="text"  style="text-align: right; background-color:#6FC" readonly="readonly" /></td>
  </tr>
  
  
</table>
</div>
</td>
</tr>
</table>

<!-- -->






</td>
</tr>
<!--======================================== -->
</table>



</fieldset>
<!------------------ EDIT PRICE ----------------->
<div id="EDIT_PRICE_OVERLAY" class="overlay1">
<div>
<a href="#" onClick="EDIT_PRICE_OVERLAY(-1)" id="close" class="close">X</a>
<table width="100%"  border="0" cellspacing="0" cellpadding="2px">
<tr>
<td  valign="top" width="50%">
<fieldset style="color:#000">
<legend>-- EDIT PAYMENT AMOUNTS --</legend>
<table width="100%" border="0" cellspacing="0" cellpadding="2px" class="TFtable w3-tiny" style="text-align:left; font-weight:bold">
  <tr>
    <td  width="55%">Invoice Price:</td>
    <td><input id="M_INV_PRICE" type="text" readonly /></td>
  </tr>
  <tr>
    <td>Quantity:</td>
    <td><input id="M_QTY" type="text" /></td>
  </tr>
  <tr>
    <td>Value Added Tax (%):</td>
    <td><select id="M_SAL_TAX"></select></td>
  </tr>
  <tr>
    <td>Is Recoverable VAT:</td>
    <td><select id="M_R_SAL_TAX_FLAG">
      <option value="0">No</option>
      <option value="1">Yes</option>
    </select></td>
  </tr>
  <tr>
    <td>Other Tax (%):</td>
    <td><select id="M_VAT"></select></td>
  </tr>
  <tr>
    <td>Withholding Tax (%):</td>
    <td><select id="M_WAT"></select></td>
  </tr>
  <tr>
    <td>Default Vendor GL Account:</td>
    <td><select id="M_GL_VENDOR"></select></td>
  </tr>
  <tr>
     <td>Default Vendor GL Cost Center:</td>
    <td><select id="M_GL_CC"></select></td>
  </tr>
  
</table>
</fieldset>
<br />
<fieldset style="color:#000;">
<legend>-- EDIT INVOICE --</legend>
<table width="100%" border="0" cellspacing="0" cellpadding="2px" class="TFtable w3-tiny" style="text-align:left; font-weight:bold">
  <tr>
    <td  width="55%">Number:</td>
    <td><input id="LN_INVOC_NUM" type="text" /></td>
  </tr>
  <tr>
    <td>Date:</td>
    <td><input id="LN_INVOC_D" type="text" placeholder="YYYYMMDD" /></td>
  </tr>
  
  
</table>
</fieldset>
</td>

<td valign="top">
<fieldset style="color:#000;">
<legend>-- EDIT PENALTY --</legend>
<table width="100%" border="0" cellspacing="0" cellpadding="2px" class="TFtable w3-tiny" style="text-align:left; font-weight:bold">
  <tr>
    <td  width="55%">Fixed Amount Penalty:</td>
    <td><input id="M_PENALTY_F" type="text" /></td>
  </tr>
  <tr>
    <td>Penalty (%):</td>
    <td><select id="M_PENALTY_P"></select></td>
  </tr>
  <tr>
    <td>Default Penalty GL Account:</td>
    <td><select id="M_GL_PENALTY"></select></td>
  </tr>
  <tr>
     <td>Default Penalty GL Cost Center:</td>
    <td><select id="M_GL_CC_PENALTY"></select></td>
  </tr>
  
</table>
</fieldset>

<br />
<fieldset style="background-color:#FFF; text-align:left; color:#000">
<legend>-- Vendor Tax Data --</legend>
<div id="VENDOR_TAX_DATA" style="width:250px; color:#003; text-align:left">
</div>
</fieldset>


</td>
</tr>
<tr>
    <td colspan="2" align="center">
    <table border="0" cellspacing="0" cellpadding="2px">
  <tr>
    <td><input id="M_UPDATE" type="button" value="Update" onclick="btnM_UPDATEActionPerformed();" /><input id="M_ROW_INDEX" type="hidden" value="-1" /></td>
    <td><input id="M_UPDATE_ALL" type="button" value="Update All Lines" onclick="btnM_UPDATE_ALLActionPerformed();" /></td>
    <td><input id="M_CANCEL" type="button" value="Cancel"  onclick="btnM_CANCELActionPerformed();" /></td>
  </tr>
</table>

    </td>
    </tr>
</table>







</div>
</div>


<!------------------ SEARCH VENDOR ----------------->
<div id="SEARCH_VENDOR_OVERLAY" class="overlay">
<div  id="find_myItem"   style="width:830px">
<a href="#" onClick="SEARCH_VENDOR_OVERLAY(this.id)" id="close" class="close">X</a>
<fieldset style="color:#000">
<legend>-- Find Vendor --</legend>
<table width="100%" cellspacing="0" cellpadding="0">
<tr>
 <td><input id="search_item" name="search_item" type="text" value="%" style="width:100%"  onKeyPress="javascript:HandleKeyEvent3(event)" onKeyDown="javascript:HandleKeyEvent3(event)" onKeyUp="javascript:HandleKeyEvent3(event)" ></td><td align="left"><input id="Find" name="Find" type="button" value="Find" onClick="javascript:SEARCH_VENDOR_GET_RESULT();"></td>
</tr>
<tr>
<td colspan="2" id="search_vendor_results">



</td>
</tr>
</table>




</fieldset>
</div>
</div>