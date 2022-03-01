<fieldset id="PREAPP" class="tabcontent">
<table width="100%">
<tr>
<td>

<fieldset id="PREAPP_H" style="font-family: verdana,arial,sans-serif;font-size:12px; color:#000;">
<table border="0" cellspacing="0" cellpadding="3px">
<tr>
<td align="right">Prepayment:</td><td width="20%"><select name="prepayvendors" id="prepayvendors" style="width:100%" onchange="javascript:GET_TRANS_APPLICATIONS_LOV(this.options[this.selectedIndex].value);"></select></td>
<td align="right">Apply To:</td><td width="20%"><select name="applytrans" id="applytrans" style="width:100%"></select></td>
<td align="right">Applied Amount:</td><td><input name="appamount" type="text" id="appamount" style="background-color:#FFC; text-align:right; width:150px" /></td>
<td><input id="prepayapply" type="button" value="Apply" onclick="javascript:APPLY_PREPAYMENT();" /></td>
</tr>
</table>
</fieldset>

</td>
</tr>
<!--======================================================= -->
<tr>
<td>


<div style="border-style:solid;border-width:1px; border-color:#999; width:1000px">
<table id="recAPPGrid" class="display cell-border compact TFtable nowrap" style="width:100%"><thead>
<tr>
<th>Prepament</th>
<th>Applied To</th>
<th>Applied Amount</th>
<th>Un-Apply</th>
<th></th>
</tr>

</thead>
<tbody>

</tbody>
</table>
</div>


</td>
</tr>

</table>
</fieldset>