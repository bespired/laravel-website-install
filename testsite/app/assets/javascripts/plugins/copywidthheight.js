$(document).ready(function() {
    $('[data-copywidth]').each(function() {
        $(this).height($(this).width());
    });
    $('[data-copyheight]').each(function() {
        $(this).width($(this).height());
    });
    $('[data-watchheight]').each( function () {
        var max = {};
        var $heightwatchers = $(this).find('[data-watchheight-watch]');
        $heightwatchers.each( function () {
            var attr=$(this).attr('data-watchheight-watch');
            if ((typeof(attr) === 'undefined')||(attr === '')) {
                attr = 'undefined';
            }
            console.log(attr)
            var h = $(this).outerHeight();
            if ((max[attr] < h)||(typeof(max[attr]) === 'undefined')) {
                max[attr] = h;
                console.log(h)
            }
        });
        for (var tel in max) {
            console.log('found:'+tel);
            if (tel === 'undefined') {
                $heightwatchers.filter('[data-watchheight-watch=\'\']').css('height',max[tel]);
            } else {
                $heightwatchers.filter('[data-watchheight-watch='+tel+']').css('height',max[tel]);
            }
        }
        
    });
});