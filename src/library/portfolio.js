
/* eslint-disable no-var */
// /* eslint-disable vars-on-top */
// /* eslint-disable no-var */

jQuery( function( $ ) {
	var $container = $( '#classic' ).imagesLoaded( function() {
		$container.isotope( {
			itemSelector: '.item',
			layoutMode: 'masonry',
			percentPosition: true,
			columnWidth: 200,
		} );
	} );
	//Add the class selected to the item that is clicked, and remove from the others
	const $optionSets = $( '#filters' ),
		$optionLinks = $optionSets.find( 'li' );

	$optionLinks.click( function() {
		const $this = $( this );
		// don't proceed if already selected
		if ( $this.hasClass( 'selected' ) ) {
			return false;
		}
		const $optionSets = $this.parents( '#filters' );
		$optionSets.find( '.selected' ).removeClass( 'selected' );
		$this.addClass( 'selected' );

		//When an item is clicked, sort the items.
		const selector = $( this ).attr( 'data-filter' );
		$container.isotope( { filter: selector } );

		return false;
	} );
} );
//calculate height of item based on width
// window.addEventListener( 'resize', function( e ) {
// 	var mapElement = document.getElementByClassName( 'w-100' );
// 	mapElement.style.height = Math.floor( mapElement.offsetWidth * 1.72 ) + 'px';
// } );
