function turnOnMobileMenu(){
    var menu = document.getElementById("menu");
    var menu2 = document.getElementsByClassName("nav");

    var atts = menu.attributes;

    //menu.setAttribute("style", "display: block;");
    menu.setAttribute("style", "visibility: visible;");

    console.log(menu);
    console.log(menu2);
    console.log(atts);

}



