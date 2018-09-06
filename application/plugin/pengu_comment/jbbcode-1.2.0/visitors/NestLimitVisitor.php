<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: NestLimitVisitor.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:58
##########################################################
 */


require_once dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'CodeDefinition.php';
require_once dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'DocumentElement.php';
require_once dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'ElementNode.php';
require_once dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'NodeVisitor.php';
require_once dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'TextNode.php';

/**
 * This visitor is used by the jBBCode core to enforce nest limits after
 * parsing. It traverses the parse graph depth first, removing any subtrees
 * that are nested deeper than an element's code definition allows.
 *
 * @author jbowens
 * @since May 2013
 */
class NestLimitVisitor implements NodeVisitor {
    /* A map from tag name to current depth. */

    protected $depth = array();

    public function visitDocumentElement(DocumentElement $documentElement) {
        foreach ($documentElement->getChildren() as $child) {
            $child->accept($this);
        }
    }

    public function visitTextNode(TextNode $textNode) {
        /* Nothing to do. Text nodes don't have tag names or children. */
    }

    public function visitElementNode(ElementNode $elementNode) {
        $tagName = strtolower($elementNode->getTagName());

        /* Update the current depth for this tag name. */
        if (isset($this->depth[$tagName])) {
            $this->depth[$tagName] ++;
        } else {
            $this->depth[$tagName] = 1;
        }

        /* Check if $elementNode is nested too deeply. */
        if ($elementNode->getCodeDefinition()->getNestLimit() != -1 &&
                $elementNode->getCodeDefinition()->getNestLimit() < $this->depth[$tagName]) {
            /* This element is nested too deeply. We need to remove it and not visit any
             * of its children. */
            $elementNode->getParent()->removeChild($elementNode);
        } else {
            /* This element is not nested too deeply. Visit all of the children. */
            foreach ($elementNode->getChildren() as $child) {
                $child->accept($this);
            }
        }

        /* Now that we're done visiting this node, decrement the depth. */
        $this->depth[$tagName] --;
    }

}
