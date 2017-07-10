// universal analytics

jQuery(function() {
    jQuery(document).foundation();
    jQuery('.sticky li.fancybox').each(function(){
        jQuery(this).removeClass('fancybox');
        var child = jQuery(this).children('a');
        var id = child.attr('href');
        child.addClass('fancybox');
        child.attr('href', 'javascript:;');
        child.attr('data-src', id);
    });
    jQuery('.fancybox').fancybox({
        toolbar : true,
    });

    jQuery('.slick').slick({
        dots: true
    });

    // scroll to top
    jQuery('.scroll-top').on("click", function(e){
        jQuery("html, body").animate({ scrollTop: 0 }, "slow");
        e.preventDefault();
        return false;
    });
});