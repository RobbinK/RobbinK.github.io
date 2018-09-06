<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: tree.class.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:57
##########################################################
 */


class tree {

    private $id_field = 'id';
    private $parent_id_field = 'parentid';
    public $children_field = 'children';

    public function set_IdKey($key) {
        $this->id_field = $key;
        return $this;
    }

    public function set_parentKey($key) {
        $this->parent_id_field = $key;
        return $this;
    }

    public function set_childrenKey($key) {
        $this->children_field = $key;
        return $this;
    }

    public function mapToTree($inputArray, $root = 0, $nested = true) {
        $tree = array($root => array());
        if (!is_array($inputArray))
            return false;
        foreach ($inputArray as &$row)
            $tree[$row[$this->id_field]] = &$row;
        foreach ($inputArray as &$row)
            if (isset($tree[$row[$this->parent_id_field]]))
                $tree[$row[$this->parent_id_field]][$this->children_field][] = &$row;
        if (!isset($tree[$root][$this->id_field]))
            $tree = $tree[$root][$this->children_field];

        if ($nested == false)
            return $this->treeToMap(array(0 => $tree[$root]));

        return array(0 => $tree[$root]);
    }

    private function treeToMap($tree) {
        static $map = array();
        foreach ($tree as &$t) {
            if (isset($t[$this->children_field]) && is_array($t[$this->children_field])) {
                $children = $t[$this->children_field];
                unset($t[$this->children_field]);
            }
            $map[] = $t;
            if (isset($children))
                $this->treeToMap($children);
        }
        return $map;
    }

    public function treeIndentor(&$tree, $indentChar = '- ') {

        $out = array();
        if (is_array($indentChar))
            $mainIndent = $indentChar[0];
        else {
            $mainIndent = $indentChar; 
        }

        foreach ($tree as &$t) {
            if (is_array($indentChar))
                $t['indent'] = join($indentChar);
            else
                $t['indent'] = $indentChar;
            $out[] = array_filter($t, create_function('$v', 'return !is_array($v);'));

            if (@is_array($t[$this->children_field])) {

                if (is_array($indentChar))
                    $newIndentChar = array_merge($indentChar, array($mainIndent));
                else
                    $newIndentChar = array($mainIndent, $mainIndent);

                $out = array_merge($out, $this->treeindentor($t[$this->children_field], $newIndentChar));
            }
        }
        return $out;
    }

    public function treeBullist($tree, $format) {
        static $space;
        static $past_first_tag=false;
        if (!$past_first_tag)
        {
            $past_first_tag=true;
            $out = "\n" . "<ul>"; //first  Tag
        }
        else
            $out = "\n" . $space . "<ul>";
        $child = null;

        foreach ($tree as $t) {

            ###################
            //-- Replace key By Format

            $arr = array_filter($t, create_function('$v', 'return !is_array($v);'));

            $keys = array_keys($arr);
            $vals = array_values($arr);
            array_walk($keys, create_function('&$v,&$k', '$v="[".$v."]";'));
            $arr = array_combine($keys, $vals);
                     
            ###################
            $child.="\n" . $space . "<li>" . strtr($format, $arr) . "</li>";

            if (isset($t[$this->children_field])) {
                $space.="\t";
                $child.= "\n" . $space . "<li>";
                $child.= $this->treeBullist($t[$this->children_field], $format);
                $child.= "\n" . $space . "</li>";
            }
        }
        $out .=$child . "\n" . $space . "</ul>";
        $space = substr($space, 0, strlen($space) - 1);
        return $out;
    }

}