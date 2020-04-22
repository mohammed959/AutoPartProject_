function showImage(element) {

    var body = document.getElementsByTagName('body')[0];

    body.innerHTML += "<div onclick='removeImage()' id='image-view-container'>"
        + "<img src='" + element.getAttribute('src') + "' />" +
        "</div>";


}

function removeImage() {
    document.getElementById('image-view-container').remove();
}