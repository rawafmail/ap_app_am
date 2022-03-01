<fieldset>

<table  border="0" cellspacing="0" cellpadding="0" >
<tr><td width="80px"><input name="btnNew" type="button" id="btnNew"  style="background-image:url(../Lib/images/rpt.gif);background-repeat: no-repeat;background-position: center left 5px ;" onClick="btnNewActionPerformed();"  value="New"></td>
<td width="80px"><input type="button" name="btnSave" id="btnSave" value="Save"   style="background-image:url(../Lib/images/saveHS.png);background-repeat: no-repeat;background-position: center left 5px ;" onclick="btnSaveActionPerformed(event);" ></td>

<td width="105px"><input type="button" name="btnPV" id="btnPV" value="Voucher"    style="background-image:url(../Lib/images/preview.png);background-repeat: no-repeat;background-position: center left 5px ;" onclick="btnPVActionPerformed();" ></td>
<td width="115px"><input type="button" name="btnPDTL" id="btnPDTL" value="Pay. Details"    style="background-image:url(../Lib/images/preview.png);background-repeat: no-repeat;background-position: center left 5px ;" onclick="btnPDTLActionPerformed();" ></td>
<td width="98px"><input type="button" name="btnJE" id="btnJE" value="Journal"    style="background-image:url(../Lib/images/preview.png);background-repeat: no-repeat;background-position: center left 5px ;" onclick="btnJEActionPerformed();" ></td>

<td width="145px"><input type="button" name="btnPost" id="btnPost" value="Transfer to GL"    style="background-image:url(../Lib/images/copy.png);background-repeat: no-repeat;background-position: center left 5px ;"  onclick="btnPostActionPerformed();"></td>
<td width="98px"><input type="button" name="btnReverse" id="btnReverse" value="Reverse"    style="background-image:url(../Lib/images/DeleteFolderHS.png);background-repeat: no-repeat;background-position: center left 5px ;"  onclick="btnReversePerformed();" ></td>

<td width="100px"><input type="button" name="btnAPRV" id="btnAPRV" value="Approve" onclick="APPROVE_PAY();" style="background-image:url(../Lib/images/approv.png);background-repeat: no-repeat;background-position: center left 5px ;" ></td>
<td width="75px"><input type="button" name="btnPAY" id="btnPAY" value="Pay" onclick="PAY_PAY();" style="background-image:url(../Lib/images/Money-icon.png);background-repeat: no-repeat;background-position: center left 5px ;" ></td>
<td width="105px"><input type="button" name="btnPV" id="btnPV" value="Test"    style="background-image:url(../Lib/images/preview.png);background-repeat: no-repeat;background-position: center left 5px ;display:none" onclick="btnPVActionPerformedTest();" >
<input type='hidden' id='test2'>
</td>
            
</tr>
</table>
</fieldset>