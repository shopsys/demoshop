import Register from 'framework/common/utils/Register';

export default function disableDoubleSubmit ($container) {
    $container.filterAllNodes('form').each(function () {
        let isFormSubmittingDisabled = false;

        $(this).submit(function (event) {
            if (isFormSubmittingDisabled) {
                event.stopImmediatePropagation();
                event.preventDefault();
            } else {
                isFormSubmittingDisabled = true;
                setTimeout(function () {
                    isFormSubmittingDisabled = false;
                }, 200);
            }
        });
    });
}

(new Register()).registerCallback(disableDoubleSubmit, 'disableDoubleSubmit');
