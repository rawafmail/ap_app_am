<script>
<!--------------------------------------------------------------------- -->

$(document).ready(function() {
    $('#recRCTGrid').DataTable( {
		
		"columnDefs": [ 
 { "targets": [ 24],"visible": false}] ,
 /*,
 { "targets": [ 24],"visible": false},
 { "targets": [ 25],"visible": false},
 { "targets": [ 26],"visible": false},
 { "targets": [ 27],"visible": false},
 { "targets": [ 28],"visible": false},
 { "targets": [ 29],"visible": false},
 { "targets": [ 30],"visible": false},
 { "targets": [ 31],"visible": false},
 { "targets": [ 32],"visible": false},
 { "targets": [ 33],"visible": false},
 { "targets": [ 34],"visible": false},
 { "targets": [ 35],"visible": false}

*/
		"paging":   false,
        "ordering": false,
        "info":     false,
		keys: true,
		"searching": false,
        "scrollY": 200,
        "scrollX": true
    } );
$('#recRCTGrid').on('key-focus', function (e, datatable, cell) {
    var inputFieldInSelectedTd = $(cell.node()).find('input');
    if (inputFieldInSelectedTd) {
      inputFieldInSelectedTd.focus();	  
   }
});	
	
} );
<!--------------------------------------------------------------------- -->
function btnM_UPDATE_ALLActionPerformed(){
	var matchtype = document.getElementById('matchtype').options[document.getElementById('matchtype').selectedIndex].value;
	var M_INV_PRICE = document.getElementById('M_INV_PRICE').value;
	var M_QTY = document.getElementById('M_QTY').value;
	var M_SAL_TAX = document.getElementById('M_SAL_TAX').options[document.getElementById('M_SAL_TAX').selectedIndex].value;
	var M_R_SAL_TAX_FLAG = document.getElementById('M_R_SAL_TAX_FLAG').options[document.getElementById('M_R_SAL_TAX_FLAG').selectedIndex].value;
	var M_VAT = document.getElementById('M_VAT').options[document.getElementById('M_VAT').selectedIndex].value;
	var M_WAT = document.getElementById('M_WAT').options[document.getElementById('M_WAT').selectedIndex].value;
	var M_GL_VENDOR = document.getElementById('M_GL_VENDOR').options[document.getElementById('M_GL_VENDOR').selectedIndex].value;
	var M_GL_CC = document.getElementById('M_GL_CC').options[document.getElementById('M_GL_CC').selectedIndex].value;
	var M_PENALTY_F = document.getElementById('M_PENALTY_F').value;
	var M_PENALTY_P_IDX = document.getElementById('M_PENALTY_P').selectedIndex;
	var M_PENALTY_P = document.getElementById('M_PENALTY_P').options[document.getElementById('M_PENALTY_P').selectedIndex].value;
	var M_GL_PENALTY = document.getElementById('M_GL_PENALTY').options[document.getElementById('M_GL_PENALTY').selectedIndex].value;
	var M_GL_CC_PENALTY = document.getElementById('M_GL_CC_PENALTY').options[document.getElementById('M_GL_CC_PENALTY').selectedIndex].value;
	var NET_AMOUNT = 0;
	var INV_AMOUNT = 0;
	var SAL_TAX_AMOUNT =0;
	var VAT_AMOUNT =0;
	var WAT_AMOUNT =0;
	var PENALTY_AMOUNT = 0;
	
    if (!VALIDATE_CROSSV_RULE(M_GL_VENDOR,M_GL_CC)){
		return false;
	}
	
	if (!VALIDATE_CROSSV_RULE(M_GL_PENALTY,M_GL_CC_PENALTY)){
		return false;
	}
	
	if (isNaN(M_INV_PRICE)||(M_INV_PRICE.trim()=='')||(eval(M_INV_PRICE)<0)) {
	  alert('It is not a valid invoice price. \n\n Please correct your entry and try again....'); 
	  document.getElementById('M_INV_PRICE').focus();
	  document.getElementById('M_INV_PRICE').select();
	  return false;
	}
		
	if (isNaN(M_QTY)||(M_QTY.trim()=='')||(eval(M_QTY)<0)) {
	  alert('It is not a valid quantity. \n\n Please correct your entry and try again....'); 
	  document.getElementById('M_QTY').focus();
	  document.getElementById('M_QTY').select();
	  return false;
	}
	
	var M_ROW_INDEX = document.getElementById("M_ROW_INDEX");
	var table = document.getElementById("recRCTGrid");
	var rowCount = table.rows.length;
	if (rowCount >1){
	for(var j=1; j<rowCount; j++) {
		var NEW_M_QTY =M_QTY;
		var row = table.rows[j];
		
		//
		var LN_INVOC_NUM = document.getElementById('LN_INVOC_NUM').value;
		var LN_INVOC_D = document.getElementById('LN_INVOC_D').value;
		if (!ValidateInvoiceData(LN_INVOC_D,LN_INVOC_NUM)){
			return false;	
		}
		
		 var colINVOC_NUM=row.cells[0];
		 for (var i=0; i<colINVOC_NUM.childNodes.length; i++){
			 if (colINVOC_NUM.childNodes[i].id=='MATCH_INV_NUM'){
				row.cells[0].childNodes[i].value=document.getElementById("LN_INVOC_NUM").value ;
				break;
			 }
		 }
		row.cells[1].childNodes[0].value=LN_INVOC_D;
		//
		
		if (eval(matchtype)!=4){
		//row.cells[4].childNodes[0].value = M_INV_PRICE;
		}
		//row.cells[5].childNodes[0].value = M_INV_PRICE;
		//row.cells[7].childNodes[0].value = eval(eval(M_INV_PRICE)-eval(row.cells[6].childNodes[0].value)).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 6});	
		//if (eval(NEW_M_QTY)>eval(row.cells[2].childNodes[0].value)){NEW_M_QTY=row.cells[2].childNodes[0].value;}
		//row.cells[2].childNodes[0].value = NEW_M_QTY;
		row.cells[9].childNodes[0].value = M_SAL_TAX;
		row.cells[10].childNodes[0].value = M_R_SAL_TAX_FLAG;
		row.cells[12].childNodes[0].value = M_VAT;
		row.cells[14].childNodes[0].value = M_WAT;
		var colHidden=row.cells[23];
		for (var i=0; i<colHidden.childNodes.length; i++){
			 if (colHidden.childNodes[i].id=='GLACCOUNT'){
				colHidden.childNodes[i].value = M_GL_VENDOR;
			 }
			 if (colHidden.childNodes[i].id=='COSTCENTER'){
				colHidden.childNodes[i].value = M_GL_CC;
			 }
			 if (colHidden.childNodes[i].id=='PENALTY_GLACCOUNT'){
				colHidden.childNodes[i].value = M_GL_PENALTY;
			 }
			 if (colHidden.childNodes[i].id=='PENALTY_GLCOSTCENTER'){
				colHidden.childNodes[i].value = M_GL_CC_PENALTY;
			 }
		}
		

		
		
		//INV_AMOUNT = eval(M_QTY) * eval(M_INV_PRICE);
		INV_AMOUNT = eval(row.cells[2].childNodes[0].value) * eval(row.cells[4].childNodes[0].value);
		if ((eval(matchtype)!=4)||(eval(row.cells[4].childNodes[0].value)==0)){
		INV_AMOUNT = eval(row.cells[2].childNodes[0].value) * eval(row.cells[5].childNodes[0].value);
		}
		SAL_TAX_AMOUNT = eval(INV_AMOUNT) * (eval(M_SAL_TAX)/eval(100));		
		VAT_AMOUNT = eval(INV_AMOUNT) * (eval(M_VAT)/eval(100));
		WAT_AMOUNT = eval(INV_AMOUNT) * (eval(M_WAT)/eval(100));
		row.cells[8].childNodes[0].value = eval(INV_AMOUNT).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
		row.cells[11].childNodes[0].value = eval(SAL_TAX_AMOUNT).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});		
		row.cells[13].childNodes[0].value = eval(VAT_AMOUNT).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
		row.cells[15].childNodes[0].value = eval(WAT_AMOUNT).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
		
		if (!isNaN(M_PENALTY_F)&&(M_PENALTY_F.trim()!='')&&(eval(M_PENALTY_F)>1)) {
			row.cells[16].childNodes[0].value = 0;
			PENALTY_AMOUNT = M_PENALTY_F;
			row.cells[17].childNodes[0].value = eval(M_PENALTY_F).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});	
		}else{
			if (!isNaN(M_PENALTY_P)&&(M_PENALTY_P_IDX!=-1)){
				row.cells[16].childNodes[0].value = M_PENALTY_P;
				PENALTY_AMOUNT = eval(INV_AMOUNT) * (eval(M_PENALTY_P)/eval(100));
				row.cells[17].childNodes[0].value = eval(PENALTY_AMOUNT).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
			}
		}
		NET_AMOUNT = eval(INV_AMOUNT)+eval(SAL_TAX_AMOUNT)+eval(VAT_AMOUNT)-eval(WAT_AMOUNT)-eval(PENALTY_AMOUNT);
		row.cells[18].childNodes[0].value = eval(NET_AMOUNT).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
	}
	}
	
	//
	UpdateSummaryGrid();
	//
	M_ROW_INDEX.value=-1; 
	var el = document.getElementById("EDIT_PRICE_OVERLAY");
    el.style.visibility = (el.style.visibility == "visible") ? "hidden" : "visible";
}
<!------------------------------------------------------------------------------------------------> 
function btnM_UPDATEActionPerformed(){
	var matchtype = document.getElementById('matchtype').options[document.getElementById('matchtype').selectedIndex].value;
	var M_INV_PRICE = document.getElementById('M_INV_PRICE').value;
	var M_QTY = document.getElementById('M_QTY').value;
	var M_SAL_TAX = document.getElementById('M_SAL_TAX').options[document.getElementById('M_SAL_TAX').selectedIndex].value;
	var M_R_SAL_TAX_FLAG = document.getElementById('M_R_SAL_TAX_FLAG').options[document.getElementById('M_R_SAL_TAX_FLAG').selectedIndex].value;
	var M_VAT = document.getElementById('M_VAT').options[document.getElementById('M_VAT').selectedIndex].value;
	var M_WAT = document.getElementById('M_WAT').options[document.getElementById('M_WAT').selectedIndex].value;
	var M_GL_VENDOR = document.getElementById('M_GL_VENDOR').options[document.getElementById('M_GL_VENDOR').selectedIndex].value;
	var M_GL_CC = document.getElementById('M_GL_CC').options[document.getElementById('M_GL_CC').selectedIndex].value;
	var M_PENALTY_F = document.getElementById('M_PENALTY_F').value;
	var M_PENALTY_P_IDX = document.getElementById('M_PENALTY_P').selectedIndex;
	var M_PENALTY_P = document.getElementById('M_PENALTY_P').options[document.getElementById('M_PENALTY_P').selectedIndex].value;
	var M_GL_PENALTY = document.getElementById('M_GL_PENALTY').options[document.getElementById('M_GL_PENALTY').selectedIndex].value;
	var M_GL_CC_PENALTY = document.getElementById('M_GL_CC_PENALTY').options[document.getElementById('M_GL_CC_PENALTY').selectedIndex].value;
	var NET_AMOUNT = 0;
	var INV_AMOUNT = 0;
	var SAL_TAX_AMOUNT =0;
	var VAT_AMOUNT =0;
	var WAT_AMOUNT =0;
	var PENALTY_AMOUNT = 0;
	
    if (!VALIDATE_CROSSV_RULE(M_GL_VENDOR,M_GL_CC)){
		return false;
	}
	
	if (!VALIDATE_CROSSV_RULE(M_GL_PENALTY,M_GL_CC_PENALTY)){
		return false;
	}
	
	if (isNaN(M_INV_PRICE)||(M_INV_PRICE.trim()=='')||(eval(M_INV_PRICE)<0)) {
	  alert('It is not a valid invoice price. \n\n Please correct your entry and try again....'); 
	  document.getElementById('M_INV_PRICE').focus();
	  document.getElementById('M_INV_PRICE').select();
	  return false;
	}
		
	if (isNaN(M_QTY)||(M_QTY.trim()=='')||(eval(M_QTY)<0)) {
	  alert('It is not a valid quantity. \n\n Please correct your entry and try again....'); 
	  document.getElementById('M_QTY').focus();
	  document.getElementById('M_QTY').select();
	  return false;
	}
	
	var M_ROW_INDEX = document.getElementById("M_ROW_INDEX");
	var table = document.getElementById("recRCTGrid");
 	var rowCount = table.rows.length;
	
	if (M_ROW_INDEX.value >=1){
		var row = table.rows[M_ROW_INDEX.value];
		if (eval(matchtype)!=4){
		row.cells[4].childNodes[0].value = M_INV_PRICE;
		}
		row.cells[5].childNodes[0].value = M_INV_PRICE;
		row.cells[7].childNodes[0].value = eval(eval(M_INV_PRICE)-eval(row.cells[6].childNodes[0].value)).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 6});	
		if (eval(M_QTY)>eval(row.cells[2].childNodes[0].value)){M_QTY=row.cells[2].childNodes[0].value;}
		row.cells[2].childNodes[0].value = M_QTY;
		row.cells[9].childNodes[0].value = M_SAL_TAX;
		row.cells[10].childNodes[0].value = M_R_SAL_TAX_FLAG;
		row.cells[12].childNodes[0].value = M_VAT;
		row.cells[14].childNodes[0].value = M_WAT;
		var colHidden=row.cells[23];
		for (var i=0; i<colHidden.childNodes.length; i++){
			 if (colHidden.childNodes[i].id=='GLACCOUNT'){
				colHidden.childNodes[i].value = M_GL_VENDOR;
			 }
			 if (colHidden.childNodes[i].id=='COSTCENTER'){
				colHidden.childNodes[i].value = M_GL_CC;
			 }
			 if (colHidden.childNodes[i].id=='PENALTY_GLACCOUNT'){
				colHidden.childNodes[i].value = M_GL_PENALTY;
			 }
			 if (colHidden.childNodes[i].id=='PENALTY_GLCOSTCENTER'){
				colHidden.childNodes[i].value = M_GL_CC_PENALTY;
			 }
		}
		

		
		
		INV_AMOUNT = eval(M_QTY) * eval(M_INV_PRICE);
		SAL_TAX_AMOUNT = eval(INV_AMOUNT) * (eval(M_SAL_TAX)/eval(100));		
		VAT_AMOUNT = eval(INV_AMOUNT) * (eval(M_VAT)/eval(100));
		WAT_AMOUNT = eval(INV_AMOUNT) * (eval(M_WAT)/eval(100));
		row.cells[8].childNodes[0].value = eval(INV_AMOUNT).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
		row.cells[11].childNodes[0].value = eval(SAL_TAX_AMOUNT).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});		
		row.cells[13].childNodes[0].value = eval(VAT_AMOUNT).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
		row.cells[15].childNodes[0].value = eval(WAT_AMOUNT).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
		
		if (!isNaN(M_PENALTY_F)&&(M_PENALTY_F.trim()!='')&&(eval(M_PENALTY_F)>1)) {
			row.cells[16].childNodes[0].value = 0;
			PENALTY_AMOUNT = M_PENALTY_F;
			row.cells[17].childNodes[0].value = eval(M_PENALTY_F).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});	
		}else{
			if (!isNaN(M_PENALTY_P)&&(M_PENALTY_P_IDX!=-1)){
				row.cells[16].childNodes[0].value = M_PENALTY_P;
				PENALTY_AMOUNT = eval(INV_AMOUNT) * (eval(M_PENALTY_P)/eval(100));
				row.cells[17].childNodes[0].value = eval(PENALTY_AMOUNT).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
			}
		}
 	}
	
	NET_AMOUNT = eval(INV_AMOUNT)+eval(SAL_TAX_AMOUNT)+eval(VAT_AMOUNT)-eval(WAT_AMOUNT)-eval(PENALTY_AMOUNT);
	row.cells[18].childNodes[0].value = eval(NET_AMOUNT).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
	//
	UpdateSummaryGrid();
	//
	var LN_INVOC_NUM = document.getElementById('LN_INVOC_NUM').value;
	var LN_INVOC_D = document.getElementById('LN_INVOC_D').value;
	if (!ValidateInvoiceData(LN_INVOC_D,LN_INVOC_NUM)){
		return false;	
	}
	
	 var colINVOC_NUM=row.cells[0];
	 for (var i=0; i<colINVOC_NUM.childNodes.length; i++){
		 if (colINVOC_NUM.childNodes[i].id=='MATCH_INV_NUM'){
			row.cells[0].childNodes[i].value=document.getElementById("LN_INVOC_NUM").value ;
			break;
		 }
	 }
	row.cells[1].childNodes[0].value=LN_INVOC_D;
	//
	M_ROW_INDEX.value=-1; 
	var el = document.getElementById("EDIT_PRICE_OVERLAY");
    el.style.visibility = (el.style.visibility == "visible") ? "hidden" : "visible";
}
<!------------------------------------------------------------------------------------------------> 

function btnM_CANCELActionPerformed(){
	M_ROW_INDEX.value=-1; 
	var el = document.getElementById("EDIT_PRICE_OVERLAY");
    el.style.visibility = (el.style.visibility == "visible") ? "hidden" : "visible";
}
<!------------------------------------------------------------------------------------------------> 
function BuildMSQL(){
	var BuilderMSQL ="";
	var DGtable = $('#recRCTGrid').DataTable();
	if ( ! DGtable.data().any() ) {return;}
	var table = document.getElementById("recRCTGrid");
	var rowCount = table.rows.length;	
	for(var i=1; i<rowCount; i++) {
			x = i;
			var ORG_ID = "<?php print $ORG_ID; ?>";
			var PO_HEADER_ID = 0;
			var PO_LINE_ID = 0;
			var TRANSACTION_ID = 0;
			var ITEM_ID = 0;
			var INV_PERIOD = 0;
			var VENDOR_CODE = '';
			var MATCH_TYPE_ID = 0;
			var GLACCOUNT = '';
			var COSTCENTER = '';
			var PENALTY_GLACCOUNT = '';
			var PENALTY_GLCOSTCENTER = '';
			var PAYMENT_LINE_ID =0;
			var PAYMENT_SCHEDULE_ID =0;

			var row = table.rows[i];

			var  MATCH_INV_NUM_V ='';
			var  MATCH_INV_NUM =row.cells[0];			
			for (var j=0; j<MATCH_INV_NUM.childNodes.length; j++){
				if (MATCH_INV_NUM.childNodes[j].id=='MATCH_INV_NUM'){MATCH_INV_NUM_V=MATCH_INV_NUM.childNodes[j].value;}
			}
			
			var colHidden=row.cells[23];
			
			for (var j=0; j<colHidden.childNodes.length; j++){
				if (colHidden.childNodes[j].id=='PO_HEADER_ID'){PO_HEADER_ID=colHidden.childNodes[j].value;}
				if (colHidden.childNodes[j].id=='PO_LINE_ID'){PO_LINE_ID=colHidden.childNodes[j].value;}
				if (colHidden.childNodes[j].id=='TRANSACTION_ID'){TRANSACTION_ID=colHidden.childNodes[j].value;}
				if (colHidden.childNodes[j].id=='ITEM_ID'){ITEM_ID=colHidden.childNodes[j].value;}
				if (colHidden.childNodes[j].id=='INV_PERIOD'){INV_PERIOD=colHidden.childNodes[j].value;}
				if (colHidden.childNodes[j].id=='VENDOR_CODE'){VENDOR_CODE=colHidden.childNodes[j].value;}
				if (colHidden.childNodes[j].id=='MATCH_TYPE_ID'){MATCH_TYPE_ID=colHidden.childNodes[j].value;}
				if (colHidden.childNodes[j].id=='GLACCOUNT'){GLACCOUNT=colHidden.childNodes[j].value;}
				if (colHidden.childNodes[j].id=='COSTCENTER'){COSTCENTER=colHidden.childNodes[j].value;}
				if (colHidden.childNodes[j].id=='PENALTY_GLACCOUNT'){PENALTY_GLACCOUNT=colHidden.childNodes[j].value;}
				if (colHidden.childNodes[j].id=='PENALTY_GLCOSTCENTER'){PENALTY_GLCOSTCENTER=colHidden.childNodes[j].value;}
				if (colHidden.childNodes[j].id=='PAYMENT_LINE_ID'){PAYMENT_LINE_ID=colHidden.childNodes[j].value;}
				if (colHidden.childNodes[j].id=='PAYMENT_SCHEDULE_ID'){PAYMENT_SCHEDULE_ID=colHidden.childNodes[j].value;}
			}
			
			BuilderMSQL = BuilderMSQL + " SELECT ";
			BuilderMSQL = BuilderMSQL + PAYMENT_ID +",";
			BuilderMSQL = BuilderMSQL + "'"+MATCH_INV_NUM_V+"',";
			BuilderMSQL = BuilderMSQL + "TO_DATE('" + row.cells[1].childNodes[0].value + "','YYYYMMDD'),";
			BuilderMSQL = BuilderMSQL + row.cells[2].childNodes[0].value.replace(/,/gi, '') + ",";
			BuilderMSQL = BuilderMSQL + "'"+row.cells[3].childNodes[0].value+"',";
			BuilderMSQL = BuilderMSQL + row.cells[4].childNodes[0].value.replace(/,/gi, '') + ",";
			BuilderMSQL = BuilderMSQL + row.cells[5].childNodes[0].value.replace(/,/gi, '') + ",";
			BuilderMSQL = BuilderMSQL + row.cells[6].childNodes[0].value.replace(/,/gi, '') + ",";
			BuilderMSQL = BuilderMSQL + row.cells[7].childNodes[0].value.replace(/,/gi, '') + ",";
			BuilderMSQL = BuilderMSQL + row.cells[8].childNodes[0].value.replace(/,/gi, '') + ",";
			BuilderMSQL = BuilderMSQL + row.cells[9].childNodes[0].value + ",";
			BuilderMSQL = BuilderMSQL + row.cells[10].childNodes[0].value + ",";
			BuilderMSQL = BuilderMSQL + row.cells[11].childNodes[0].value.replace(/,/gi, '') + ",";
			BuilderMSQL = BuilderMSQL + row.cells[12].childNodes[0].value + ",";
			BuilderMSQL = BuilderMSQL + row.cells[13].childNodes[0].value.replace(/,/gi, '') + ",";
			BuilderMSQL = BuilderMSQL + row.cells[14].childNodes[0].value + ",";
			BuilderMSQL = BuilderMSQL + row.cells[15].childNodes[0].value.replace(/,/gi, '') + ",";
			BuilderMSQL = BuilderMSQL + row.cells[16].childNodes[0].value + ",";
			BuilderMSQL = BuilderMSQL + row.cells[17].childNodes[0].value.replace(/,/gi, '') + ",";
			BuilderMSQL = BuilderMSQL + row.cells[18].childNodes[0].value.replace(/,/gi, '') + ",";
			BuilderMSQL = BuilderMSQL + "'"+row.cells[19].childNodes[0].value+"',";
			BuilderMSQL = BuilderMSQL + "'"+row.cells[20].childNodes[0].value+"',";
			BuilderMSQL = BuilderMSQL + ORG_ID+ ",";
			BuilderMSQL = BuilderMSQL + PO_HEADER_ID+ ",";
			BuilderMSQL = BuilderMSQL + PO_LINE_ID+ ",";
			BuilderMSQL = BuilderMSQL + TRANSACTION_ID+ ",";
			BuilderMSQL = BuilderMSQL + ITEM_ID+ ",";
			BuilderMSQL = BuilderMSQL + "'"+INV_PERIOD+"',";
			BuilderMSQL = BuilderMSQL + "'"+VENDOR_CODE+"',";
			BuilderMSQL = BuilderMSQL + MATCH_TYPE_ID+ ",";
			BuilderMSQL = BuilderMSQL + "'"+GLACCOUNT+"',";
			BuilderMSQL = BuilderMSQL + "'"+COSTCENTER+"',";
			BuilderMSQL = BuilderMSQL + "'"+PENALTY_GLACCOUNT+"',";
			BuilderMSQL = BuilderMSQL + "'"+PENALTY_GLCOSTCENTER+"',";
			BuilderMSQL = BuilderMSQL + PAYMENT_LINE_ID+",";
			BuilderMSQL = BuilderMSQL + PAYMENT_SCHEDULE_ID;
			BuilderMSQL = BuilderMSQL + " FROM DUAL ";
		    x = " UNION ALL ";
			if ((i+1)==rowCount){x="";}
			
			if (i==1){
			BuilderMSQL = " SELECT ";
			BuilderMSQL = BuilderMSQL + PAYMENT_ID +" AS PAYMENT_ID,";
			BuilderMSQL = BuilderMSQL + "'"+MATCH_INV_NUM_V+"' AS  INV_NUM,";
			BuilderMSQL = BuilderMSQL + "TO_DATE('" + row.cells[1].childNodes[0].value + "','YYYYMMDD') AS  INV_DATE,";
			BuilderMSQL = BuilderMSQL + row.cells[2].childNodes[0].value.replace(/,/gi, '') + "  AS  QTY,";
			BuilderMSQL = BuilderMSQL + "'"+row.cells[3].childNodes[0].value+"' AS  UOM,";
			BuilderMSQL = BuilderMSQL + row.cells[4].childNodes[0].value.replace(/,/gi, '') + "  AS  UNIT_COST,";
			BuilderMSQL = BuilderMSQL + row.cells[5].childNodes[0].value.replace(/,/gi, '') + "  AS  INV_PRICE,";
			BuilderMSQL = BuilderMSQL + row.cells[6].childNodes[0].value.replace(/,/gi, '') + "  AS  PO_PRICE,";
			BuilderMSQL = BuilderMSQL + row.cells[7].childNodes[0].value.replace(/,/gi, '') + "  AS  PRICE_VAR,";
			BuilderMSQL = BuilderMSQL + row.cells[8].childNodes[0].value.replace(/,/gi, '') + "  AS  INV_VALUE,";
			BuilderMSQL = BuilderMSQL + row.cells[9].childNodes[0].value + "  AS  SAL_TAX_P,";
			BuilderMSQL = BuilderMSQL + row.cells[10].childNodes[0].value + "  AS  SAL_TAX_RF,";
			BuilderMSQL = BuilderMSQL + row.cells[11].childNodes[0].value.replace(/,/gi, '') + "  AS  SAL_TAX_VAL,";
			BuilderMSQL = BuilderMSQL + row.cells[12].childNodes[0].value + "  AS  VAT_P,";
			BuilderMSQL = BuilderMSQL + row.cells[13].childNodes[0].value.replace(/,/gi, '') + "  AS  VAT_VAL,";
			BuilderMSQL = BuilderMSQL + row.cells[14].childNodes[0].value + " AS  AWT_P,";
			BuilderMSQL = BuilderMSQL + row.cells[15].childNodes[0].value.replace(/,/gi, '') + " AS  AWT_VAL,";
			BuilderMSQL = BuilderMSQL + row.cells[16].childNodes[0].value + " AS  PEN_P,";
			BuilderMSQL = BuilderMSQL + row.cells[17].childNodes[0].value.replace(/,/gi, '') + " AS  PEN_VAL,";
			BuilderMSQL = BuilderMSQL + row.cells[18].childNodes[0].value.replace(/,/gi, '') + " AS  NET_AMOUNT,";
			BuilderMSQL = BuilderMSQL + "'"+row.cells[19].childNodes[0].value+"' AS  RCV_NUM,";
			BuilderMSQL = BuilderMSQL + "'"+row.cells[20].childNodes[0].value+"' AS  RCV_NUM_M,";
			BuilderMSQL = BuilderMSQL + ORG_ID+ " AS ORG_ID,";
			BuilderMSQL = BuilderMSQL + PO_HEADER_ID+ " AS PO_HEADER_ID,";
			BuilderMSQL = BuilderMSQL + PO_LINE_ID+ " AS PO_LINE_ID,";
			BuilderMSQL = BuilderMSQL + TRANSACTION_ID+ " AS TRANSACTION_ID,";
			BuilderMSQL = BuilderMSQL + ITEM_ID+ " AS ITEM_ID,";
			BuilderMSQL = BuilderMSQL + "'"+INV_PERIOD+"' AS INV_PERIOD,";
			BuilderMSQL = BuilderMSQL + "'"+VENDOR_CODE+"' AS VENDOR_CODE,";
			BuilderMSQL = BuilderMSQL + MATCH_TYPE_ID+ " AS MATCH_TYPE_ID,";
			BuilderMSQL = BuilderMSQL + "'"+GLACCOUNT+"' AS GLACCOUNT,";
			BuilderMSQL = BuilderMSQL + "'"+COSTCENTER+"' AS COSTCENTER,";
			BuilderMSQL = BuilderMSQL + "'"+PENALTY_GLACCOUNT+"' AS PENALTY_GLACCOUNT,";
			BuilderMSQL = BuilderMSQL + "'"+PENALTY_GLCOSTCENTER+"' AS PENALTY_GLCOSTCENTER,";
			BuilderMSQL = BuilderMSQL + PAYMENT_LINE_ID+ " AS PAYMENT_LINE_ID,";
			BuilderMSQL = BuilderMSQL + PAYMENT_SCHEDULE_ID+ " AS PAYMENT_SCHEDULE_ID";
			BuilderMSQL = BuilderMSQL +" FROM DUAL";
			}
			
			BuilderMSQL = BuilderMSQL +x;
	}
	return BuilderMSQL;
}
<!------------------------------------------------------------------------------------------------> 
function GET_MATCH_PO_DTL() {
	
	
	//ClearPaymentsDetailsControls();
	//
	var uid =  "<?php print $uid ; ?>";
	var ORG_ID =  "<?php print $ORG_ID; ?>";
	var ORGANIZATION_ID =  "<?php print $ORGANIZATION_ID; ?>";
	
	
	var matchtype = document.getElementById('matchtype').options[document.getElementById('matchtype').selectedIndex].value;
	if ((document.getElementById('ponum_1').selectedIndex==0)&&(eval(matchtype)==1)&&(document.getElementById('PO_NUM_TXT').value.trim()=='')){return;}
    if ((document.getElementById('rcv_num').selectedIndex==0)&&(eval(matchtype)>1)&&(document.getElementById('rcv_num1').value.trim()=='')){return;}

	var rcv_num=document.getElementById('rcv_num').selectedIndex;
	if (rcv_num !=-1){
	rcv_num= document.getElementById('rcv_num').options[document.getElementById('rcv_num').selectedIndex].value;
	}
	
	if (document.getElementById('rcv_num1').value.trim()!=''){rcv_num=document.getElementById('rcv_num1').value;}
	var invperiod= document.getElementById('invperiod').options[document.getElementById('invperiod').selectedIndex].value;
	var store= document.getElementById('store').options[document.getElementById('store').selectedIndex].value;
	var po_id= document.getElementById('ponum_1').options[document.getElementById('ponum_1').selectedIndex].value;
	var po_num_txt ='0';
	if (document.getElementById('PO_NUM_TXT').value.trim()!=''){
		po_num_txt=document.getElementById('PO_NUM_TXT').value.trim();
	}
	
	var invcnum  = document.getElementById('invcnum').value;
	var txtInvDate  = document.getElementById('txtInvDate').value;
	var vendor_id  = document.getElementById('vendorid_1').value;
	var header_ven_emp_number  = document.getElementById('ven_emp_text').value;
	
	if (!ValidateInvoiceData(txtInvDate,invcnum)){
		document.getElementById('ponum_1').selectedIndex=0;
		document.getElementById('rcv_num').selectedIndex=-1;
		document.getElementById('rcv_num1').value='';
		return;	
	}
	
	 // Create our XMLHttpRequest object
    var hr = new XMLHttpRequest();

	
    // Create some variables we need to send to our PHP file
    var url = "AP_APP_MATCH_DB.php";
	var vars = "po_num_txt="+po_num_txt+"&po_id="+po_id+"&matchtype="+matchtype+"&rcv_num="+rcv_num+"&invperiod="+invperiod+"&store="+store+"&invcnum="+invcnum+"&txtInvDate="+txtInvDate+"&ORGANIZATION_ID="+ORGANIZATION_ID+"&ORG_ID="+ORG_ID+"&vendor_id="+vendor_id+"&uid="+uid+"&header_ven_emp_number="+header_ven_emp_number+"&action=GET_MATCH_DTL";
	
    hr.open("POST", url, true);
  
	hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded; charset=utf-8");
	hr.setRequestHeader("Content-length", vars.length);
	hr.setRequestHeader("Connection", "close");
    // Access the onreadystatechange event for the XMLHttpRequest object
    hr.onreadystatechange = function() {
	    if(hr.readyState == 4 && hr.status == 200) {
			
			var return_data = hr.responseText;
			console.log('Check:'+return_data);
			var datarows = return_data.split("</tr>");
			document.getElementById("status").innerHTML = '';
			document.getElementById('PO_NUM_TXT').value='';
			
			  var datarows_count = datarows.length-1;
			  var rowexist =0;
			  for(var i = 0 ; i < datarows_count; i++) {
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
			//alert(datacols[25]);
			//CHECK VENDOR STATUS
			check_flag=datacols[25];
			if(datacols[25]=='N'){ alert("Vendor Disabled Please Check Vendor Data"); return 0 ;}
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
							/*,
							datacols[25],
							datacols[26],
							datacols[27],
							datacols[28],
							datacols[29],
							datacols[30],
							datacols[31],
							datacols[32],
							datacols[33],
							datacols[34],
							datacols[35]
							*/
							 ] ).draw( false );

   			  }
			  
			  if (rowexist >0){alert('Found: '+rowexist+' rows already exists');}
       //If table is in the tab, you need to adjust headers when tab becomes visible
			  $($.fn.dataTable.tables(true)).DataTable()
			  .columns.adjust();
			  UpdateSummaryGrid();
			  //Clear("PM1");
			  Clear("PM2");
			  Clear("PM3");			  			  
	    }
	}
	
    // Send the data to PHP now... and wait for response
    hr.send(vars); // Actually execute the request
	document.getElementById("status").innerHTML = '<div class="alert-box warning"><span>Processing: </span>please wait .....</div>';					
}

<!------------------------------------------------------------------------------------------------> 
function UpdateSummaryGrid() {
 var RecoverableSalesTax = 0;
 var WithholdingTax = 0;
 var ValueAddedTax = 0;
 var ValuePenalty = 0;
 var NetAmount = 0;
 var TotAmt =0;
 var TotAmtRaw =0;
 var SubAmt = 0;
 var SalesTax = 0; 
 var NET_AMOUNT =0;
 var table = document.getElementById("recRCTGrid");
 var rowCount = table.rows.length;
 
 document.getElementById("TotAmt").value=eval(TotAmt).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
 document.getElementById("SubAmt").value=eval(SubAmt).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
 document.getElementById("RecoverableSalesTax").value=eval(RecoverableSalesTax).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
 document.getElementById("WithholdingTax").value=eval(WithholdingTax).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
 document.getElementById("ValuePenalty").value=eval(ValuePenalty).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
 document.getElementById("ValueAddedTax").value=eval(ValueAddedTax).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}); 
 document.getElementById("SalesTax").value=eval(SalesTax).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}); 
 document.getElementById("NetAmount").value=eval(NetAmount).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
 document.getElementById("txtAmount").value=eval(NetAmount).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});

 
 for(var i=1; i<rowCount; i++) {
	var row = table.rows[i];
	TotAmt =eval(TotAmt)+eval(row.cells[8].childNodes[0].value.replace(/,/gi, ''));
	WithholdingTax  = eval(WithholdingTax)+eval(row.cells[15].childNodes[0].value.replace(/,/gi, ''));
	SalesTax  = eval(SalesTax)+eval(row.cells[11].childNodes[0].value.replace(/,/gi, ''));
	ValueAddedTax  = eval(ValueAddedTax)+eval(row.cells[13].childNodes[0].value.replace(/,/gi, ''));
	ValuePenalty  = eval(ValuePenalty)+eval(row.cells[17].childNodes[0].value.replace(/,/gi, ''));
	NET_AMOUNT = eval(TotAmt)+eval(SalesTax)+eval(ValueAddedTax)-eval(WithholdingTax)-eval(ValuePenalty);
	row.cells[18].childNodes[0].value = eval(row.cells[8].childNodes[0].value.replace(/,/gi, ''))+eval(row.cells[11].childNodes[0].value.replace(/,/gi, ''))+eval(row.cells[13].childNodes[0].value.replace(/,/gi, ''))-eval(row.cells[15].childNodes[0].value.replace(/,/gi, ''))-eval(row.cells[17].childNodes[0].value.replace(/,/gi, ''));
	NetAmount  = eval(NetAmount)+eval(row.cells[18].childNodes[0].value.replace(/,/gi, ''));
	if (eval(row.cells[10].childNodes[0].value.replace(/,/gi, ''))==1){
		RecoverableSalesTax  = eval(RecoverableSalesTax)+eval(row.cells[11].childNodes[0].value.replace(/,/gi, ''));
	}
 }
 
 SubAmt = eval(TotAmt)+eval(SalesTax)+eval(ValueAddedTax); 
 document.getElementById("TotAmt").value=eval(TotAmt).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
 document.getElementById("SubAmt").value=eval(SubAmt).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
 document.getElementById("RecoverableSalesTax").value=eval(RecoverableSalesTax).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
 document.getElementById("WithholdingTax").value=eval(WithholdingTax).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
 document.getElementById("ValuePenalty").value=eval(ValuePenalty).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
 document.getElementById("ValueAddedTax").value=eval(ValueAddedTax).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}); 
 document.getElementById("SalesTax").value=eval(SalesTax).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}); 
 document.getElementById("NetAmount").value=eval(NetAmount).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
 document.getElementById("txtAmount").value=eval(NetAmount).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
 	
 	if (rowCount>1){
		//Disable Header Vendor input to prevent change the vendor code				  
		document.getElementById("ven_emp_text").readOnly=true;
	}else{	
		document.getElementById("ven_emp_text").readOnly=false;
	}	
}
<!------------------------------------------------------------------------------------------------>
function Clear(contain){
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
			if (contain=='SEARCH_PAY_FIELDS'){frm_elements[i].value = "";}
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

function GET_PAY_TYPES() {
	
	 // Create our XMLHttpRequest object
    var hr = new XMLHttpRequest();
    // Create some variables we need to send to our PHP file
    var url = "AP_APP_MATCH_DB.php";
	var vars = "action=GET_PAY_TYPES";
    hr.open("POST", url, true);
  
	hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded; charset=utf-8");
	hr.setRequestHeader("Content-length", vars.length);
	hr.setRequestHeader("Connection", "close");
    // Access the onreadystatechange event for the XMLHttpRequest object
    hr.onreadystatechange = function() {
	    if(hr.readyState == 4 && hr.status == 200) {
		    var return_data = hr.responseText;
			document.getElementById("matchtype").innerHTML = return_data;
			document.getElementById("status").innerHTML = '';		
	    }
    }
    // Send the data to PHP now... and wait for response
    hr.send(vars); // Actually execute the request
	document.getElementById("status").innerHTML = '<div class="alert-box warning"><span>Processing: </span>please wait .....</div>';						
}
<!------------------------------------------------------------------------------------------------> 
function GET_SUBINVENTORIES() {
	
	var uid =  "<?php print $uid ; ?>";
	var ORGANIZATION_ID =  "<?php print $ORGANIZATION_ID; ?>";
	 // Create our XMLHttpRequest object
    var hr = new XMLHttpRequest();
    // Create some variables we need to send to our PHP file
    var url = "AP_APP_MATCH_DB.php";
	var vars = "ORGANIZATION_ID="+ORGANIZATION_ID+"&uid="+uid+"&action=GET_SUBINVENTORIES";
    hr.open("POST", url, true);
  
	hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded; charset=utf-8");
	hr.setRequestHeader("Content-length", vars.length);
	hr.setRequestHeader("Connection", "close");
    // Access the onreadystatechange event for the XMLHttpRequest object
    hr.onreadystatechange = function() {
	    if(hr.readyState == 4 && hr.status == 200) {
		    var return_data = hr.responseText;
			document.getElementById("store").innerHTML = return_data;
			document.getElementById("status").innerHTML = '';		
	    }
    }
    // Send the data to PHP now... and wait for response
    hr.send(vars); // Actually execute the request
	document.getElementById("status").innerHTML = '<div class="alert-box warning"><span>Processing: </span>please wait .....</div>';					
}
<!------------------------------------------------------------------------------------------------> 
function GET_SUBINVENTORY_PERIOD() {
	
	var uid =  "<?php print $uid ; ?>";
	var LE =  "<?php print $LEGAL_ENTITY_ID; ?>";
	 // Create our XMLHttpRequest object
    var hr = new XMLHttpRequest();
    // Create some variables we need to send to our PHP file
    var url = "AP_APP_MATCH_DB.php";
	var vars = "LE="+LE+"&uid="+uid+"&action=GET_SUBINVENTORY_PERIOD";
    hr.open("POST", url, true);
  
	hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded; charset=utf-8");
	hr.setRequestHeader("Content-length", vars.length);
	hr.setRequestHeader("Connection", "close");
    // Access the onreadystatechange event for the XMLHttpRequest object
    hr.onreadystatechange = function() {
	    if(hr.readyState == 4 && hr.status == 200) {
		    var return_data = hr.responseText;
			document.getElementById("invperiod").innerHTML = return_data;
			document.getElementById("status").innerHTML = '';		
	    }
    }
    // Send the data to PHP now... and wait for response
    hr.send(vars); // Actually execute the request
	document.getElementById("status").innerHTML = '<div class="alert-box warning"><span>Processing: </span>please wait .....</div>';					
}
<!------------------------------------------------------------------------------------------------> 
function GET_PURCHASE_ORDERS(vendor_id) {
	
	var uid =  "<?php print $uid ; ?>";
	var ORG_ID =  "<?php print $ORG_ID; ?>";
	 // Create our XMLHttpRequest object
    var hr = new XMLHttpRequest();
    // Create some variables we need to send to our PHP file
    var url = "AP_APP_MATCH_DB.php";
	var vars = "ORG_ID="+ORG_ID+"&vendor_id="+vendor_id+"&uid="+uid+"&action=GET_PURCHASE_ORDERS";
    hr.open("POST", url, true);
  
	hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded; charset=utf-8");
	hr.setRequestHeader("Content-length", vars.length);
	hr.setRequestHeader("Connection", "close");
    // Access the onreadystatechange event for the XMLHttpRequest object
    hr.onreadystatechange = function() {
	    if(hr.readyState == 4 && hr.status == 200) {
		    var return_data = hr.responseText;
			document.getElementById("ponum_1").innerHTML = return_data;
			document.getElementById("status").innerHTML = '';		
	    }
    }
    // Send the data to PHP now... and wait for response
    hr.send(vars); // Actually execute the request
	document.getElementById("status").innerHTML = '<div class="alert-box warning"><span>Processing: </span>please wait .....</div>';					
}
<!------------------------------------------------------------------------------------------------> 
function GET_RECEIPTS(po_id,invperiod,store) {
	
	
	//
	var uid =  "<?php print $uid ; ?>";
	var ORG_ID =  "<?php print $ORG_ID; ?>";
	var ORGANIZATION_ID =  "<?php print $ORGANIZATION_ID; ?>";
	var matchtype = document.getElementById('matchtype').options[document.getElementById('matchtype').selectedIndex].value;
	var po_num_txt ='0';
	if (document.getElementById('PO_NUM_TXT').value.trim()!=''){
		po_num_txt=document.getElementById('PO_NUM_TXT').value.trim();
	}
	
	 // Create our XMLHttpRequest object
    var hr = new XMLHttpRequest();
    // Create some variables we need to send to our PHP file
    var url = "AP_APP_MATCH_DB.php";
	var vars = "po_num_txt="+po_num_txt+"&matchtype="+matchtype+"&po_id="+po_id+"&store="+store+"&invperiod="+invperiod+"&ORGANIZATION_ID="+ORGANIZATION_ID+"&ORG_ID="+ORG_ID+"&uid="+uid+"&action=GET_RECEIPTS";
	
    hr.open("POST", url, true);
	hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded; charset=utf-8");
	hr.setRequestHeader("Content-length", vars.length);
	hr.setRequestHeader("Connection", "close");
    // Access the onreadystatechange event for the XMLHttpRequest object
    hr.onreadystatechange = function() {
	    if(hr.readyState == 4 && hr.status == 200) {
		    var return_data = hr.responseText;
			
			document.getElementById("rcv_num").innerHTML = return_data;
			document.getElementById("status").innerHTML = '';		
	    }
    }
    // Send the data to PHP now... and wait for response
    hr.send(vars); // Actually execute the request
	document.getElementById("status").innerHTML = '<div class="alert-box warning"><span>Processing: </span>please wait .....</div>';				

}
<!------------------------------------------------------------------------------------------------> 
function GET_SHIPMENTS(vendor_id) {
	
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
	    }
    }
    // Send the data to PHP now... and wait for response
    hr.send(vars); // Actually execute the request
	document.getElementById("status").innerHTML = '<div class="alert-box warning"><span>Processing: </span>please wait .....</div>';					
}
<!------------------------------------------------------------------------------------------------>
var vendor_search_caller ='close';  

function SEARCH_VENDOR_OVERLAY(callerid) {
	vendor_search_caller =callerid;
	var el = document.getElementById("SEARCH_VENDOR_OVERLAY");
	el.style.visibility = (el.style.visibility == "visible") ? "hidden" : "visible";
	if (el.style.visibility == "visible"){
	document.getElementById("search_item").focus();
	document.getElementById("search_item").select();
	}
}
<!---------------------------------------------------------------------------------------------> 	
function HandleKeyEvent3(event){
	var x = event.which || event.keyCode;
	if (x == 13){
		event.preventDefault();
		SEARCH_VENDOR_GET_RESULT();
	}
}
<!--------------------------------------------------------------------------------------------->
function SEARCH_VENDOR_GET_RESULT() {
	var uid =  "<?php print $uid ; ?>";
	var ORG_ID =  "<?php print $ORG_ID; ?>";
	var search_item = document.getElementById("search_item").value;
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
    var url = "AP_APP_MATCH_DB.php";
	var vars = "ORG_ID="+ORG_ID+"&uid="+uid+"&search_item="+search_item+"&action=SEARCH_VENDOR_GET_RESULT";
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
			document.getElementById("search_vendor_results").innerHTML = return_data;
			document.getElementById("status").innerHTML = '';		
	    }
    }
    // Send the data to PHP now... and wait for response to update the status div
    hr.send(vars); // Actually execute the request
	document.getElementById("status").innerHTML = '<div class="alert-box warning"><span>Processing: </span>please wait .....</div>';					
}
<!---------------------------------------------------------------------------------------------> 		


function SELECT_VENDOR(str_id,str_n) {
	if (vendor_search_caller == 'search_vend_1'){
		document.getElementById("vendorid_1").value = str_id;
		document.getElementById("vendorn_1").value = str_n;
		GET_PURCHASE_ORDERS(str_id);
	}
    if (vendor_search_caller == 'search_vend'){
		document.getElementById("vendorid").value = str_id;
		document.getElementById("vendorn").value = str_n;
		GET_SHIPMENTS(str_id);
	}
	el = document.getElementById("SEARCH_VENDOR_OVERLAY");
	el.style.visibility = (el.style.visibility == "visible") ? "hidden" : "visible";
	   
				
}
<!--------------------------------------------------------------------------------------------->
function SEARCH_VENDOR_NAME(search_item,search_id) {
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
    var url = "AP_APP_MATCH_DB.php";
	var vars = "ORG_ID="+ORG_ID+"&uid="+uid+"&search_item="+search_item+"&action=SEARCH_VENDOR_NAME";
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
			document.getElementById("status").innerHTML = '';		
			document.getElementById(search_id).value = return_data;
			if (search_id == 'vendorn_1'){
				GET_PURCHASE_ORDERS(search_item);
			}
			if (search_id == 'vendorn'){
				GET_SHIPMENTS(search_item);
			}
	    }
    }
    // Send the data to PHP now... and wait for response to update the status div
    hr.send(vars); // Actually execute the request
	document.getElementById("status").innerHTML = '<div class="alert-box warning"><span>Processing: </span>please wait .....</div>';					
}

<!--------------------------------------------------------------------------------------------->
function EDIT_PRICE_OVERLAY(rowidx){
 if (eval(JE_HEADER_ID) !=0){
	document.getElementById("status").innerHTML = '<div class="alert-box errormsg">You could not update records which have a posted journal entry.</div>';
	return ;	
 }
 document.getElementById("M_GL_PENALTY").value="9999999";

 if (rowidx > 0){
	 var table = document.getElementById("recRCTGrid");
	 var rowCount = table.rows.length;
	 var row = table.rows[rowidx]; 
	 
	 var colINVOC_NUM=row.cells[0];
	 for (var i=0; i<colINVOC_NUM.childNodes.length; i++){
		 if (colINVOC_NUM.childNodes[i].id=='MATCH_INV_NUM'){
			document.getElementById("LN_INVOC_NUM").value = row.cells[0].childNodes[i].value;
			break;
		 }
	 }	 
	 	
	 document.getElementById("LN_INVOC_D").value = row.cells[1].childNodes[0].value;
	 document.getElementById("M_INV_PRICE").value = row.cells[5].childNodes[0].value;	
	 document.getElementById("M_QTY").value = row.cells[2].childNodes[0].value;
	 document.getElementById("M_ROW_INDEX").value = rowidx;
	 
	 var M_SAL_TAX=document.getElementById("M_SAL_TAX");
	 for (var i=0; i<M_SAL_TAX.length; i++){
		if (eval(M_SAL_TAX.options[i].value) == eval(row.cells[9].childNodes[0].value)){
			M_SAL_TAX.options[i].selected = true;
		}
	 }
	 
	 var M_R_SAL_TAX_FLAG=document.getElementById("M_R_SAL_TAX_FLAG");
 	 for (var i=0; i<M_R_SAL_TAX_FLAG.length; i++){
 	 if (eval(M_R_SAL_TAX_FLAG.options[i].value) == eval(row.cells[10].childNodes[0].value)){
		 M_R_SAL_TAX_FLAG.options[i].selected = true;
	 }
	 
	 var M_WAT=document.getElementById("M_WAT");
	 for (var i=0; i<M_WAT.length; i++){
		 if (eval(M_WAT.options[i].value) == eval(row.cells[14].childNodes[0].value)){
			 M_WAT.options[i].selected = true;
		 }
	 }
	 
	 var M_VAT=document.getElementById("M_VAT");
	 for (var i=0; i<M_VAT.length; i++){
		 if (eval(M_VAT.options[i].value) == eval(row.cells[12].childNodes[0].value)){
		 	M_VAT.options[i].selected = true;
	 	 }
	 }
	 
	 var colHidden=row.cells[23];
	 for (var i=0; i<colHidden.childNodes.length; i++){
		 if (colHidden.childNodes[i].id=='GLACCOUNT'){
			 var M_GL_VENDOR=document.getElementById("M_GL_VENDOR");
			 for (var j=0; j<M_GL_VENDOR.length; j++){
				 if (M_GL_VENDOR.options[j].value == colHidden.childNodes[i].value){
					 M_GL_VENDOR.options[j].selected = true;
				 }
			 }
		 }
		 
		 if (colHidden.childNodes[i].id=='COSTCENTER'){
			 var M_GL_CC=document.getElementById("M_GL_CC");
			 for (var j=0; j<M_GL_CC.length; j++){
				 if (M_GL_CC.options[j].value == colHidden.childNodes[i].value){
					 M_GL_CC.options[j].selected = true;
				 }
			 }
		 }
		 
		if (colHidden.childNodes[i].id=='PENALTY_GLACCOUNT'){
			 var M_GL_PENALTY=document.getElementById("M_GL_PENALTY");
			 for (var j=0; j<M_GL_PENALTY.length; j++){
				 if (M_GL_PENALTY.options[j].value == colHidden.childNodes[i].value){
					 M_GL_PENALTY.options[j].selected = true;
				 }
			 }
		 }
		 
		 if (colHidden.childNodes[i].id=='PENALTY_GLCOSTCENTER'){
			 var M_GL_CC_PENALTY=document.getElementById("M_GL_CC_PENALTY");
			 for (var j=0; j<M_GL_CC_PENALTY.length; j++){
				 if (M_GL_CC_PENALTY.options[j].value == colHidden.childNodes[i].value){
					 M_GL_CC_PENALTY.options[j].selected = true;
				 }
			 }
		 }
		 
		 if (colHidden.childNodes[i].id=='VENDOR_CODE'){
			 GET_VENDOR_TAX_DATA(colHidden.childNodes[i].value);			 
		 }
		 
	 }
	 
	 
 } 	

 }
 var el = document.getElementById("EDIT_PRICE_OVERLAY");
 el.style.visibility = (el.style.visibility == "visible") ? "hidden" : "visible";
 if (el.style.visibility == "visible"){
 document.getElementById("M_INV_PRICE").focus();
 document.getElementById("M_INV_PRICE").select();
 }
}
<!------------------------------------------------------------------------------------------------> 
function ValidateInvoiceData(X, Y) {
	if (isNaN(X) ||(X.length < 8)||(X.trim() == "")||(eval(X.trim().substr(4, 2))>12)||(eval(X.trim().substr(4, 2))<1)||(eval(X.trim().substr(6, 2))>31)||(eval(X.trim().substr(6, 2))<1) ) {
	  alert('Invoice Date is not valid..\n\nInvoice Date Format: [YYYYMMDD].');
	  document.getElementById('txtInvDate').focus();
	  document.getElementById('txtInvDate').select();	
	  return false;
    }
	
	Y = Y.replace("'", "");
	Y = Y.replace('"', '');
	Y = Y.replace('&', ',');
	Y = Y.replace(':', '-');
	Y = Y.replace('%', '');
	Y = Y.replace('#', '');
	Y = Y.replace('$', 'USD');
	Y = Y.replace('@', '-at-');
	Y = Y.replace('^', '');
	Y = Y.replace('/', '-');
	Y = Y.replace('\\', '-');
	Y = Y.replace('?', '');
	Y = Y.replace('!', '');
	Y = Y.trim();
	Y = Y.replace(/^\s+|\s+$/gm,'');
	Y = Y.toUpperCase();
	
	if (Y ==''){
	  alert('Invoice Number is not valid..');	  
	  document.getElementById('invcnum').focus();
	  document.getElementById('invcnum').select();	
	  return false;
	}
	/*
	if (confirm('Please confirm the invoice data:\n\n Invoice Date: '+X+'\n Invoice Number: '+Y)){
	return true;
	}else{
	 document.getElementById('invcnum').focus();
	 document.getElementById('invcnum').select();
	 return false;	
	}
	*/
	return true;
}
<!--------------------------------------------------------------------------------------------->
/*
function VaildateMatchRowIsExisted(PO_LINE_ID,RCV_ID,ITEM,ITEM_Q,ITEM_U){
	
	var table = document.getElementById("recRCTGrid");
	var rowCount = table.rows.length;
	var PO_LINE_IDIsExisted =0;
	var RCV_IDIsExisted = 0;
	
	
	var RowIsExistedCRC = eval(PO_LINE_ID.trim())+eval(RCV_ID.trim());
	
	
    if ((eval(rowCount)-1) >= 2){
 	for(var i=1; i<rowCount; i++) {
		
 		var row = table.rows[i];		
		var colHidden=row.cells[23];
		
		for (var j=0; j<colHidden.childNodes.length; j++){
			
			if ((colHidden.childNodes[j].id=='PO_HEADER_ID')){
				PO_LINE_IDIsExisted = eval(PO_LINE_IDIsExisted)+eval(colHidden.childNodes[j].value);
			}
			if ((colHidden.childNodes[j].id=='TRANSACTION_ID')&&(eval(RCV_ID.trim())==eval(colHidden.childNodes[j].value))){
				RCV_IDIsExisted = eval(RCV_IDIsExisted)+eval(colHidden.childNodes[j].value);
			}
		}
		
	}
	
	alert(RowIsExistedCRC +' - '+(eval(RCV_IDIsExisted)+eval(PO_LINE_IDIsExisted)));
	
	if (eval(RowIsExistedCRC)<=eval(eval(RCV_IDIsExisted)+eval(PO_LINE_IDIsExisted))){
		if (confirm('Record is already existed:\n---------------------\n\n'+'PO Lind ID: '+PO_LINE_ID+'\nReceiving Trans. ID: '+RCV_ID+'\nItem : '+ITEM+'\nItem Qty.: '+ITEM_Q+'  '+ITEM_U+'\n\nAre you, sure, you want to add this record again?')){
			return true;
		}else{
			return false;
		}
	}
	
	}
	return true;
		 
}*/
<!--------------------------------------------------------------------------------------------->
function HandleGridMatchKeyEvent(event,Z){
if (eval(JE_HEADER_ID) !=0){
	event.preventDefault();
	event.stopPropagation();
	document.getElementById("status").innerHTML = '<div class="alert-box errormsg">You could not update records which have a posted journal entry.</div>';
	return false;	
}
var x = event.which || event.keyCode;
//alert('x='+x+' - z='+Z);
if (x==121){EDIT_PRICE_OVERLAY(Z)}
if (x==46){
if (confirm('Are you sure, you want to delete the current record?')){	
  var oTable = $('#recRCTGrid').dataTable();
  // Immediately remove the first row
  oTable.fnDeleteRow(Z-1);
  //oTable.row(Z-1).remove().draw();
  UpdateSummaryGrid();
}
}
}
<!----
</script>
