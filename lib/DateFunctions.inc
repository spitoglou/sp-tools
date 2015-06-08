<?php
/* $Revision: 1.15 $ */
/* date validation and parsing functions
These functions refer to the config variable defining the date format
The date format is defined in SystemParameters called DefaultDateFormat
this can be a string either 'd/m/Y' for UK/Australia/New Zealand dates or
'm/d/Y' for US/Canada format dates */

function number_to_month($number){
   $number = Trim($number);
   if ($number == "1" || $number == "1"){
      $month = 'Ιανουάριος';
   }elseif($number == "2" || $number == "02"){
      $month = 'Φεβρουάριος';
   }elseif($number == "3" || $number == "03"){
      $month = 'Μάρτιος';
   }elseif($number == "4" || $number == "04"){
      $month = 'Απρίλιος';
   }elseif($number == "5" || $number == "05"){
      $month = 'Μάιος';
   }elseif($number == "6" || $number == "06"){
      $month = 'Ιούνιος';
   }elseif($number == "7" || $number == "07"){
      $month = 'Ιούλιος';
   }elseif($number == "8" || $number == "08"){
      $month = 'Αύγουστος';
   }elseif($number == "9" || $number == "09"){
      $month = 'Σεπτέμβριος';
   }elseif($number == "10"){
      $month = 'Οκτώβριος';
   }elseif($number == "11"){
      $month = 'Νοέμβριος';
   }elseif($number == "12"){
      $month = 'Δεκέμβριος';
   }

   return $month;

}

function Is_Date($DateEntry) {

   $DateEntry =Trim($DateEntry);
   if (strpos($DateEntry,'/')) {
      $Date_Array = explode('/',$DateEntry);
   } elseif (strpos ($DateEntry,'-')) {
      $Date_Array = explode('-',$DateEntry);
   } elseif (strlen($DateEntry)==6) {
      $Date_Array[0]= substr($DateEntry,0,2);
      $Date_Array[1]= substr($DateEntry,2,2);
      $Date_Array[2]= substr($DateEntry,4,2);
   } elseif (strlen($DateEntry)==8) {
      $Date_Array[0]= substr($DateEntry,0,2);
      $Date_Array[1]= substr($DateEntry,2,2);
      $Date_Array[2]= substr($DateEntry,4,4);
   }
   If ((int)$Date_Array[2] >9999) {
      Return 0;
   }
   if (is_long((int)$Date_Array[0]) AND is_long((int)$Date_Array[1]) AND is_long((int)$Date_Array[2])) {
      if ($_SESSION['DefaultDateFormat']=='d/m/Y'){
         if (checkdate((int)$Date_Array[1],(int)$Date_Array[0],(int)$Date_Array[2])){
            Return 1;
         } else {
            Return 0;
         }
      } elseif ($_SESSION['DefaultDateFormat']=='m/d/Y'){

         if (checkdate((int)$Date_Array[0],(int)$Date_Array[1],(int)$Date_Array[2])){
            Return 1;
         } else {
            Return 0;
         }
      } else { /*Can't be in an appropriate DefaultDateFormat */
         Return 0;
      }
   }else { // end if all numeric inputs
      Return 0;
   }

} //end of Is_Date function

function Is_Time($TimeEntry) {

   $TimeEntry =Trim($TimeEntry);
   $TimeEntry = preg_replace("[^0-9:]", "", $TimeEntry );
   //echo $TimeEntry."<br>";
   if (strpos($TimeEntry,':')) {
      $Time_Array = explode(':',$TimeEntry);
   } else {
      Return 0;
   }
   If ((int)$Time_Array[0] >23) {
      Return 0;
   }
   If ((int)$Time_Array[1] >59) {
      Return 0;
   }
   if (is_long((int)$Time_Array[0]) AND is_long((int)$Time_Array[1])) {
      Return 1;
   }else { // end if all numeric inputs
      Return 0;
   }

} //end of Is_Date function

function Is_DateSQL($DateEntry) {
   $DateEntry =Trim($DateEntry);
   if (strpos($DateEntry,'/')) {
      $Date_Array = explode('/',$DateEntry);
   } elseif (strpos ($DateEntry,'-')) {
      $Date_Array = explode('-',$DateEntry);
   } elseif (strlen($DateEntry)==6) {
      $Date_Array[0]= substr($DateEntry,0,2);
      $Date_Array[1]= substr($DateEntry,2,2);
      $Date_Array[2]= substr($DateEntry,4,2);
   } elseif (strlen($DateEntry)==8) {
      $Date_Array[0]= substr($DateEntry,0,2);
      $Date_Array[1]= substr($DateEntry,2,2);
      $Date_Array[2]= substr($DateEntry,4,4);
   }


   If ((int)$Date_Array[2] >9999) {
      Return 0;
   }


   if (is_long((int)$Date_Array[0]) AND is_long((int)$Date_Array[1]) AND is_long((int)$Date_Array[2])) {

      if (checkdate((int)$Date_Array[1],(int)$Date_Array[2],(int)$Date_Array[0])){
         Return 1;
      } else {
         Return 0;
      }

   }else { // end if all numeric inputs
      Return 0;
   }

} //end of Is_Date function

function MonthAndYearFromSQLDate($DateEntry) {

   if (strpos($DateEntry,'/')) {
      $Date_Array = explode('/',$DateEntry);
   } elseif (strpos ($DateEntry,'-')) {
      $Date_Array = explode('-',$DateEntry);
   }

   if (strlen($Date_Array[2])>4) {
      $Date_Array[2]= substr($Date_Array[2],0,2);
   }

   Return ucfirst(strftime('%B %Y', adodb_mktime(0,0,0, (int)$Date_Array[1],(int)$Date_Array[2],(int)$Date_Array[0])));

}

function SecondsToTime($secs) {

   $hours = intval((int)$secs/3600);
   $mins = intval(((int)$secs-(int)$hours*3600)/60);

   $res = sprintf("%02d:%02d", $hours, $mins);

   Return $res;
}

function ConvertSQLDate($DateEntry) {

//for MySQL dates are in the format YYYY-mm-dd

    if (Is_DateSQL($DateEntry)) {

      if (strpos($DateEntry,'/')) {
         $Date_Array = explode('/',$DateEntry);
      } elseif (strpos ($DateEntry,'-')) {
         $Date_Array = explode('-',$DateEntry);
      }

      if (strlen($Date_Array[2])>4) {  /*chop off the time stuff */
         $Date_Array[2]= substr($Date_Array[2],0,2);
      }


      if ($_SESSION['DefaultDateFormat']=='d/m/Y'){
         Return $Date_Array[2].'/'.$Date_Array[1].'/'.$Date_Array[0];
      } elseif ($_SESSION['DefaultDateFormat']=='m/d/Y'){
         Return $Date_Array[1].'/'.$Date_Array[2].'/'.$Date_Array[0];
      }

    } else {

      Return '';
    }

} // end function ConvertSQLDate

function ConvertSQLTime($DateEntry) {

//for MySQL dates are in the format YYYY-mm-dd

   $i = strpos($DateEntry,' ');

   $mystr = substr($DateEntry,$i,strlen($DateEntry));

   $Date_Array = explode(':',$mystr);

   if ($DateEntry!='') {
      return $Date_Array[0].":".$Date_Array[1];
   } else {
      return '';
   }

/* if (strpos($DateEntry,'/')) {
      $Date_Array = explode('/',$DateEntry);
   } elseif (strpos ($DateEntry,'-')) {
      $Date_Array = explode('-',$DateEntry);
   }

   if (strlen($Date_Array[2])>4) {  /*chop off the time stuff */ /*
      $Date_Array[2]= substr($Date_Array[2],0,2);
   }


   if ($_SESSION['DefaultDateFormat']=='d/m/Y'){
      Return $Date_Array[2].'/'.$Date_Array[1].'/'.$Date_Array[0];
   } elseif ($_SESSION['DefaultDateFormat']=='m/d/Y'){
      Return $Date_Array[1].'/'.$Date_Array[2].'/'.$Date_Array[0];
   } */

} // end function ConvertSQLDate

function SQLDateToEDI($DateEntry) {

//for MySQL dates are in the format YYYY-mm-dd
//EDI format 102 dates are in the format CCYYMMDD - just need to lose the seperator

   if (strpos($DateEntry,'/')) {
      $Date_Array = explode('/',$DateEntry);
   } elseif (strpos ($DateEntry,'-')) {
      $Date_Array = explode('-',$DateEntry);
   }

   if (strlen($Date_Array[2])>4) {  /*chop off the time stuff */
      $Date_Array[2]= substr($Date_Array[2],0,2);
   }

   Return $Date_Array[0].$Date_Array[1].$Date_Array[2];

} // end function SQLDateToEDI

function ConvertToEDIDate($DateEntry) {

/* takes a date in a the format specified in $_SESSION['DefaultDateFormat']
and converts to a yyyymmdd - EANCOM format 102*/


   $DateEntry = trim($DateEntry);

   if (strpos($DateEntry,'/')) {
      $Date_Array = explode('/',$DateEntry);
   } elseif (strpos ($DateEntry,'-')) {
      $Date_Array = explode('-',$DateEntry);
   } elseif (strlen($DateEntry)==6) {
      $Date_Array[0]= substr($DateEntry,0,2);
      $Date_Array[1]= substr($DateEntry,2,2);
      $Date_Array[2]= substr($DateEntry,4,2);
   } elseif (strlen($DateEntry)==8) {
      $Date_Array[0]= substr($DateEntry,0,2);
      $Date_Array[1]= substr($DateEntry,2,2);
      $Date_Array[2]= substr($DateEntry,4,4);
   }


//to modify assumption in 2030

   If ((int)$Date_Array[2] <60) {
      $Date_Array[2] = '20'.$Date_Array[2];
   } elseif ((int)$Date_Array[2] >59 AND (int)$Date_Array[2] <100) {
      $Date_Array[2] = '19'.$Date_Array[2];
   } elseif ((int)$Date_Array[2] >9999) {
      Return 0;
   }

   if ($_SESSION['DefaultDateFormat']=='d/m/Y'){
      Return $Date_Array[2].$Date_Array[1].$Date_Array[0];

   } elseif ($_SESSION['DefaultDateFormat']=='m/d/Y') {
      Return $Date_Array[2].$Date_Array[0].$Date_Array[1];

   }

} // end function to convert DefaultDateFormat Date to EDI format 102

function ConvertEDIDate($DateEntry, $EDIFormatCode) {

   /*EDI Format codes:
      102  -  CCYYMMDD
      203  -  CCYYMMDDHHMM
      616  -  CCYYWW  - cant handle the week number
      718  -  CCYYMMDD-CCYYMMDD  can't handle this either a date range
   */


   switch ($EDIFormatCode) {
   case 102:
      if ($_SESSION['DefaultDateFormat']=='d/m/Y'){
         Return substr($DateEntry,6,2).'/'.substr($DateEntry,4,2).'/'.substr($DateEntry,0,4);

      } elseif ($_SESSION['DefaultDateFormat']=='m/d/Y') {
                  Return substr($DateEntry,4,2).'/'.substr($DateEntry,6,2).'/'.substr($DateEntry,0,4);

      }
      break;
   case 203:
      if ($_SESSION['DefaultDateFormat']=='d/m/Y'){
         Return substr($DateEntry,6,2).'/'.substr($DateEntry,4,2).'/'.substr($DateEntry,0,4).' ' . substr($DateEntry,6,2).':' . substr($DateEntry,8,2);

      } elseif ($_SESSION['DefaultDateFormat']=='m/d/Y') {
                  Return substr($DateEntry,4,2).'/'.substr($DateEntry,6,2).'/'.substr($DateEntry,0,4).' ' . substr($DateEntry,6,2).':' . substr($DateEntry,8,2);

      }
      break;
   case 616:
      /*multiply the week number by 7 and add to the 1/1/CCYY */
      Return date($_SESSION['DefaultDateFormat'], adodb_mktime(0,0,0, 1,1+(7*(int)substr($DateEntry,4,2)),substr($DateEntry,0,4)));
      break;
   case 718:
      if ($_SESSION['DefaultDateFormat']=='d/m/Y'){
         Return substr($DateEntry,6,2).'/'.substr($DateEntry,4,2).'/'.substr($DateEntry,0,4) . ' - '. substr($DateEntry,15,2).'/'.substr($DateEntry,13,2).'/'.substr($DateEntry,9,4);

      } elseif ($_SESSION['DefaultDateFormat']=='m/d/Y') {
                  Return substr($DateEntry,4,2).'/'.substr($DateEntry,6,2).'/'.substr($DateEntry,0,4).' - '. substr($DateEntry,13,2).'/'.substr($DateEntry,15,2).'/'.substr($DateEntry,9,4);

      }

      break;
   }


}

function Format_Date($DateEntry) {

   $DateEntry =trim($DateEntry);

   if (strpos($DateEntry,'/')) {
      $Date_Array = explode('/',$DateEntry);
   } elseif (strpos ($DateEntry,'-')) {
      $Date_Array = explode('-',$DateEntry);
   } elseif (strlen($DateEntry)==6) {
      $Date_Array[0]= substr($DateEntry,0,2);
      $Date_Array[1]= substr($DateEntry,2,2);
      $Date_Array[2]= substr($DateEntry,4,2);
   } elseif (strlen($DateEntry)==8) {
      $Date_Array[0]= substr($DateEntry,0,2);
      $Date_Array[1]= substr($DateEntry,2,2);
      $Date_Array[2]= substr($DateEntry,4,4);
   }

//to modify assumption in 2030

   If ((int)$Date_Array[2] <60) {
      $Date_Array[2] = '20'.$Date_Array[2];
   } elseif ((int)$Date_Array[2] >59 AND (int)$Date_Array[2] <100)                  {
      $Date_Array[2] = '19'.$Date_Array[2];
   } elseif ((int)$Date_Array[2] >9999) {
      Return 0;
   }

   If (is_long((int)$Date_Array[0]) AND is_long((int)$Date_Array[1]) AND is_long((int)$Date_Array[2])) {
      if ($_SESSION['DefaultDateFormat']=='d/m/Y'){
         if (checkdate((int)$Date_Array[1],(int)$Date_Array[0],(int)$Date_Array[2])){
            Return $Date_Array[0].'/'.$Date_Array[1].'/'.$Date_Array[2];
         }
      } elseif ($_SESSION['DefaultDateFormat']='m/d/Y'){
         if (checkdate((int)$Date_Array[0],(int)$Date_Array[1],(int)$Date_Array[2]))                     {
            Return $Date_Array[0].'/'.$Date_Array[1].'/'.$Date_Array[2];
         }
      } // end if check date
   } else { // end if all numeric inputs
      Return 0;
   }
}// end of function

function FormatDateForSQL($DateEntry) {

/* takes a date in a the format specified in $_SESSION['DefaultDateFormat']
and converts to a yyyy/mm/dd format */


   $DateEntry = trim($DateEntry);

   if (Is_Date($DateEntry)) {


   if (strpos($DateEntry,'/')) {
      $Date_Array = explode('/',$DateEntry);
   } elseif (strpos ($DateEntry,'-')) {
      $Date_Array = explode('-',$DateEntry);
   } elseif (strlen($DateEntry)==6) {
      $Date_Array[0]= substr($DateEntry,0,2);
      $Date_Array[1]= substr($DateEntry,2,2);
      $Date_Array[2]= substr($DateEntry,4,2);
   } elseif (strlen($DateEntry)==8) {
      $Date_Array[0]= substr($DateEntry,0,4);
      $Date_Array[1]= substr($DateEntry,4,2);
      $Date_Array[2]= substr($DateEntry,6,2);
   }

   /*echo '<BR>The date was originally: ' . $DateEntry;
   echo '<BR>Date array 0 = ' . $Date_Array[0];
   echo '<BR>Date array 1 = ' . $Date_Array[1];
   echo '<BR>Date array 2 = ' . $Date_Array[2];
   */
//to modify assumption in 2030

   If ((int)$Date_Array[2] <60) {
      $Date_Array[2] = '20'.$Date_Array[2];
   } elseif ((int)$Date_Array[2] >59 AND (int)$Date_Array[2] <100) {
      $Date_Array[0] = '19'.$Date_Array[2];
   } elseif ((int)$Date_Array[2] >9999) {
      Return 0;
   }

   if ($_SESSION['DefaultDateFormat']=='d/m/Y'){
      /* echo '<BR>The date returned is ' . $Date_Array[2].'/'.$Date_Array[1].'/'.$Date_Array[0]; */
      //To_Date('1993-11-08','YYYY-MM-DD')
      Return 'To_Date(\''.$Date_Array[2].'-'.$Date_Array[1].'-'.$Date_Array[0].'\',\'YYYY-MM-DD\')';

   } elseif ($_SESSION['DefaultDateFormat']=='m/d/Y') {
      Return $Date_Array[2].'/'.$Date_Array[0].'/'.$Date_Array[1];

   }

   } else {
      Return "''";
   }

}// end of function

function FormatTimeForSQL($DateEntry) {

/* takes a date in a the format specified in $_SESSION['DefaultDateFormat']
and converts to a yyyy/mm/dd format */


   $DateEntry = trim($DateEntry);
   $DateEntry = preg_replace("[^0-9:]", "", $DateEntry );
   //echo $DateEntry;

   if (strpos($DateEntry,'/')) {
      $Date_Array = explode('/',$DateEntry);
   } elseif (strpos ($DateEntry,'-')) {
      $Date_Array = explode('-',$DateEntry);
   } elseif (strlen($DateEntry)==6) {
      $Date_Array[0]= substr($DateEntry,0,2);
      $Date_Array[1]= substr($DateEntry,2,2);
      $Date_Array[2]= substr($DateEntry,4,2);
   } elseif (strlen($DateEntry)==8) {
      $Date_Array[0]= substr($DateEntry,0,4);
      $Date_Array[1]= substr($DateEntry,4,2);
      $Date_Array[2]= substr($DateEntry,6,2);
   }

   /*echo '<BR>The date was originally: ' . $DateEntry;
   echo '<BR>Date array 0 = ' . $Date_Array[0];
   echo '<BR>Date array 1 = ' . $Date_Array[1];
   echo '<BR>Date array 2 = ' . $Date_Array[2];
   */
//to modify assumption in 2030

   If ((int)$Date_Array[2] <60) {
      $Date_Array[2] = '20'.$Date_Array[2];
   } elseif ((int)$Date_Array[2] >59 AND (int)$Date_Array[2] <100) {
      $Date_Array[0] = '19'.$Date_Array[2];
   } elseif ((int)$Date_Array[2] >9999) {
      Return 0;
   }

   if ($_SESSION['DefaultDateFormat']=='d/m/Y'){
      /* echo '<BR>The date returned is ' . $Date_Array[2].'/'.$Date_Array[1].'/'.$Date_Array[0]; */
      //To_Date('1993-11-08','YYYY-MM-DD')
      //Return 'To_Date(\''.$Date_Array[2].'-'.$Date_Array[1].'-'.$Date_Array[0].'\',\'YYYY-MM-DD\')';
      if ($DateEntry!='') {
         Return 'To_Date(\'1899-12-30 '.$DateEntry.':00\',\'YYYY-MM-DD HH24:MI:SS\')';
      } else {
         Return '\'\'';
      }

   } elseif ($_SESSION['DefaultDateFormat']=='m/d/Y') {
      Return $Date_Array[2].'/'.$Date_Array[0].'/'.$Date_Array[1];

   }

}// end of function

function Date1GreaterThanDate2 ($Date1, $Date2) {

/* returns 1 true if Date1 is greater than Date 2 */

   $Date1 = trim($Date1);
   $Date2 = trim($Date2);
   $Date1_array = explode('/', $Date1);
   $Date2_array = explode('/', $Date2);

   /*Try to make the year of each date comparable - if one date is specified as just
   2 characters and the other >2 then take the last 2 characters of the other date only */
   if (strlen($Date1_array[2])>2 AND strlen($Date2_array[2])==2){
      $Date1_array[2] = substr($Date1_array[2], strlen($Date1_array[2])-2);
   }
   if (strlen($Date2_array[2])>2 AND strlen($Date1_array[2])==2){
      $Date2_array[2] = substr($Date2_array[2], strlen($Date2_array[2])-2);
   }

   /*The 2 element of the array will be the year in either d/m/Y or m/d/Y formats */

   If (($Date1_array[2] - $Date2_array[2]) >0){
      Return 1;
   } Elseif (($Date1_array[2] - $Date2_array[2]) ==0){

   /*The 0 and 1 elements of the array are switched depending on the format used */

      if ($_SESSION['DefaultDateFormat']=='d/m/Y'){
         If ( ($Date1_array[1] -  $Date2_array[1]) >0){
            Return 1;
         } elseif (($Date1_array[1] - $Date2_array[1])==0){
            If (($Date1_array[0] -  $Date2_array[0])>0){
               Return 1;
            }
         }

      } elseif ($_SESSION['DefaultDateFormat'] =='m/d/Y'){
         If (($Date1_array[0] - $Date2_array[0])>0){
            Return 1;
         } elseif (($Date1_array[0] - $Date2_array[0])==0){
            If (($Date1_array[1] - $Date2_array[1])>0){
               Return 1;
            }
         }
      }
   }

   Return 0;
}

function CalcDueDate($TranDate, $DayInFollowingMonth, $DaysBeforeDue){

   $TranDate = trim($TranDate);

   $Date_array = explode('/', $TranDate);

   if ($_SESSION['DefaultDateFormat']=='d/m/Y'){
      if ($DayInFollowingMonth==0){ /*then it must be set up for DaysBeforeDue type */

         $DayDue = $Date_array[0]+$DaysBeforeDue;
         $MonthDue = $Date_array[1];
         $YearDue = $Date_array[2];

      } elseif($DayInFollowingMonth>=29) { //take the last day of month

         $DayDue = 0;
         $MonthDue = $Date_array[1]+2;
         $YearDue = $Date_array[2];
      } else {
         $DayDue = $DayInFollowingMonth;
         $MonthDue = $Date_array[1]+1;
         $YearDue = $Date_array[2];

      }
   } elseif ($_SESSION['DefaultDateFormat']=='m/d/Y'){
      if ($DayInFollowingMonth==0){ /*then it must be set up for DaysBeforeDue type */
         $DayDue = $Date_array[1]+$DaysBeforeDue;
         $MonthDue = $Date_array[0];
         $YearDue = $Date_array[2];

      } elseif($DayInFollowingMonth>=29) { //take the last day of month

         $DayDue = 0;
         $MonthDue = $Date_array[0]+2;
         $YearDue = $Date_array[2];
      } else {
         $DayDue = $DayInFollowingMonth;
         $MonthDue = $Date_array[0]+1;
         $YearDue = $Date_array[2];
      }
   }
   Return adodb_date($_SESSION['DefaultDateFormat'], adodb_mktime(0,0,0, $MonthDue, $DayDue,$YearDue));

}

function get_weekday_name ( $DateEntry ) {
   $DateEntry = trim($DateEntry);

   if (strpos($DateEntry,'/')) {
      $Date_Array = explode('/',$DateEntry);
   } elseif (strpos ($DateEntry,'-')) {
      $Date_Array = explode('-',$DateEntry);
   } elseif (strlen($DateEntry)==6) {
      $Date_Array[0]= substr($DateEntry,0,2);
      $Date_Array[1]= substr($DateEntry,2,2);
      $Date_Array[2]= substr($DateEntry,4,2);
   } elseif (strlen($DateEntry)==8) {
      $Date_Array[0]= substr($DateEntry,0,4);
      $Date_Array[1]= substr($DateEntry,4,2);
      $Date_Array[2]= substr($DateEntry,6,2);
   }

   $Date_Array[2].$Date_Array[1].$Date_Array[0];
   $indate = mktime ( 3, 0, 0, $Date_Array[1], $Date_Array[0], $Date_Array[2] );
   $w = strftime ( "%w", $indate );

   switch ( $w ) {
      case 0: return $this->LOCALdayNameArrayShort[7];
      case 1: return $this->LOCALdayNameArrayShort[1];
      case 2: return $this->LOCALdayNameArrayShort[2];
      case 3: return $this->LOCALdayNameArrayShort[3];
      case 4: return $this->LOCALdayNameArrayShort[4];
      case 5: return $this->LOCALdayNameArrayShort[5];
      case 6: return $this->LOCALdayNameArrayShort[6];
   }
   return "Αγνωστη($w)";
}

function get_weekday_name2 ( $DateEntry ) {
   $DateEntry = trim($DateEntry);

   if (strpos($DateEntry,'/')) {
      $Date_Array = explode('/',$DateEntry);
   } elseif (strpos ($DateEntry,'-')) {
      $Date_Array = explode('-',$DateEntry);
   } elseif (strlen($DateEntry)==6) {
      $Date_Array[0]= substr($DateEntry,0,2);
      $Date_Array[1]= substr($DateEntry,2,2);
      $Date_Array[2]= substr($DateEntry,4,2);
   } elseif (strlen($DateEntry)==8) {
      $Date_Array[0]= substr($DateEntry,0,4);
      $Date_Array[1]= substr($DateEntry,4,2);
      $Date_Array[2]= substr($DateEntry,6,2);
   }
   date_default_timezone_set('UTC');
   $Date_Array[2].$Date_Array[1].$Date_Array[0];
   $indate = mktime ( 3, 0, 0, $Date_Array[1], $Date_Array[0], $Date_Array[2] );
   $w = strftime ( "%w", $indate );

   switch ( $w ) {
      case 0: return "Κυριακή";
      case 1: return "Δευτέρα";
      case 2: return "Τρίτη";
      case 3: return "Τετάρτη";
      case 4: return "Πέμπτη";
      case 5: return "Παρασκευή";
      case 6: return "Σάββατο";
   }
   return "Αγνωστη($w)";
}

function get_weekday_number ( $DateEntry ) {

   $DateEntry = trim($DateEntry);

   if (strpos($DateEntry,'/')) {
      $Date_Array = explode('/',$DateEntry);
   } elseif (strpos ($DateEntry,'-')) {
      $Date_Array = explode('-',$DateEntry);
   } elseif (strlen($DateEntry)==6) {
      $Date_Array[0]= substr($DateEntry,0,2);
      $Date_Array[1]= substr($DateEntry,2,2);
      $Date_Array[2]= substr($DateEntry,4,2);
   } elseif (strlen($DateEntry)==8) {
      $Date_Array[0]= substr($DateEntry,0,4);
      $Date_Array[1]= substr($DateEntry,4,2);
      $Date_Array[2]= substr($DateEntry,6,2);
   }

   $Date_Array[2].$Date_Array[1].$Date_Array[0];
   $indate = mktime ( 3, 0, 0, $Date_Array[1], $Date_Array[0], $Date_Array[2] );
   $w = strftime ( "%w", $indate );

        return $w;

}


Function DateAdd ($DateToAddTo,$PeriodString,$NumberPeriods){

   $Date_array = explode('/', trim($DateToAddTo));
   if ($_SESSION['DefaultDateFormat']=='d/m/Y'){

      switch ($PeriodString) {
      case 'd': //Days
         return adodb_date($_SESSION['DefaultDateFormat'],adodb_mktime(0,0,0, (int)$Date_array[1],(int)$Date_array[0]+$NumberPeriods ,(int)$Date_array[2]));
         break;
      case 'w': //weeks
         return adodb_date($_SESSION['DefaultDateFormat'],adodb_mktime(0,0,0, (int)$Date_array[1],(int)$Date_array[0]+($NumberPeriods*7),(int)$Date_array[2]));
         break;
      case 'm': //months
         return adodb_date($_SESSION['DefaultDateFormat'],adodb_mktime(0,0,0, (int)$Date_array[1]+$NumberPeriods,(int)$Date_array[0],(int)$Date_array[2]));
         break;
      case 'y': //years
         return adodb_date($_SESSION['DefaultDateFormat'],adodb_mktime(0,0,0, (int)$Date_array[1],(int)$Date_array[0],(int)$Date_array[2]+$NumberPeriods));
         break;
      default:
         Return 0;
      }
   } elseif ($_SESSION['DefaultDateFormat']=='m/d/Y'){

      switch ($PeriodString) {
      case 'd':
         return adodb_date($_SESSION['DefaultDateFormat'],adodb_mktime(0,0,0, (int)$Date_array[0],(int)$Date_array[1]+$NumberPeriods,(int)$Date_array[2]));
         break;
      case 'w':
         return adodb_date($_SESSION['DefaultDateFormat'],adodb_mktime(0,0,0, (int)$Date_array[0],(int)$Date_array[1]+($NumberPeriods*7),(int)$Date_array[2]));
         break;
      case 'm':
         return adodb_date($_SESSION['DefaultDateFormat'],adodb_mktime(0,0,0, (int)$Date_array[0]+$NumberPeriods,(int)$Date_array[1],(int)$Date_array[2]));
         break;
      case 'y':
         return adodb_date($_SESSION['DefaultDateFormat'],adodb_mktime(0,0,0, (int)$Date_array[0],(int)$Date_array[1],(int)$Date_array[2]+$NumberPeriods));
         break;
      default:
         Return 0;
      }
   }
}

function date_diff_exact($d1, $d2){
   //$d1 = (is_string($d1) ? strtotime($d1) : $d1);
   //$d2 = (is_string($d2) ? strtotime($d2) : $d2);

   $Date1 = trim($d1);
   $Date2 = trim($d2);
   $Date1_array = explode('/', $d1);
   $Date2_array = explode('/', $d2);

   $d1 = adodb_mktime(0,0,0, (int)$Date1_array[1],(int)$Date1_array[0],(int)$Date1_array[2]);
   $d2 = adodb_mktime(0,0,0, (int)$Date2_array[1],(int)$Date2_array[0],(int)$Date2_array[2]);

   $diff_secs = abs($d1 - $d2);
   $base_year = min(date("Y", $d1), date("Y", $d2));

   $diff = mktime(0, 0, $diff_secs, 1, 1, $base_year);
   return array(
           "years" => date("Y", $diff) - $base_year,
           "months_total" => (date("Y", $diff) - $base_year) * 12 + date("n", $diff) - 1,
           "months" => date("n", $diff) - 1,
           "days_total" => floor($diff_secs / (3600 * 24)),
           "days" => date("j", $diff) - 1,
           "hours_total" => floor($diff_secs / 3600),
           "hours" => date("G", $diff),
           "minutes_total" => floor($diff_secs / 60),
           "minutes" => (int) date("i", $diff),
           "seconds_total" => $diff_secs,
           "seconds" => (int) date("s", $diff)
   );
}

function DateDiff ($Date1, $Date2, $Period) {

/* expects dates in the format specified in $_SESSION['DefaultDateFormat'] - period can be one of 'd','w','y','m'
months are assumed to be 30 days and years 365.25 days This only works
provided that both dates are after 1970. Also only works for dates up to the year 2035 ish */

   $Date1 = trim($Date1);
   $Date2 = trim($Date2);
   $Date1_array = explode('/', $Date1);
   $Date2_array = explode('/', $Date2);

   if ($_SESSION['DefaultDateFormat']=='d/m/Y'){
      $Date1_Stamp = adodb_mktime(0,0,0, (int)$Date1_array[1],(int)$Date1_array[0],(int)$Date1_array[2]);
      $Date2_Stamp = adodb_mktime(0,0,0, (int)$Date2_array[1],(int)$Date2_array[0],(int)$Date2_array[2]);
   } elseif ($_SESSION['DefaultDateFormat']=='m/d/Y'){
      $Date1_Stamp = adodb_mktime(0,0,0, (int)$Date1_array[0],(int)$Date1_array[1],(int)$Date1_array[2]);
      $Date2_Stamp = adodb_mktime(0,0,0, (int)$Date2_array[0],(int)$Date2_array[1],(int)$Date2_array[2]);
   }
   $Difference = $Date1_Stamp - $Date2_Stamp;

/* Difference is the number of seconds between each date negative if Date 2 > Date 1 */

   switch ($Period) {
   case 'd':
      Return (int) ($Difference/(24*60*60));
      break;
   case 'w':
      Return (int) ($Difference/(24*60*60*7));
      break;
   case 'm':
      Return (int) ($Difference/(24*60*60*30));
      break;
   case 's':
      Return $Difference;
      break;
   case 'y':
      Return (int) ($Difference/(24*60*60*365.25));
      break;
   default:
      Return 0;
   }

}

function YearsMonthsDaysNew($Date1, $Date2) {


   //$years =  (int) (DateDiff($Date1,$Date2,'m')/12);
   //$months = (int) (DateDiff($Date1,$Date2,'m')-12*$years);
   //$days = (int) (DateDiff($Date1,$Date2,'d')-365.25*$years-30*$months);
   //$tmpdate = DateAdd($Date2,'y',$years);
   //$tmpdate = DateAdd(adodb_date('d/m/Y',$tmpdate),'m',$months);
   //$days = DateDiff($Date1,$tmpdate,'d');

   $res = getAge($Date2,$Date1);

   $years=$res[2];
   $months = $res[0];
   $days = $res[1];

   return array('y'=>$years,'m'=>$months,'d'=>$days);
}

function DateDiffArray($myDate1, $myDate2)
{
   // Initial declarations for clarity.
   list($dayFrom, $monFrom, $yearFrom) = $myDate1;
   list($dayTo, $monTo, $yearTo) = $myDate2;

   // Is $dateFrom before $dateTo?
   $swap = 0;
   if($yearFrom > $yearTo || ($yearFrom == $yearTo && ($monFrom > $monTo || ($monFrom == $monTo && $dayFrom > $dayTo))))
   {
      // Swap the dates.
      $swap = 1;
      list($myDate1, $myDate2) = array($myDate2, $myDate1);
      list($monFrom, $dayFrom, $yearFrom) = $myDate1;
      list($monTo, $dayTo, $yearTo) = $myDate2;
   }

   // How many years (leap and non-leap) occur during the timeframe?
   $fullYears = $yearTo - $yearFrom - 1;

   // How many leap years occur during the timeframe?
   $fullLeapYears = getFullLeapYears($yearFrom, $yearTo);

   // Subtract the number of fullLeapYears from the number of fullYears.
   // $fullYears = the number of non-leap years during the timeframe.
   // $fullLeapYears = the number of leap years during the timeframe.
   $fullYears -= $fullLeapYears;

   // Calculate the number of months left over.
   if($yearFrom != $yearTo)
   {
      // Count the number of months between $monFrom and $monTo.
      if($monFrom < $monTo)
      {
         $fullMonths = $monTo - $monFrom - 1;
      }
      // Comepensate for the 12 month wrap-around.
      else
      {
         // This is the same thing as 12 - ($monFrom - $monTo) - 1.
         $fullMonths = 12 - $monFrom + $monTo - 1;
      }

      // Calculate the number of days left over.
      $fullDays = 0;

      // Count up the number of days in each month from $monFrom + 1 up to December.
      for($i = $monFrom + 1; $i <= 12; $i++)
      {
         $fullDays += getDaysInMonth(array($i, 1, $yearFrom));
      }

      // Count up the number of days in each month from January to $monTo - 1.
      for($i = 1; $i < $monTo; $i++)
      {
         $fullDays += getDaysInMonth(array($i, 1, $yearTo));
      }

      // Add the leftover days between $dayFrom and $dayTo.
      $fullDays += getDaysInMonth(array($monFrom, 1, $yearFrom)) - $dayFrom + $dayTo;
   }
   // $yearFrom and $yearTo are the same.
   else
   {
      $fullDays = 0;

      // Add up the number of days in each month between $monFrom and $monTo (not inclusively).
      for($i = $monFrom + 1; $i < $monTo; $i++)
      {
         $fullDays += getDaysInMonth(array($i, 1, $yearTo));
      }

      // If we are looking at two dates in the same month (of the same year).
      if($monFrom == $monTo)
      {
         // Simply subtract $dayFrom from $dayTo.
         $fullDays += $dayTo - $dayFrom;
      }
      // The months are different.
      else
      {
         // Add the leftover days between $dayFrom and $dayTo.
         $fullDays += getDaysInMonth(array($monFrom, 1, $yearFrom)) - $dayFrom + $dayTo;
      }
   }

   // If $fullYears comes out to be -1 (which happens when they are equal) add one.
   $fullYears += ($fullYears == -1);

      // Finally, we need to determine if the timespan contains the break where
      // the Gregorian calendar was established.
      $gregorianSkip = 0;

      if(
         (
            ($yearFrom == 1582 && $monFrom == 10 && $dayFrom <= 4) ||
               ($yearFrom == 1582 && $monFrom < 10) ||
               ($yearFrom < 1582)
            ) &&
            (
               ($yearTo == 1582 && $monTo == 10 && $dayTo >= 15) ||
               ($yearTo == 1582 && $monTo > 10) ||
               ($yearTo > 1582)
            )
         )
      {
         $gregorianSkip = 10;
      }

   // The return value is a little tricky.
   // The number of days is 365 * years + 366 * leapYears + fullDays.
   // If we swapped the dates originally, throw in a negative sign.
   return pow(-1, $swap) * (365 * $fullYears + 366 * $fullLeapYears + $fullDays - $gregorianSkip);
}

function isLeapYear($year)
{
   $leapYear = 0;

   if($year != 4) // Year 4 was not a leap year!
   {
      if(!($year % 4)) // if $year is divisible by 4
      {
         $leapYear = 1;
         if(!($year % 100) && ($year % 400)) // if $year is divisible by 100 and not divisible by 400
         {
            $leapYear = 0;
         }
      }
   }

   return $leapYear;
}

function getDayFromDate($myDate){
   $Date_Array = explode('/',$myDate);
   $day = $Date_Array[0];
   $month = $Date_Array[1];
   $year = $Date_Array[2];

   if ($day != ""){
      return $day;
   }else{
      return '0';
   }
}

function getMonthFromDate($myDate){
   $Date_Array = explode('/',$myDate);
   $day = $Date_Array[0];
   $month = $Date_Array[1];
   $year = $Date_Array[2];

   if ($month != ""){
      return $month;
   }else{
      return '0';
   }
}

function getYearFromDate($myDate){
   $Date_Array = explode('/',$myDate);
   $day = $Date_Array[0];
   $month = $Date_Array[1];
   $year = $Date_Array[2];

   if ($year != ""){
      return $year;
   }else{
      return '0';
   }
}

//--------------------------------------------------------------//
//  Function: getDaysInMonth                                 //
// Arguments: $myDate                                           //
//  Comments: Given a date, getDaysInMonth determines how many  //
//            days were/are/will be in that particular month of //
//            that particular year.                             //
//--------------------------------------------------------------//
function getDaysInMonth_byMonth($month)
{
   // Initial declarations for clarity.

   //list( $day, $month, $year) = $myDate;

   // Look at the $month variable
   switch($month)
   {
      // February (Accounts for leap years)
      case 2 :
         $days = 28 + isLeapYear($year);
         break;

         // April, June, September, November
      case 4 :
      case 6 :
      case 9 :
      case 11 :
         $days = 30;
         break;
         // January, March, May, July, August, October, December
      default :
         $days = 31;
   }

   return $days;
}


function getDaysInMonth($myDate)
{
   // Initial declarations for clarity.
   $Date_Array = explode('/',$myDate);
   $day = $Date_Array[0];
   $month = $Date_Array[1];
   $year = $Date_Array[2];

   //list( $day, $month, $year) = $myDate;

   // Look at the $month variable
   switch($month)
   {
      // February (Accounts for leap years)
      case 2 :
         $days = 28 + isLeapYear($year);
         break;

      // April, June, September, November
      case 4 :
      case 6 :
      case 9 :
      case 11 :
         $days = 30;
         break;
      // January, March, May, July, August, October, December
      default :
         $days = 31;
   }

   return $days;
}

function getAge($myDate1, $myDate2)
{
   // Initial delcarations for clarity.

   $Date_Array = explode('/',$myDate1);
   $dayFrom = $Date_Array[0];
   $monFrom = $Date_Array[1];
   $yearFrom = $Date_Array[2];

   $Date_Array = explode('/',$myDate2);
   $dayTo = $Date_Array[0];
   $monTo = $Date_Array[1];
   $yearTo = $Date_Array[2];

   //list( $dayFrom,$monFrom, $yearFrom) = $myDate1;
   //list( $dayTo,$monTo, $yearTo) = $myDate2;


   // Is $dateFrom before $dateTo?
   if($yearFrom > $yearTo || ($yearFrom == $yearTo && ($monFrom > $monTo || ($monFrom == $monTo && $dayFrom > $dayTo))))
   {
      // Swap the dates.
      list($myDate1, $myDate2) = array($myDate2, $myDate1);
   }

   // $myTime holds information about months, days and years respectively.
   $myTime = array(0, 0, 0);

   // Set the number of years to the difference between $yearTo and $yearFrom.
   $myTime[2] = $yearTo - $yearFrom;

   // Subtract a year if necessary.
   if($monTo < $monFrom)
   {
      $myTime[2] -= 1;
   }
   else
   {
      if($monTo == $monFrom && $dayTo < $dayFrom)
      {
         $myTime[2] -= 1;

      }
   }

   // Set the number of months to the difference between $monTo and $monFrom.
   $myTime[0] = $monTo - $monFrom;

   // Subtract a month if necessary.
   if($dayTo < $dayFrom)
   {
      $myTime[0] -= 1;

      // Add the leftover days between $dayFrom and $dayTo.
      $myTime[1] = getDaysInMonth(array( 1,$monFrom, $yearTo)) - $dayFrom + $dayTo;

   }
   // If the value of dayFrom comes before the value of $dayTo, find their difference (no need for compensation).
   else
   {
      $myTime[1] = $dayTo - $dayFrom;
   }

   // Compensate for 12 month wrap-around.
   if($myTime[0] < 0)
   {
      $myTime[0] = 12 + $myTime[0];
   }

   // Finally, we need to determine if the timespan contains the break where
   // the Gregorian calendar was established.
   if(
      (
         ($yearFrom == 1582 && $monFrom == 10 && $dayFrom <= 4) ||
         ($yearFrom == 1582 && $monFrom < 10) ||
         ($yearFrom < 1582)
      ) &&
      (
         ($yearTo == 1582 && $monTo == 10 && $dayTo >= 15) ||
         ($yearTo == 1582 && $monTo > 10) ||
         ($yearTo > 1582)
      )
      )
   {
      $myTime[1] -= 10;
   }

   // If, because of the Gregorian calendar skip, the 1 drops below zero,
   // borrow a month and add 31 days (the number of days there would have been
   // in October 1582 if there had not been the change to the Gregorian calendar.
   if($myTime[1] < 0)
   {
      $myTime[0] -= 1;
      $myTime[1] += 31;
   }

   // If the number of months drops below zero, borrow a year and add 12 months.
   if($myTime[0] < 0)
   {
      $myTime[2] -= 1;
      $myTime[0] += 12;
   }

   return $myTime;
}

Function CalcEarliestDispatchDate (){

/*This function will need to be modified depending on the business - many businesses run 24x7
The default assumes no delivery on Sat and Sun*/

   $EarliestDispatch = Mktime();
   If (adodb_date('w',$EarliestDispatch)==0 ){

/*if today is a sunday the dispatch date must be tomorrow (Monday) or after */

      $EarliestDispatch = Mktime(0,0,0,adodb_date('m',$EarliestDispatch),adodb_date('d',$EarliestDispatch)+1,adodb_date('y',$EarliestDispatch));

   } elseIf (adodb_date('w',$EarliestDispatch)==6){

/*if today is a saturday the dispatch date must be Monday or after */

      $EarliestDispatch = Mktime(0,0,0,adodb_date('m',$EarliestDispatch),adodb_date('d',$EarliestDispatch)+2,adodb_date('y',$EarliestDispatch));

   }elseif (adodb_date('H')>$_SESSION['DispatchCutOffTime']) {

/* If the hour is after Dispatch Cut Off Time default dispatch date to tomorrow */

      $EarliestDispatch = Mktime(0,0,0,adodb_date('m'),adodb_date('d')+1,adodb_date('y'));
   }
   Return $EarliestDispatch;
}

Function GetPeriod ($TransDate, &$db) {

/*Gets the period for a transaction from the date entered from the period table -
if the date entered is out of range monthly periods are inserted as necessary and
the correct, newly inserted period returned

Dates are parsed using the DefaultDateFormat string */

   if (strpos($TransDate,'/')){
      $Date_array = explode('/', $TransDate);
   } elseif (strpos($TransDate,'-')){
      $Date_array = explode('-', $TransDate);
   } else {
      echo '<BR>' . _('Dates must be entered in the format') . ' ' . $_SESSION['DefaultDateFormat'];
      exit;
   }

   If ((int)$Date_array[2] <60) {
      $Date_array[2] = '20'. $Date_array[2];
   } elseif ((int)$Date_array[2] >59 AND (int)$Date_array[2] <100) {
      $Date_array[2] = '19'. $Date_array[2];
   }

   if ($_SESSION['DefaultDateFormat']=='d/m/Y'){
      $TransDate = adodb_mktime(0,0,0,$Date_array[1],$Date_array[0],$Date_array[2]);
   } elseif ($_SESSION['DefaultDateFormat']=='m/d/Y'){
      $TransDate = adodb_mktime(0,0,0,$Date_array[0],$Date_array[1],$Date_array[2]);
   }
   $MonthAfterTransDate = Mktime(0,0,0,adodb_date('m',$TransDate)+1,adodb_date('d',$TransDate),adodb_date('Y',$TransDate));

   $GetPrdSQL = "SELECT periodno FROM periods WHERE lastdate_in_period < '" . adodb_date('Y/m/d', $MonthAfterTransDate) . "' AND lastdate_in_period >= '" . adodb_date('Y/m/d', $TransDate) . "'";

   $ErrMsg = _('An error occurred in retrieving the period number');
   $GetPrdResult = DB_query($GetPrdSQL,$db,$ErrMsg);

   if (DB_num_rows($GetPrdResult)==0) {

/*The date entered does not fall between currently defined period ranges.
Need to insert some new periods */

      DB_free_result($GetPrdResult);
      $GetPrdSQL = 'SELECT MAX(lastdate_in_period), MAX(periodno) FROM periods';
      $GetPrdResult = DB_query($GetPrdSQL,$db);
      $myrow = DB_fetch_row($GetPrdResult);

      $Date_array = explode('-', $myrow[0]);

      $LastPeriodEnd = adodb_mktime(0,0,0,$Date_array[1]+1,0,$Date_array[0]);
      $LastPeriodNo = $myrow[1];

      if (DateDiff(adodb_date($_SESSION['DefaultDateFormat'],$TransDate), adodb_date($_SESSION['DefaultDateFormat'], $LastPeriodEnd),'d')>0){

      /*Then the date entered is after the currently defined period */

         While (DateDiff(adodb_date($_SESSION['DefaultDateFormat'], $TransDate), adodb_date($_SESSION['DefaultDateFormat'], $LastPeriodEnd),'d')>0){

/* The date of the last period added is less than the transaction date */

            $MonthOfLastPeriod = adodb_date('m', $LastPeriodEnd);
            If ($MonthOfLastPeriod ==12) {
               $LastPeriodEnd =  Mktime(0,0,0,2,0,adodb_date('Y',$LastPeriodEnd)+1);
            } else {
               $LastPeriodEnd = Mktime(0,0,0,($MonthOfLastPeriod +2),0,adodb_date('Y',$LastPeriodEnd));
            }

            $LastPeriodNo = $LastPeriodNo + 1;

            $GetPrdSQL = 'INSERT INTO periods (periodno, lastdate_in_period) VALUES (' . $LastPeriodNo . ", '" . adodb_date('Y/m/d', $LastPeriodEnd) . "')";
            $ErrMsg = _('An error occurred in adding a new period number');
            $GetPrdResult = DB_query($GetPrdSQL, $db, $ErrMsg);

            $sql = 'INSERT INTO chartdetails (accountcode, period)
                  SELECT chartmaster.accountcode, periods.periodno
                     FROM chartmaster
                        CROSS  JOIN periods
                  WHERE ( chartmaster.accountcode, periods.periodno ) NOT
                     IN ( SELECT chartdetails.accountcode, chartdetails.period FROM chartdetails )';

            $InsNewChartDetails = DB_query($sql,$db,'','','',false); /*dont trap errors - chart details records created only as required - duplicate messages ignored */

         }
         return $LastPeriodNo;

      } else {
      /* then the transactions date must be before periods have been created for need to insert periods before currently defined periods */

         $GetPrdSQL = 'SELECT MIN(lastdate_in_period), MIN(periodno) FROM periods';
         $ErrMsg = _('An error occurred in getting the first period number in the database');
         $GetPrdResult = DB_query($GetPrdSQL,$db);

         $myrow = DB_fetch_row($GetPrdResult);

         $Date_array = explode('-', $myrow[0]);

         $FirstPeriodEnd = adodb_mktime(0,0,0,$Date_array[1]+1,0,$Date_array[0]);
         $FirstPeriodNo = $myrow[1];


         While (DateDiff(adodb_date($_SESSION['DefaultDateFormat'], $TransDate), adodb_date($_SESSION['DefaultDateFormat'], $FirstPeriodEnd), 'd')<0){

         /* The date of the first period is after the transaction date */

            $FirstPeriodEnd = Mktime(0,0,0, adodb_date('m',$FirstPeriodEnd), 0, adodb_date('Y',$FirstPeriodEnd));

            $FirstPeriodNo = $FirstPeriodNo - 1;
            $GetPrdSQL = 'INSERT INTO periods (periodno, lastdate_in_period) VALUES (' . $FirstPeriodNo . ", '" . adodb_date('Y/m/d', $FirstPeriodEnd) . "')";
            $ErrMsg = _('An error occurred in inserting periods before the first period to accomodate back dated transactions');
            $GetPrdResult = DB_query($GetPrdSQL,$db, $ErrMsg);
            $sql = 'INSERT INTO chartdetails (accountcode, period)
                  SELECT accountcode,' . $FirstPeriodEnd . ' FROM chartmaster';
            $InsNewChartDetails = DB_query($sql,$db,'','','',false);
            /*dont trap errors - chart details records */
         }
         return $FirstPeriodNo + 1 ;
      } /*end of logic for dates before currently defined periods */
   } else {

   /*the date is in a range currently defined by period numbers */
      $myrow = DB_fetch_row ($GetPrdResult);
      return $myrow[0];

   }


} /*end of get period function */

function getOrthodoxEaster($year){
  /*
   Takes any Gregorian date and returns the Gregorian
   date of Orthodox Easter for that year.
  */
  $year = trim($year);
  $r1 = $year % 19;
  $r2 = $year % 4;
  $r3 = $year % 7;
  $ra = 19 * $r1 + 16;
  $r4 = $ra % 30;
  $rb = 2 * $r2 + 4 * $r3 + 6 * $r4;
  $r5 = $rb % 7;
  $rc = $r4 + $r5;
  //Orthodox Easter for this year will fall $rc days after April 3
  //return strtotime("3 April $year + $rc days");
  $mydate= strtotime("3 April $year + $rc days");
  return date('d/m/Y',$mydate);
}

function GetFirstDay($DateEntry){
   $DateEntry =Trim($DateEntry);

   if (strpos($DateEntry,'/')) {
      $Date_Array = explode('/',$DateEntry);
   } elseif (strlen($DateEntry)==6) {
      $Date_Array[0]= substr($DateEntry,0,2);
      $Date_Array[1]= substr($DateEntry,2,2);
      //Χρόνος
      $Date_Array[2]= substr($DateEntry,4,2);
   } elseif (strlen($DateEntry)==8) {
      $Date_Array[0]= substr($DateEntry,0,2);
      $Date_Array[1]= substr($DateEntry,2,2);
      //Χρόνος
      $Date_Array[2]= substr($DateEntry,4,4);
   }
   if ($_SESSION['DefaultDateFormat']=='d/m/Y'){
      Return '01/'.$Date_Array[1].'/'.$Date_Array[2];

   }elseif($_SESSION['DefaultDateFormat']=='m/d/Y'){
      Return $Date_Array[1].'/01/'.$Date_Array[2];
   }

}

function GetLastDay($DateEntry){
   $DateEntry =Trim($DateEntry);

   if (strpos($DateEntry,'/')) {
      $Date_Array = explode('/',$DateEntry);
   } elseif (strlen($DateEntry)==6) {
      $Date_Array[0]= substr($DateEntry,0,2);
      $Date_Array[1]= substr($DateEntry,2,2);
      //Χρόνος
      $Date_Array[2]= substr($DateEntry,4,2);
   } elseif (strlen($DateEntry)==8) {
      $Date_Array[0]= substr($DateEntry,0,2);
      $Date_Array[1]= substr($DateEntry,2,2);
      //Χρόνος
      $Date_Array[2]= substr($DateEntry,4,4);
   }
   if ($_SESSION['DefaultDateFormat']=='d/m/Y'){
      Return getDaysInMonth($DateEntry).'/'.$Date_Array[1].'/'.$Date_Array[2];

   }elseif($_SESSION['DefaultDateFormat']=='m/d/Y'){
      Return $Date_Array[1].'/'.getDaysInMonth($DateEntry).'/'.$Date_Array[2];
   }

}

//Handle Delphi Dates

function mod($number, $div)
{
   return $number - floor($number/$div)*$div;
}

function div($number, $div)
{
   return floor($number/$div);
}

function DelphiDate($DateEntry) // Unix Timestamp
{

   $aPHPTime = 0;

   if ($DateEntry!='') {

      if (strpos($DateEntry,'/')) {
         $Date_Array = explode('/',$DateEntry);
      } elseif (strpos ($DateEntry,'-')) {
         $Date_Array = explode('-',$DateEntry);
      }

      if (strlen($Date_Array[2])>4) {  /*chop off the time stuff */
         $Date_Array[2]= substr($Date_Array[2],0,2);
      }


      if ($_SESSION['DefaultDateFormat']=='d/m/Y'){
         $aPHPTime = mktime(0,0,0,$Date_Array[1],$Date_Array[0],$Date_Array[2]);
      } elseif ($_SESSION['DefaultDateFormat']=='m/d/Y'){
         $aPHPTime = mktime(0,0,0,$Date_Array[0],$Date_Array[1],$Date_Array[2]);
      }

   }

   # The Unix Timestamp holds the number of seconds after January 1 1970 01:00:00
   return div($aPHPTime,86400)+25570;
}

function PHPUnixTimeStamp($aDelphiDate)
{
   # Delphi's TDate holds number of days after December 30 1899
   return ($aDelphiDate-25569)*86400-3600;
}

function month_name($month){
   global   $db;

   switch ($month) {

      case '1':
         $text='Ιανουάριος';
         break;
      case '2':
         $text='Φεβρουάριος';
         break;
      case '3':
         $text='Μάρτιος';
         break;
      case '4':
         $text='Απρίλιος';
         break;
      case '5':
         $text='Μάϊος';
         break;
      case '6':
         $text='Ιούνιος';
         break;
      case '7':
         $text='Ιούλιος';
         break;
      case '8':
         $text='Αύγουστος';
         break;
      case '9':
         $text='Σεπτέμβρης';
         break;
      case '10':
         $text='Οκτώβρης';
         break;
      case '11':
         $text='Νοέμβρης';
         break;
      case '12':
         $text='Δεκέμβρης';
         break;
      default:
         $text='';

   }
   return $text;

}


?>