<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: pipn.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:57
##########################################################
 */
 

include_once('paypal.inc.php');
$paypal=new paypal();

// optionally enable logging
// $paypal->log=1;
// $paypal->logfile='/absolute/path/to/logfile.txt';

//if you are dealing with subscriptions this must be called first
$paypal->ignore_type=array('subscr_signup');

if($paypal->validate_ipn())
{

    if($paypal->payment_success==1)
    {
        //payment is successfull
        //use the item id to identify for which product the payment was made 
        $id=intval($paypal->posted_data['item_number']);
    }
    else
    {
        //payment not successful and/or subcsription cancelled
    }
}
else
{
//not valid PIPN  log

}