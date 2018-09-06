<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: ab_jdatatable_uclass.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:58
##########################################################
 */


class ab_jdatatableUclass {

    private $model,
            $aColumns,
            $indexColumn,
            $iFilteredTotal,
            $iTotal;

    function __construct($model) {
        $this->model = $model;
        $this->iTotal = $this->model->getcount();
    }

    private function setIndexColumn($index) {
        $this->indexColumn = $index;
    }

    function setColumns($arrayColumns) {
        if (is_array($arrayColumns) && !empty($arrayColumns)) {
            $this->aColumns = $arrayColumns;
            return true;
        }
        return false;
    }

    function getColumns($index = -1) {
        if ($index > -1 && isset($this->aColumns[$index]))
            return $this->aColumns[$index];
        else if (!empty($this->aColumns))
            return $this->aColumns;
    }

    function preparePagin() {
        if (isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1') {
            $this->model->limit($_GET['iDisplayStart'], $_GET['iDisplayLength']);
            return array($_GET['iDisplayStart'], $_GET['iDisplayLength']);
        }
    }

    function prepareOrdering() {
        // Ordering
        $sOrder = '';
        if (isset($_GET['iSortCol_0'])) {
            for ($i = 0; $i < intval($_GET['iSortingCols']); $i++) {
                if ($_GET['bSortable_' . intval($_GET['iSortCol_' . $i])] == "true") {
                    $sOrder .= $this->aColumns[intval($_GET['iSortCol_' . $i])] . " " . input::safe($_GET['sSortDir_' . $i]) . ", ";
                }
            }
            $sOrder = substr_replace($sOrder, '', -2);
        }
        $this->model->orderby($sOrder);
    }

    function prepareFiltering() {
        // Filtering
        // NOTE this does not match the built-in DataTables filtering which does it word by word on any field.
        // It's possible to do here, but concerned about efficiency on very large tables, and MySQL's regex functionality is very limited
        $sWhere = "";
        if ((isset($_GET['sSearch']) ) && ($_GET['sSearch'] != "")) {
            $sWhere = "(";
            $aWords = preg_split('/\s+/', $_GET['sSearch']);
            for ($j = 0; $j < count($aWords); $j++) {
                if ($aWords[$j] != "") {
                    $sWhere .= "(";
                    for ($i = 0; $i < count($this->aColumns); $i++) {
                        $sWhere .= $this->aColumns[$i] . " LIKE '%" . input::safe($aWords[$j]) . "%' OR ";
                    }
                    $sWhere = substr_replace($sWhere, "", -3);
                    $sWhere .= ") AND ";
                }
            }

            $sWhere = substr_replace($sWhere, "", -5);
            $sWhere .= ")";
        }

        $this->model->where($sWhere,null, false);
        $this->iFilteredTotal = $this->model->getcount();
    }

    function process() {
        $type=null;
        if (DB_CONNECTION_TYPE == 'mysql')
            $type = MYSQL_NUM;
        else if (DB_CONNECTION_TYPE == 'mysqli')
            $type = MYSQLI_NUM;
        else if (DB_CONNECTION_TYPE == 'pdo')
            $type = PDO::FETCH_NUM;
        $this->data = $this->model->setrestype($type)->exec()->allrows();
    }

    function getData() {

        return $this->data;
    }

    function setData($data) {
        $this->data = $data;
    }

    function renderOutput() {
        // Output
        $output = array(
            "sEcho" => intval((isset($_GET['sEcho']) ? $_GET['sEcho'] : 0)),
            "iTotalRecords" => $this->iTotal,
            "iTotalDisplayRecords" => $this->iFilteredTotal,
            "aaData" => $this->data
        );
        return json_encode($output);
    }

}
