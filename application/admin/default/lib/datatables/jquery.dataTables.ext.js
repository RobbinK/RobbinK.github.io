/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: jquery.dataTables.ext.js
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:55
##########################################################
 */
$(function(){
    $.fn.dataTableExt.oApi.fnStandingRedraw = function(oSettings) {
        if (oSettings.oFeatures.bServerSide === false) {
            var before = oSettings._iDisplayStart;
            oSettings.oApi._fnReDraw(oSettings);
            oSettings._iDisplayStart = before;
            oSettings.oApi._fnCalculateEnd(oSettings);
        }
        oSettings.oApi._fnDraw(oSettings);
    };


    $.fn.dataTableExt.oApi.fnReloadAjax = function(oSettings, sNewSource) {
        if (typeof sNewSource != 'undefined')
            oSettings.sAjaxSource = sNewSource;

        this.fnClearTable(this);
        this.oApi._fnProcessingDisplay(oSettings, true);
        var that = this;

        $.getJSON(oSettings.sAjaxSource, null, function(json) {
            for (var i = 0; i < json.aaData.length; i++) {
                that.oApi._fnAddData(oSettings, json.aaData[i]);
            }

            oSettings.aiDisplay = oSettings.aiDisplayMaster.slice();
            that.fnDraw(that);
            that.oApi._fnProcessingDisplay(oSettings, false);
        });
    }
});