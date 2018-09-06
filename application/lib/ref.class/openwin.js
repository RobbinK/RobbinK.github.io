/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: openwin.js
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:57
##########################################################
 */
var pengu_winobj;
function openwin(url,name,params)
{
    try{
        if (pengu_winobj.closed==false)
            pengu_winobj.close();
    }catch(e){}
    pengu_winobj=window.open(url,name,params);
    pengu_winobj.focus();
}