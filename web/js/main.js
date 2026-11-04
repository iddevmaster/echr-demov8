/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function () {
    if ($.pjax) {
//        alert('set timeout');
        $.pjax.defaults.timeout = 5000;
    }
    $.fn.modal.Constructor.prototype.enforceFocus = function () {};
    $(document).on('click', '.btn-grid-reload', function (e) {
        e.preventDefault();
        $.pjax.reload({container: $(this).data('pjax-container')});
    });

    $('#ajaxCrudModal').on('submit', 'form', function (e) {
        e.preventDefault();
        $('#ajaxCrudModal button[type="submit"]').click();
    });
    
    $(document).on('pjax:error', function(xhr, textStatus, error, options) {
        console.log(error);
//        alert('error');
        xhr.preventDefault();
    });
//    $(document).on('pjax:timeout', function(xhr, options) {
//        alert('timeout');
//        xhr.preventDefault();
//    });
});

function isScrolledIntoView(elem, offsetTop = 0) {
    if ($(elem).length == 0) {
        return false;
    }
    var docViewTop = $(window).scrollTop() + offsetTop;
    var docViewBottom = $(window).scrollTop() + $(window).height();

    var elemTop = $(elem).offset().top;
    var elemBottom = elemTop + $(elem).height();
    //  return ((elemBottom <= docViewBottom) && (elemTop >= docViewTop)); //try it, will only work for text
//    return (docViewBottom >= elemTop && docViewTop <= elemBottom);
    return !(docViewBottom < elemBottom || docViewTop > elemTop);
}