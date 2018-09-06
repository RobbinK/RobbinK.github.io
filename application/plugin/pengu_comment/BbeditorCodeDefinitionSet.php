<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: BbeditorCodeDefinitionSet.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:58
##########################################################
 */



require_once 'BbeditorBBcodeValidators/FontValidator.php';

/**
 * Provides a default set of common bbcode definitions.
 *
 * @author jbowens
 */
class BbeditorCodeDefinitionSet implements CodeDefinitionSet {
    /* The default code definitions in this set. */

    protected $definitions = array();

    /**
     * Constructs the default code definitions.
     */
    public function __construct() {
        $fontValidator = new FontValidator();
        /* [size] size tag */
        $builder = new CodeDefinitionBuilder('size', '<font size="{option}">{param}</font>');
        $builder->setUseOption(true);
        array_push($this->definitions, $builder->build());

        $builder = new CodeDefinitionBuilder('font', '<font face="{option}">{param}</font>');
        $builder->setUseOption(true);
        array_push($this->definitions, $builder->build());
        
        /* [ul] ul tag */
        $builder = new CodeDefinitionBuilder('ul', '<ul>{param}</ul>');
        array_push($this->definitions, $builder->build());
        
        
        /* [ol] ol tag */
        $builder = new CodeDefinitionBuilder('ol', '<ol>{param}</ol>');
        array_push($this->definitions, $builder->build());
        
        /* [li] li tag */
        $builder = new CodeDefinitionBuilder('li', '<li>{param}</li>');
        array_push($this->definitions, $builder->build());
        
        /* [code] code tag */
        $builder = new CodeDefinitionBuilder('code', '<code>{param}</code>');
        array_push($this->definitions, $builder->build());
        
        /* [quote] blockquote tag */
        $builder = new CodeDefinitionBuilder('quote', '<blockquote>{param}</blockquote>');
        array_push($this->definitions, $builder->build());
    }

    /**
     * Returns an array of the default code definitions.
     */
    public function getCodeDefinitions() {
        return $this->definitions;
    }

}

