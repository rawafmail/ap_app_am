
 <?php 
require('../lib/repheader.php');
//require('../libfn/repheader.php');
//require('../../libfn/repheadernew.php');

 //-- DATABASE CONNECTION 
$CONNECT = odbc_connect('PROD', 'apps', 'apps');
 ?>
 <script>
function submit_data() {
var VIEW = document.getElementById('VIEW').options[document.getElementById('VIEW').selectedIndex].value;
var action ='CHECK_POSTED.php'; 
if (VIEW==1){
	action ='CHECK_POSTED_DETAILS_ALL.php';
}
document.getElementById("form1").action = action;
document.getElementById("form1").submit();
}
</script>

            <tr>
            <td align="center" valign="top" style=" background-color: lavender;font-size:12px; font-weight:bold;  font:Arial, Helvetica, sans-serif" >
			<form id="form1" name=" form1" method="post" action="CHECK_POSTED.php"><br /><fieldset><legend>Checks Delivered Report&nbsp;&nbsp;</legend><br><table border="0">
             
      
      <tr align="left">
         <td><B>COMPANY :</B></td>
         <td>
             <select name='ORG_ID' id='ORG_ID'>
             <option value='91'>Amriya company </option>
             <option value='111'>European company </option>
             <option value='156'>Pharco company </option>
            <option value='656'>Pharco-B company </option>
            <option value='776'>Safe company </option>
            <option value='715'>Pharco-B Chemicals company </option>
            <option value='615'>Techno company </option>
	     </select>
         </td>     
	 </tr>
   <tr align="left">
         <td><b>From Date:</b></td>
         <td>
	       <input name="FROM_DATE" id="FROM_DATE" type="text" size="8" maxlength="8" /> YYYYMMDD
           </td>     
      </tr>
       
      <tr align="left">
         <td><b>From Date:</b></td>
         <td>
	       <input name="TO_DATE" id="TO_DATE" type="text" size="8" maxlength="8" /> YYYYMMDD
           </td>     
      </tr>
      <tr align="left">
         <td><B>View:</B></td>
         <td>
             <select name='VIEW' id='VIEW'>
             <option value='0'>Summary </option>
               <option value='1'>Details </option>
              
	     </select>
         </td>     
	 </tr>
       <tr align="center">
           <td colspan="2"><input  type="submit" name="Submit" value="Run Report"  style="background-color:#ADCAD3 ; font-family:Arial, Helvetica, sans-serif;  font-size:12px;" onClick="submit_data();"></td>
       </tr>
       <tr align="center">
        <td ><input id='uid' name='uid' type='hidden' value="<?php echo $_GET['uid']?>"  /></td>
        <td ><input id='sid' name='sid' type='hidden'  value="<?php echo $_GET['sid']?>"/></td>
      </tr>
      <tr> 
  </table>                                
  </fieldset></form></td>
   </tr>
   <script>



</script>
  <?php require('../libfn/repfooter.php'); ?>	