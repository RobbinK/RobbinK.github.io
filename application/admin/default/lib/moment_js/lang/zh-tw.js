/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: zh-tw.js
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:56
##########################################################
 */
(function(){var a={months:"一月_二月_三月_四月_五月_六月_七月_八月_九月_十月_十一月_十二月".split("_"),monthsShort:"1月_2月_3月_4月_5月_6月_7月_8月_9月_10月_11月_12月".split("_"),weekdays:"星期日_星期一_星期二_星期三_星期四_星期五_星期六".split("_"),weekdaysShort:"週日_週一_週二_週三_週四_週五_週六".split("_"),longDateFormat:{LT:"Ah點mm",L:"YYYY年MMMD日",LL:"YYYY年MMMD日",LLL:"YYYY年MMMD日LT",LLLL:"YYYY年MMMD日ddddLT"},meridiem:function(a,b,c){return a<9?"早上":a<11&&b<30?"上午":a<13&&b<30?"中午":a<18?"下午":"晚上"},calendar:{sameDay:"[今天]LT",nextDay:"[明天]LT",nextWeek:"[下]ddddLT",lastDay:"[昨天]LT",lastWeek:"[上]ddddLT",sameElse:"L"},relativeTime:{future:"%s內",past:"%s前",s:"幾秒",m:"一分鐘",mm:"%d分鐘",h:"一小時",hh:"%d小時",d:"一天",dd:"%d天",M:"一個月",MM:"%d個月",y:"一年",yy:"%d年"},ordinal:function(a){return""}};typeof module!="undefined"&&(module.exports=a),typeof window!="undefined"&&this.moment&&this.moment.lang&&this.moment.lang("zh-tw",a)})();