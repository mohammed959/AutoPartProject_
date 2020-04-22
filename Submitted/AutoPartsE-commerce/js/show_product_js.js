function change_photo(e){
    bigImage = document.getElementById('main-image');
    var preSrc = bigImage.getAttribute('src');
    var newSrc = e.target.getAttribute('src');

    bigImage.setAttribute('src',newSrc);
    e.target.setAttribute('src',preSrc);
    // if (class_name == 'child-image')
        console.log("true");

    // bigImage.setAttribute('src',e.srcElement);
}

function registerEvents() {
    var classname = document.getElementsByClassName("child-image");

    for (var i = 0; i < classname.length; i++) {
        classname[i].addEventListener('click', change_photo, false);
    }

}
window.addEventListener( "load", registerEvents, false );