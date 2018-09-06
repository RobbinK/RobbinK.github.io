<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: CodeDefinitionSet.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:58
##########################################################
 */



require_once 'CodeDefinition.php';

/**
 * An interface for sets of code definitons.
 *
 * @author jbowens
 */
interface CodeDefinitionSet
{

    /**
     * Retrieves the CodeDefinitions within this set as an array.
     */
    public function getCodeDefinitions();

}
