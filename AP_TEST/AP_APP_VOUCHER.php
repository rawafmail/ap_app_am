
<fieldset id="PV" class="tabcontent">
<fieldset style="font-family: verdana,arial,sans-serif;font-size:12px; color:#000;">

  <table border="0" cellspacing="0" cellpadding="5px">
   <tr>
   <td align="right">Non-Endorsement:</td>
    <td><select id="nonE">
      <option value="0">No</option>
      <option value="1">Yes</option>
    </select></td>
    <td>&nbsp;</td>
   </tr>
  <tr>
    <td align="right">Number:</td>
    <td><input name="txtChequeNum" id="txtChequeNum" type="text"  /></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="right">Maturity Date:</td>
    <td><input name="txtChequeDate" id="txtChequeDate" type="text" size="8" maxlength="8" placeholder="YYYYMMDD" /> </td>
    <td><input name="btnUCHQ"  id="btnUCHQ" type="button" value="Update Cheque Data" onclick="UPDATE_CHEQUE_DATA();" /></td>
  </tr>
  <tr>
    <td align="right">Signature[1]:</td>
    <td><select name="cboAppv1" id="cboAppv1"></select></td>
    <td><input name="btnPCHQ" id="btnPCHQ" type="button" value="Print Cheque" onclick="btnPCHQActionPerformed();" /></td>
  </tr>
  <tr>
    <td align="right">Signature[2]:</td>
    <td><select name="cboAppv2" id="cboAppv2"></select></td>
    <td><input type="button" name="pro_chq" id="pro_chq" value="Submit Cheque" onclick="PROCESS_CHEQUE();" /></td>
  </tr>
 
</table>
</fieldset>

</fieldset>

