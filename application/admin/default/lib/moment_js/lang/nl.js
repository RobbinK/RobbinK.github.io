/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: nl.js
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:56
##########################################################
 */
(function(){var a={months:"januari_februari_maart_april_mei_juni_juli_augustus_september_oktober_november_december".split("_"),monthsShort:"jan._feb._mar._apr._mei._jun._jul._aug._sep._okt._nov._dec.".split("_"),weekdays:"zondag_maandag_dinsdag_woensdag_donderdag_vrijdag_zaterdag".split("_"),weekdaysShort:"zo._ma._di._wo._do._vr._za.".split("_"),longDateFormat:{LT:"HH:mm",L:"DD-MM-YYYY",LL:"D MMMM YYYY",LLL:"D MMMM YYYY LT",LLLL:"dddd D MMMM YYYY LT"},meridiem:{AM:"AM",am:"am",PM:"PM",pm:"pm"},calendar:{sameDay:"[Vandaag om] LT",nextDay:"[Morgen om] LT",nextWeek:"dddd [om] LT",lastDay:"[Gisteren om] LT",lastWeek:"[afgelopen] dddd [om] LT",sameElse:"L"},relativeTime:{future:"over %s",past:"%s geleden",s:"een paar seconden",m:"één minuut",mm:"%d minuten",h:"één uur",hh:"%d uur",d:"één dag",dd:"%d dagen",M:"één maand",MM:"%d maanden",y:"één jaar",yy:"%d jaar"},ordinal:function(a){return a===1||a===8||a>=20?"ste":"de"}};typeof module!="undefined"&&(module.exports=a),typeof window!="undefined"&&this.moment&&this.moment.lang&&this.moment.lang("nl",a)})();