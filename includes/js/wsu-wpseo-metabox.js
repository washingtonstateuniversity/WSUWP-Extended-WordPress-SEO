(function($){

	$( '#yoast_wpseo_title' ).val( '' );

	$(window).load(function() {

		$( '#snippet-editor-title' ).hide();
		$( '#snippet-editor-slug' ).hide();
		$( '[for=snippet-editor-slug]').hide();

	});

}(jQuery));
