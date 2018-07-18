$(document).ready(function () {

    $('.js-filter-toggle-button').on('click', function (e) {
        $('body').toggleClass('js-body-filters-open');
        $(this).toggleClass('active');
        e.preventDefault();
    });

    $('.js-product-filter-show-result-button').on('click', function (e) {
        $('body').removeClass('js-body-filters-open');
        $('js-filter-toggle-button').removeClass('active');
    });

    $('.js-product-filter-reset-button').on('click', function (e) {
        $('body').removeClass('js-body-filters-open');
        $('js-filter-toggle-button').removeClass('active');
    });

    $('.js-filter-toggle-overlay').on('click', function (e) {
        $('body').removeClass('js-body-filters-open');
        $('js-filter-toggle-button').removeClass('active');
    });

});
