<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: CssColorValidator.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:58
##########################################################
 */


require_once dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'InputValidator.php';

/**
 * An InputValidator for CSS color values. This is a very rudimentary
 * validator. It will allow a lot of color values that are invalid. However,
 * it shouldn't allow any invalid color values that are also a security
 * concern.
 *
 * @author jbowens
 * @since May 2013
 */
class CssColorValidator implements  InputValidator
{

    /**
     * Returns true if $input uses only valid CSS color value
     * characters.
     *
     * @param $input  the string to validate
     */
    public function validate($input)
    {
        return (bool) preg_match('/^[A-z0-9\-#., ()%]+$/', $input);
    }

}
