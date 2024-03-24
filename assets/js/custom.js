
// Preview the post image
var $post_img = document.querySelector("#select_post_img");

$post_img.addEventListener("change", previewImage);

function previewImage() {
    var fileObject = this.files[0];
    var fileReader = new FileReader();

    fileReader.readAsDataURL(fileObject);

    fileReader.onload = function() {
        var image_src = fileReader.result;
        var image = document.querySelector("#post_img");

        image.setAttribute('src', image_src);
        image.setAttribute('style', 'display:');
    }
}