/*
-------------------------------------
------------------------------------- The image changue  every 10 Secons
-------------------------------------
-------------------------------------
*/

var Cont = 0;
function inic(){
if(Cont % 2 != 0){
  document.querySelector('.img_1').style.opacity = '0';
  document.querySelectorAll('.img_1')[1].style.opacity = '0';

setTimeout(function (){
document.querySelector('.img_1').className = 'img_1 img_d_n';
    document.querySelectorAll('.img_1')[1].className = 'img_1 img_d_n';

  document.querySelector('.img_2').className = 'img_2 img_d_b';

  document.querySelectorAll('.img_2')[1].className = 'img_2 img_d_b';
  
},500);  
  setTimeout(function (){
document.querySelector('.img_2').style.opacity = '0.2';  
  document.querySelectorAll('.img_2')[1].style.opacity = '1';
 
  },600);
    Cont++;
}else{

  document.querySelector('.img_2').style.opacity = '0';
  document.querySelectorAll('.img_2')[1].style.opacity = '0';

setTimeout(function (){

  document.querySelector('.img_2').className = 'img_2 img_d_n';
  document.querySelectorAll('.img_2')[1].className = 'img_2 img_d_n';

  document.querySelector('.img_1').className = 'img_1 img_d_b';
  document.querySelectorAll('.img_1')[1].className = 'img_1 img_d_b';

},500);  
  
  setTimeout(function (){
document.querySelector('.img_1').style.opacity = '0.2';
document.querySelectorAll('.img_1')[1].style.opacity = '1';
  },600);  
  Cont++;
}   }

window.onload = function (){  
  inic();  document.querySelector('.cont_text_img').className = "cont_text_img cont_text_img_act";
setTimeout(function(){
  document.querySelector('.cont_img_frond').className = "cont_img_frond  cont_img_frond_active";
},1700);
  
  
}

setInterval(function(){ inic() }, 10000);