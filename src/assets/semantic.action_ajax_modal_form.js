/*
 * jQuery + Semantic-UI plugin
 */

(function ($) {
	$.fn.action_ajax_modal_form = function ( modal, options ) {
		var $modal = $(modal);
		_init($modal);
		
		this.each(function () {
			init.call(this, $modal, options);
		});
	};
	
	var default_settings = {
		loaderHtml: '<div class="ui text loader">Loading</div>',
	};
	$.fn.action_ajax_modal_form.settings = default_settings;
	
	
	function _init ( $modal )
	{
		$modal.modal({
			onApprove: function () {
				$modal.find('.actions').hide();
				$modal.find('.content form')
					.addClass('ui form loading')
					.submit();
				return false;
			},
		});
		$modal.find('.actions').hide();
	}
	
	function init ( $modal, options )
	{
		options = $.extend({}, default_settings, options);
		
		var $link = $(this);
		
		// init action
		$link.click(function ( event ) {
			event.preventDefault();
			var $link = $(this);
			var url = $link.attr('href');
			
			$.get({
				url: url,
				method: 'get',
				success: function ( html ) {
					_form($modal, html);
				},
				error: function ( err ) {
					_error($modal, err);
				},
				complete: function () {},
			});
			
			_loading($modal, options);
		});
		
	}
	
	function _loading ( $modal, options )
	{
		$modal.find('.content').html(options.loaderHtml);
		$modal.find('.actions').hide();
		$modal.modal('show');
	}
	
	function _form ( $modal, html )
	{
		var $page = $('<div>'+html+'</div>');
		var $form = $page.find('#content form');
		if ( !$form.length ) {
			$modal.hide();
			return;
		}
		// clear form
		$form.find('.actions').remove();
		// show form
		$modal.find('.content').empty().append($form);
		$modal.find('.actions').show();
		// show modal
		$modal.modal('setting', 'closeable', false).modal('show');
	}
	
	function _error ( $modal, err )
	{
		$modal.find('.actions').show();
		$modal.find('.content').empty()
			.append('Error: ' + err.message);
	}
	
	
	
})(jQuery);
