/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: ru.js
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:56
##########################################################
 */
(function(){var a=[function(a){return a%10===1&&a%100!==11},function(a){return a%10>=2&&a%10<=4&&a%10%1===0&&(a%100<12||a%100>14)},function(a){return a%10===0||a%10>=5&&a%10<=9&&a%10%1===0||a%100>=11&&a%100<=14&&a%100%1===0},function(a){return!0}],b=function(b,c){var d=b.split("_"),e=Math.min(a.length,d.length),f=-1;while(++f<e)if(a[f](c))return d[f];return d[e-1]},c=function(a,c,d){var e={mm:"минута_минуты_минут_минуты",hh:"час_часа_часов_часа",dd:"день_дня_дней_дня",MM:"месяц_месяца_месяцев_месяца",yy:"год_года_лет_года"};return d==="m"?c?"минута":"минуту":a+" "+b(e[d],+a)},d={months:"январь_февраль_март_апрель_май_июнь_июль_август_сентябрь_октябрь_ноябрь_декабрь".split("_"),monthsShort:"янв_фев_мар_апр_май_июн_июл_авг_сен_окт_ноя_дек".split("_"),weekdays:"воскресенье_понедельник_вторник_среда_четверг_пятница_суббота".split("_"),weekdaysShort:"вск_пнд_втр_срд_чтв_птн_суб".split("_"),longDateFormat:{LT:"HH:mm",L:"DD-MM-YYYY",LL:"D MMMM YYYY",LLL:"D MMMM YYYY LT",LLLL:"dddd, D MMMM YYYY LT"},calendar:{sameDay:"[Сегодня в] LT",nextDay:"[Завтра в] LT",lastDay:"[Вчера в] LT",nextWeek:function(){return this.day()===2?"[Во] dddd [в] LT":"[В] dddd [в] LT"},lastWeek:function(){switch(this.day()){case 0:return"[В прошлое] dddd [в] LT";case 1:case 2:case 4:return"[В прошлый] dddd [в] LT";case 3:case 5:case 6:return"[В прошлую] dddd [в] LT"}},sameElse:"L"},relativeTime:{future:"через %s",past:"%s назад",s:"несколько секунд",m:c,mm:c,h:"час",hh:c,d:"день",dd:c,M:"месяц",MM:c,y:"год",yy:c},ordinal:function(a){return"."}};typeof module!="undefined"&&(module.exports=d),typeof window!="undefined"&&this.moment&&this.moment.lang&&this.moment.lang("ru",d)})();