$(function () {
   /* var container = $('.masonery-container');
    container.masonry({
        percentPosition: true,
        containerStyle: null,
        // options...
        itemSelector: '.mansonery-item',
        columnWidth: '.mansonery-item',
        isAnimated: true
    });*/

    savvior.init("#masonery-container", {
        "screen and (max-width: 20em)": { columns: 2 },
        "screen and (min-width: 20em) and (max-width: 40em)": { columns: 3 },
        "screen and (min-width: 40em)": { columns: 3 },
    });
});
