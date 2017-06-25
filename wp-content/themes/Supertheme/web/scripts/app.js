// universal analytics

jQuery(function() {
    jQuery(document).foundation();
    jQuery('.fancybox').fancybox({
        toolbar : true,
    });
    jQuery('.slick').slick();

    // scroll to top
    jQuery('.scroll-top').on("click", function(e){
        jQuery("html, body").animate({ scrollTop: 0 }, "slow");
        e.preventDefault();
        return false;
    });
});