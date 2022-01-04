
/*
*   Simple Resonsive Menu JS 
*/

(function(){
const srm_menu = document.querySelector('.simple-responsive-menu');
const srm_menubox = document.getElementById('simple-responsive-menu-box');
const srm_menubox_svg_open = document.querySelector('.svg-open');
const srm_menubox_svg_close = document.querySelector('.svg-close');

srm_menu.addEventListener('click', function(e) {
  e.preventDefault();

  console.log('clicked');

  if(srm_menubox.classList.contains('srm_show')) {
    srm_menubox.classList.remove("srm_show");
    srm_menubox_svg_close.classList.add("hide-svg")
    srm_menubox_svg_open.classList.remove("hide-svg")

  } else {
    srm_menubox.classList.add("srm_show");
    srm_menubox_svg_close.classList.remove("hide-svg")
    srm_menubox_svg_open.classList.add("hide-svg")
  }
})

})()
