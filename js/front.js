$(function() {

    lightbox();
    sticky();
    utils();
});

function prefersReducedMotion() {
    if (!window.matchMedia) {
        return false;
    }
    return window.matchMedia('(prefers-reduced-motion: reduce)').matches;
}

/* =========================================
 *  lightbox
 *  =======================================*/

function lightbox() {

    $(document).delegate('*[data-toggle="lightbox"]', 'click', function(event) {
        event.preventDefault();

        var reduceMotion = prefersReducedMotion();

        $(this).ekkoLightbox({
            onShow: function() {
                if (reduceMotion && this.modal) {
                    this.modal.removeClass('fade');
                }
            },
            onShown: function() {
                if (reduceMotion) {
                    $('.modal-backdrop').removeClass('fade');
                }
            }
        });
    });
}

/* =========================================
 *  sticky header 
 *  =======================================*/

function sticky() {

    $(".header").sticky();

}

function utils() {

    var reduceMotion = prefersReducedMotion();

    function updateUrlHash(hash) {
        if (!hash) {
            return;
        }

        if (window.history && typeof window.history.pushState === 'function') {
            window.history.pushState(null, '', '#' + hash);
            return;
        }

        window.location.hash = hash;
    }

    /* navbar toggle a11y (aria-expanded sync) */

    var $navToggle = $('.navbar-toggle');
    var $navMenu = $('#navigation');

    function updateNavA11yState() {
        if (!$navToggle.length || !$navMenu.length) {
            return;
        }

        var isMobile = $navToggle.is(':visible');
        var isExpanded = $navMenu.hasClass('in');
        var $navLinks = $navMenu.find('a');

        // On desktop, the menu isn't actually collapsed, so don't hide it from AT.
        if (!isMobile) {
            $navMenu.removeAttr('aria-hidden');
            $navLinks.removeAttr('tabindex');
            return;
        }

        $navToggle.attr('aria-expanded', isExpanded ? 'true' : 'false');
        $navMenu.attr('aria-hidden', isExpanded ? 'false' : 'true');

        // When collapsed on mobile, remove links from tab order.
        if (isExpanded) {
            $navLinks.removeAttr('tabindex');
        } else {
            $navLinks.attr('tabindex', '-1');
        }
    }

    if ($navToggle.length) {
        $navToggle.attr({
            'aria-controls': 'navigation',
            'aria-expanded': 'false'
        });

        updateNavA11yState();
        $(window).on('resize', updateNavA11yState);

        $navMenu.on('shown.bs.collapse', function() {
            updateNavA11yState();

            // If the user opened the menu from the toggle (keyboard), move focus into the menu.
            if (document.activeElement === $navToggle.get(0)) {
                var $firstLink = $navMenu.find('a:visible').first();
                if ($firstLink.length) {
                    $firstLink.focus();
                }
            }
        });

        $navMenu.on('hidden.bs.collapse', function() {
            // If focus was inside the menu, return it to the toggle.
            var activeEl = document.activeElement;
            if (activeEl && $.contains($navMenu.get(0), activeEl)) {
                $navToggle.focus();
            }

            updateNavA11yState();
        });

        // Escape closes the mobile nav
        $(document).on('keydown', function(e) {
            if (e.key === 'Escape' || e.keyCode === 27) {
                if ($navToggle.is(':visible') && $navMenu.hasClass('in')) {
                    $navMenu.collapse('hide');
                }
            }
        });
    }

    /* skip link focus */

    $('a[href="#main-content"]').on('click', function() {
        window.setTimeout(function() {
            var $target = $('#main-content');
            if ($target.length) {
                $target.focus();
            }
        }, 0);
    });

    /* tooltips */

    $('[data-toggle="tooltip"]').tooltip({
        animation: !reduceMotion
    });

    /* click on the box activates the radio */

    $('#checkout').on('click', '.box.shipping-method, .box.payment-method', function(e) {
        var radio = $(this).find(':radio');
        radio.prop('checked', true);
    });
    /* click on the box activates the link in it */

    $('.box.clickable').on('click', function(e) {

        window.location = $(this).find('a').attr('href');
    });
    /* external links in new window*/

    $('.external').on('click', function(e) {

        e.preventDefault();
        window.open($(this).attr("href"));
    });
    /* animated scrolling */

    /* animated scrolling */

    $('.scroll-to, #navigation a').click(function(event) {
        event.preventDefault();
        var full_url = this.href;
        var parts = full_url.split("#");
        var trgt = parts[1];

        if (reduceMotion) {
            updateUrlHash(trgt);
            return;
        }

        $('body').scrollTo($('#' + trgt), 800, {
            offset: -40,
            onAfter: function() {
                updateUrlHash(trgt);
            }
        });

        if ($navToggle.length && $navToggle.is(':visible')) {
            $('#navigation').collapse('hide');
        }

    });

}

$.fn.alignElementsSameHeight = function() {
    $('.same-height-row').each(function() {

        var maxHeight = 0;
        var children = $(this).find('.same-height');
        children.height('auto');
        if ($(window).width() > 768) {
            children.each(function() {
                if ($(this).innerHeight() > maxHeight) {
                    maxHeight = $(this).innerHeight();
                }
            });
            children.innerHeight(maxHeight);
        }

        maxHeight = 0;
        children = $(this).find('.same-height-always');
        children.height('auto');
        children.each(function() {
            if ($(this).innerHeight() > maxHeight) {
                maxHeight = $(this).innerHeight();
            }
        });
        children.innerHeight(maxHeight);
    });
}

$(window).load(function() {

    windowWidth = $(window).width();
    windowHeight = $(window).height();

    $(this).alignElementsSameHeight();

});
$(window).resize(function() {

    newWindowWidth = $(window).width();
    newWindowHeight = $(window).height();

    if (windowWidth !== newWindowWidth) {
        setTimeout(function() {
            $(this).alignElementsSameHeight();
        }, 100);
        windowWidth = newWindowWidth;
        windowHeight = newWindowHeight;
    }

});