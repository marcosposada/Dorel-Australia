$j(document).ready(function () {
    $j('.category-read-more span').click(function(){

        $j('.category-intro').addClass('opened');
        $j(this).parent().hide();
    });
});