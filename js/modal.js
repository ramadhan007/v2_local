// JavaScript Document

$(document).ready(function(e) {
    function autoheight()
	{
		var modal = $(this);
		var modal_height = modal.find('.modal-dialog').height();
		var modal_header_height = modal.find('.modal-content .modal-header').height();
		var modal_body_height = modal.find('.modal-content .modal-body').height();
		modal.find('.modal-content .modal-body iframe').height(modal_body_height-60);
	}
	
	function reposition() {
		var modal = $(this);
		dialog = modal.find('.modal-dialog');
		modal.css('display', 'block');
		dialog.css("margin-top", Math.max(0, ($(window).height() - dialog.height()) / 2));
	}
	
	//auto height when a large modal before
	$('.modal.autoheight').on('show.bs.modal', autoheight);
	
	//auto height when a large modal is shown
	$('.modal.autoheight').on('shown.bs.modal', autoheight);
	
	// Reposition when a modal before shown
	$('.modal').on('show.bs.modal', reposition);
	
	// Reposition when a modal is shown
    $('.modal').on('shown.bs.modal', reposition);
	
	// Reposition when the window is resized
    $(window).on('resize', function() {
		$('.modal.autoheight:visible').each(autoheight);
        $('.modal:visible').each(reposition);
    });
});