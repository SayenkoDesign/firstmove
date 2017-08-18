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
        beforeClose : function( instance, current, e ) {
        }
    });

    jQuery('.slick').slick({
        dots: true,
    });

    // scroll to top
    jQuery('.scroll-top').on("click", function(e){
        jQuery("html, body").animate({ scrollTop: 0 }, "slow");
        e.preventDefault();
        return false;
    });

    // scroll to
    function getParameterByName(name, url) {
        if (!url) url = window.location.href;
        name = name.replace(/[\[\]]/g, "\\$&");
        var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
            results = regex.exec(url);
        if (!results) return null;
        if (!results[2]) return '';
        return decodeURIComponent(results[2].replace(/\+/g, " "));
    }
    jQuery('.sticky-container .submenu a').click(function(e){
        var scrollto = getParameterByName('scrollTo', jQuery(this).attr("href"));
        var href = jQuery(this).attr("href");
        href = href.substring(0, href.indexOf('?'));
        if(href == window.location.pathname) {
            jQuery('html, body').animate({
                scrollTop: jQuery('#' + scrollto).offset().top - 200
            }, 2000);
            if(jQuery('#small-menu').is(':visible')) {
                jQuery('.sticky .menu-icon').click();
            }
            e.preventDefault();
            return false;
        }
    });
    if(getParameterByName('scrollTo')) {
        jQuery('html, body').animate({
            scrollTop: jQuery("#" + getParameterByName('scrollTo')).offset().top - 200
        }, 2000);
    }

    // jQuery toggle transcript
    jQuery('.transcript.button').on("click", function(){
        console.log("toggle transcript");
        jQuery(this).parent().next().stop(true, true).slideToggle();
    });
});