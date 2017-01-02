$j(document).ready(function () {
    var hasThumbnails = document.getElementById('itemslider-zoom');

    if (!hasThumbnails) {

        $j('.product-view-image').attr('class', 'grid12-12 product-view-image product-view-image-no-thumb');
        $j('.product-img-column').attr('class', 'product-img-column grid12-5');
    }
});