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

    // vzato z agáty, je potřeba nějak začlenit do rangeSlider.js .. funkčně to bere hodnoty z inputů, které máme skryté a dává je to do textových elementů, které zobrazuji. Po resetu filtrů by se měli aktualizovat, což se nyní neděje. Další problém je v tom, že se nezobrazuje měna – ta by měla být asi dynamická. Někdy je před číslem, někdy za. Formátování toho čísla taky není ideální. Zatím nechávám takto, ale asi by to chtělo nějakou péči.
    var showSliderPrices = function ( $slider ) {
		var $minimalPriceInput = $ ( '#product_filter_form_minimalPrice' );
		var $maximalPriceInput = $ ( '#product_filter_form_maximalPrice' );
		var minimalPrice = $minimalPriceInput.val ( ) ? $minimalPriceInput.val ( ) : parseInt ( $slider.data ( 'minimal-value' ) );
		var maximalPrice = $maximalPriceInput.val ( ) ? $maximalPriceInput.val ( ) : parseInt ( $slider.data ( 'maximal-value' ) );
		$ ( '.js-homepage-filter-price-from' ) . html ( minimalPrice );
		$ ( '.js-homepage-filter-price-to' ) . html ( maximalPrice );
	};

    showSliderPrices ( $ ( '.js-range-slider' ) );
		$ ( '.js-range-slider' ) . on ( 'mousemove', function ( ) {
			showSliderPrices ( $ ( this ) );
		} );

});
