/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: nb.js
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:56
##########################################################
 */
(function(){var a={months:"januar_februar_mars_april_mai_juni_juli_august_september_oktober_november_desember".split("_"),monthsShort:"jan_feb_mar_apr_mai_jun_jul_aug_sep_okt_nov_des".split("_"),weekdays:"søndag_mandag_tirsdag_onsdag_torsdag_fredag_lørdag".split("_"),weekdaysShort:"søn_man_tir_ons_tor_fre_lør".split("_"),longDateFormat:{LT:"HH:mm",L:"YYYY-MM-DD",LL:"D MMMM YYYY",LLL:"D MMMM YYYY LT",LLLL:"dddd D MMMM YYYY LT"},meridiem:{AM:"AM",am:"am",PM:"PM",pm:"pm"},calendar:{sameDay:"[I dag klokken] LT",nextDay:"[I morgen klokken] LT",nextWeek:"dddd [klokken] LT",lastDay:"[I går klokken] LT",lastWeek:"[Forrige] dddd [klokken] LT",sameElse:"L"},relativeTime:{future:"om %s",past:"for %s siden",s:"noen sekunder",m:"ett minutt",mm:"%d minutter",h:"en time",hh:"%d timer",d:"en dag",dd:"%d dager",M:"en måned",MM:"%d måneder",y:"ett år",yy:"%d år"},ordinal:function(a){return"."}};typeof module!="undefined"&&(module.exports=a),typeof window!="undefined"&&this.moment&&this.moment.lang&&this.moment.lang("nb",a)})();