/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: tinymce.gzip.js
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:58
##########################################################
 */
!function(){function n(n,e){function t(){o.parentNode.removeChild(o),o&&(o.onreadystatechange=o.onload=o=null),e()}function i(){"undefined"!=typeof console&&console.log&&console.log("Failed to load: "+n)}var o;o=document.createElement("script"),o.type="text/javascript",o.src=n,"onreadystatechange"in o?o.onreadystatechange=function(){"loaded"==o.readyState&&t()}:o.onload=t,o.onerror=i,(document.getElementsByTagName("head")[0]||document.body).appendChild(o)}function e(n,e,t){function i(n,e){if(e){for(var t=e.length-1;t>=0;t--)d[n+"_"+e[t]]?e.splice(t,1):d[n+"_"+e[t]]=!0;if(e.length)return"&"+n+"s="+e.join(",")}return""}var o="";return o+=i("plugin",e),o+=i("theme",n),o+=i("language",t),o&&(d.core?o+="&core=false":d.core=!0,o=r.baseURL+"/tinymce.gzip.php?js=true"+o),o}function t(n){if("string"==typeof n)return n.split(/[, ]/);var e=[];if(n)for(var i=0;i<n.length;i++)e=e.concat(t(n[i]));return e}function i(){var e=s.shift();if(e)n(e,i);else{for(var t=0;t<l.length;t++)l[t]();l=[],c=!1}}function o(n){var a=[],r=[],d=[];a.push(n.theme||"modern");for(var f=t(n.plugins),g=0;g<f.length;g++)r.push(f[g]);n.language&&d.push(n.language),s.push(e(a,r,d)),l.push(function(){window.tinymce.dom.Event.domLoaded=1,window.tinymce.init!=o&&(u=window.tinymce.init,window.tinymce.init=o),u.call(window.tinymce,n)}),c||(c=!0,i())}function a(){for(var n=document.getElementsByTagName("script"),e=0;e<n.length;e++){var t=n[e].src;if(-1!=t.indexOf("tinymce.gzip.js"))return t.substring(0,t.lastIndexOf("/"))}}var r,c,u,d={},s=[],l=[];r={init:o,baseURL:a(),suffix:".min"},window.tinyMCE_GZ={init:function(n,o){s.push(e(t(n.themes),t(n.plugins),t(n.languages))),l.push(function(){window.tinymce.dom.Event.domLoaded=1,o()}),c||(c=!0,i())}},window.tinymce=window.tinyMCE=r}();