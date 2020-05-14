import Ajax from 'framework/common/utils/Ajax';
import Register from 'framework/common/utils/Register';
import Timeout from 'framework/common/utils/Timeout';
import windowClose from '../utils/windowClose';
import Window from '../utils/Window';

export default class PickUpPlace {

    constructor ($container, $pickUpPlaceInput) {
        this.$container = $container;
        this.$pickUpPlaceInput = $pickUpPlaceInput;
    }

    onTransportChange (event, $input) {
        const $transportInput = $('#js-window').data('transportInput');
        const isPickUpPlaceTransportType = PickUpPlace.isPickUpPlaceTransportType($input.data('transport-type'));

        if (isPickUpPlaceTransportType && $input.prop('checked') && ($transportInput === undefined || $transportInput[0] !== $input[0])) {
            this.showSearchWindow($input);

            $input.prop('checked', false);
            event.stopImmediatePropagation();
            event.preventDefault();
        }
    };

    showSearchWindow ($selectedTransportInput) {
        const pickUpPlaceInputValue = $('#transport_and_payment_form_pickUpPlace').val();
        const pickUpPlaceValue = (pickUpPlaceInputValue !== '') ? pickUpPlaceInputValue : null;
        const transportType = $selectedTransportInput.data('transport-type');

        Ajax.ajax({
            url: this.$pickUpPlaceInput.data('pick-up-place-search-url'),
            dataType: 'html',
            data: {
                pickUpPlaceId: pickUpPlaceValue,
                transportType: transportType
            },
            success: function (data) {
                const $window = new Window({
                    content: data,
                    cssClass: 'window-popup--standard box-pick-up-place'
                });
                $window.data('transportInput', $selectedTransportInput);
            }
        });
    };

    onSearchAutocompleteInputChange ($autocompleteInput) {
        const $searchContainer = $autocompleteInput.closest('.js-pick-up-place-search');
        const $autocompleteResults = $searchContainer.find('.js-pick-up-place-autocomplete-results');

        this.$container.find('.js-pick-up-place-autocomplete-results-detail').html('');

        Timeout.setTimeoutAndClearPrevious('PickUpPlace.onSearchAutocompleteInputChange', function () {
            $autocompleteResults.show();
            Ajax.ajax({
                url: $searchContainer.data('pick-up-place-autocomplete-url'),
                loaderElement: $autocompleteResults,
                dataType: 'html',
                method: 'post',
                data: {
                    searchQuery: $searchContainer.find('.js-pick-up-place-city-post-code-autocomplete-input').val(),
                    transportType: $('#js-window').data('transportInput').data('transport-type')
                },
                success: function (data) {
                    $autocompleteResults.html(data);
                    Register.registerNewContent($autocompleteResults);

                    $('#js-window').resize();
                }
            });
        }, 200);
    };

    onSelectPlaceButtonClick ($button) {
        this.$pickUpPlaceInput.val($button.data('id'));

        const $transportInput = $('#js-window').data('transportInput');
        if ($transportInput.prop('disabled') !== true) {
            $transportInput.prop('checked', true).change();
        }

        const $pickUpPlaceDetail = this.$container.find('#transport_and_payment_form_transport .js-pick-up-place-detail');

        this.$container.find('.js-pick-up-place-detail').addClass('display-none');
        $pickUpPlaceDetail.removeClass('display-none')
            .attr('title', $button.data('description'))
            .tooltip('destroy');

        $pickUpPlaceDetail.find('.js-pick-up-place-detail-name')
            .text($button.data('name'));

        $pickUpPlaceDetail.find('.js-pick-up-place-detail-address')
            .text($button.data('address'));

        $pickUpPlaceDetail.find('.js-pick-up-place-change-button').toggle($button.data('name').length > 0);

        windowClose();
    };

    onChangeButtonClick ($button) {
        const $transportContainer = $button.closest('.js-order-transport');
        const $selectedTransportInput = $transportContainer.find('.js-order-transport-input');

        this.showSearchWindow($selectedTransportInput);
    };

    static updateSummaryVisibility () {
        if ($('.js-order-transport-input').length === 0) {
            return;
        }
        const transportType = $('.js-order-transport-input:checked').data('transport-type');
        const isPickUpPlaceTransportTypeSelected = PickUpPlace.isPickUpPlaceTransportType(transportType);

        $('.js-pick-up-place-summary').toggleClass('display-none', !isPickUpPlaceTransportTypeSelected);
        $('.js-pick-up-place-hide-if-chosen').toggleClass('display-none', isPickUpPlaceTransportTypeSelected);
    };

    static isPickUpPlaceTransportType (transportType) {
        return $.inArray(transportType, ['zasilkovna']) !== -1;
    };

    static init ($container) {
        const $pickUpPlaceInput = $container.find('.js-pick-up-place-input');
        const pickUpPlace = new PickUpPlace($container, $pickUpPlaceInput);
        const $transportInput = $container.find('.js-order-transport-input');
        const $autocompleteInput = $container.find('.js-pick-up-place-city-post-code-autocomplete-input');
        const $pickUpPlaceButton = $container.find('.js-pick-up-place-button');
        const $pickUpPlaceChangeButton = $container.find('.js-pick-up-place-change-button');

        $transportInput.change((event) => pickUpPlace.onTransportChange(event, $transportInput));

        $autocompleteInput.bind('keyup paste', () => pickUpPlace.onSearchAutocompleteInputChange($autocompleteInput));
        $pickUpPlaceButton.click(() => pickUpPlace.onSelectPlaceButtonClick($pickUpPlaceButton));
        $pickUpPlaceChangeButton.click(() => pickUpPlace.onChangeButtonClick($pickUpPlaceChangeButton));

        PickUpPlace.updateSummaryVisibility();
    }
}

(new Register()).registerCallback(PickUpPlace.init, 'PickUpPlace.init');
