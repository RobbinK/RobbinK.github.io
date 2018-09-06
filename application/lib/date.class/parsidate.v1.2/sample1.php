<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: sample1.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:57
##########################################################
 */


include "parsidate.class.php";

################################
//Disc : ساخت شی نمونه کلاس   

$obj = new ParsiDate;
$date=$obj->showDate("Y/m/d");

//################################
////Disc:  ParsiDate روش سریع دسترسی به کلاس
//
//$date=parsidate()->showDate("Y/m/d");
//
//################################
////Disc:  ParsiDate عمليات جمع و کسر از تاريخ
//echo "<br>ex.add() method<br/>";
//echo parsidate()->add('y',-1) // Sal ra +1 mikonad
//                ->add('m',1)  // Mah ra +2 mikonad
//                ->add('d',5)  // Day ra +5 mikonad
//                ->showDate("Y/m/d");
//
//echo '<br/>';
//################################
////Disc:  ParsiDate عمليات جمع و کسر از تاريخ
//echo "<br>ex.setJdate() method<br/>";
//echo parsidate()->setJdate(1320,null,5)
//                ->showDate("Y/m/d");
//
