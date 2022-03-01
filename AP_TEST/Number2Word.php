<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL);

function truncate($value)
{
    if ($value < 0)
    {
        return ceil($value);
    }
    else
    {
        return floor($value);
    }
}

function get_value($MYvAL ,$CurrCode) {
$piaster = 0;
$piaster = $MYvAL - truncate($MYvAL);
$MYvAL = $MYvAL - $piaster;
//------------------------------
$intVal = '';
$DecVal = '';
$Curr = '';
$indx=0;
$Ln = 0;
$txtDesc =  (string)$MYvAL;
$txtvals =  (string)$MYvAL;
$i=0;
$J=0;
$Y1=0;
$Y2=0;
$N1=0;
$N2=0;
//------------------------------
$CurrText ='';

switch ($CurrCode){
    case "CHF":
        $CurrText = "فرنك سويسرى";
    break;
    case "EGP":
        $CurrText = "EGP";
    break;
    case "GBP":
        $CurrText = "جنيه إسترلينى";
    break;
    case "JPY":
        $CurrText = "ين يابانى";
    break;
    case "SAR":
        $CurrText = "ريال سعودى";
    break;
    case "SEK":
        $CurrText = "كرونة سويدى";
    break;
    case "USD":
        $CurrText = "دولار أمريكى";
    break;
    case "EUR":
        $CurrText = "يورو";
    break;
	default: '';
}
//------------------------------
$intVal = $MYvAL;
$DecVal = round($piaster) * 100;
$Ln = strlen($intVal);
$Indx = $Ln;


$Y1 = 8;
$Y2 = 8;
$J = 1;
for ($i = 8 - $Ln + 1; $i <= 8; $i++) {
 $txtvals[i] = substr($intVal, J, 1);
  $J = $J + 1;
} 

$indx = 0;

if ($txtvals[1] > 0) {
if ($txtvals[2] > 0) {
 $indx = $indx + 1;
 if (($txtvals[2] == 1 ) && ( $txtvals[1] == 1)) { $txtDesc[$indx] = "احد";}
 if (($txtvals[2] == 1 ) && ( $txtvals[1] != 1 )) { $txtDesc[$indx] = "واحد";}
 if (($txtvals[2] == 2 ) && ( $txtvals[1] == 1 )) { $txtDesc[$indx] = "إثنى";}
 if (($txtvals[2] == 2 ) && ( $txtvals[1] != 1 )) { $txtDesc[$indx] = "إثنين";}
 if ($txtvals[2] == 3) {$txtDesc[$indx] = "ثلاثة";}
 if ($txtvals[2] == 4) {$txtDesc[$indx] = "أربعة";}
 if ($txtvals[2] == 5) {$txtDesc[$indx] = "خمسة";}
 if ($txtvals[2] == 6) {$txtDesc[$indx] = "ستة";}
 if ($txtvals[2] == 7) {$txtDesc[$indx] = "سبعة";}
 if ($txtvals[2] == 8) {$txtDesc[$indx] = "ثمانى";}
 If ($txtvals[2] == 9) {$txtDesc[$indx] = "تسعة";}
 If (($txtvals[2] != 0 ) && ( $txtvals[1] > 1 )){
 $indx = $indx + 1;
 $txtDesc[$indx] = "و";
 }
}
}
return $txtDesc;
}
print truncate(10000.35);
print '<br>';
print get_value(10000.35,'EGP');
?>