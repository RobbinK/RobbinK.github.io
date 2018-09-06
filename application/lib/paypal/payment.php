<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: payment.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:57
##########################################################
 */
 

include_once('paypal.inc.php');
$paypal = new paypal();

//optionally disable page caching by browsers
$paypal->headers_nocache(); //should be called before any output

//set the price
$paypal->price='33';

$paypal->ipn='http://www.adwinbids.com/application/pipn.php'; //full web address to IPN script

//enable recurring payment(subscription) for every number of years
$paypal->recurring_year($r);

//OR every number of months
$paypal->recurring_month($r);

//OR every number of days
$paypal->recurring_day($r);

//OR one-time payment
$paypal->enable_payment();

//change currency code
$paypal->add('currency_code','USD');

//your paypal email address
$paypal->add('business','farrokhhayati@gmail.com');

$paypal->add('item_name','Buy gaming traffic at adwinbids');
$paypal->add('item_number','Unique id');
$paypal->add('quantity',1);
$paypal->add('return',HOST_URL);
$paypal->add('cancel_return',HOST_URL);
$paypal->output_form();