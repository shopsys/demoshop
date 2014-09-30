(function ($){

	SS6 = SS6 || {};
	SS6.grid = SS6.grid || {};
	
	SS6.grid.init = function (formElement) {
		$('.js-grid-go-to').each(SS6.grid.bindGoTo);
	}
	
	SS6.grid.bindGoTo = function () {
		var $button = $(this).find('.js-grid-go-to-button');
		var $input = $(this).find('.js-grid-go-to-input');
		
		$input.bind('keyup.gridGoTo', function (event) {
			if (event.keyCode == 13) {
				$button.trigger('click.gridGoTo', event);
			}
		});
		
		$button.bind('click.gridGoTo', function () {
			document.location = $(this).data('url').replace('--page--', $input.val());
			return false;
		});
	}
	
	$(document).ready(function () {
		SS6.grid.init();
	});
	
})(jQuery);