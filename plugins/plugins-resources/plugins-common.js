



/**
 * Generate random string
 */
function random_string(string_length){
	var text = "";
	var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

	for(var i = 0; i < string_length; i++)
		text += possible.charAt(Math.floor(Math.random() * possible.length));

	return text;
}

//--------------------------------------------------------------------------------------------------------------------

/**
 * options object. The following members can be provided:
 *    url: iframe url to load
 *    message: instead of a url to open, you could pass a message. HTML tags allowed.
 *    id: id attribute of modal window
 *    title: optional modal window title
 *    size: 'default', 'full'
 *    close: optional function to execute on closing the modal
 *    footer: optional array of objects describing the buttons to display in the footer.
 *       Each button object can have the following members:
 *          label: string, label of button
 *          bs_class: string, button bootstrap class. Can be 'primary', 'default', 'success', 'warning' or 'danger'
 *          click: function to execute on clicking the button. If the button closes the modal, this
 *                 function is executed before the close handler
 *          causes_closing: boolean, default is true.
 */
function modal_window(options){
	var id = options.id;
	var url = options.url;
	var title = options.title;
	var footer = options.footer;
	var message = options.message;

	if(typeof(id) == 'undefined') id = random_string(20);
	if(typeof(footer) == 'undefined') footer = [];

	if(jQuery('#' + id).length){
		/* modal exists -- remove it first */
		jQuery('#' + id).remove();
	}

	/* prepare footer buttons, if any */
	var footer_buttons = '';
	for(i = 0; i < footer.length; i++){
		if(typeof(footer[i].causes_closing) == 'undefined'){ footer[i].causes_closing = true; }
		if(typeof(footer[i].bs_class) == 'undefined'){ footer[i].bs_class = 'default'; }
		footer[i].id = id + '_footer_button_' + random_string(10);

		footer_buttons += '<button type="button" class="btn btn-' + footer[i].bs_class + '" ' +
				(footer[i].causes_closing ? 'data-dismiss="modal" ' : '') +
				'id="' + footer[i].id + '" ' +
				'>' + footer[i].label + '</button>';
	}

	jQuery('body').append(
		'<div class="modal fade" id="' + id + '" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">' +
			'<div class="modal-dialog">' +
				'<div class="modal-content">' +
					( title != undefined ?
						'<div class="modal-header">' +
							'<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>' +
							'<h3 class="modal-title" id="myModalLabel">' + title + '</h3>' +
						'</div>'
						: ''
					) +
					'<div class="modal-body" style="-webkit-overflow-scrolling:touch !important; overflow-y: auto;">' +
						( url != undefined ?
							'<iframe width="100%" height="100%" sandbox="allow-forms allow-scripts allow-same-origin allow-popups" src="' + url + '"></iframe>'
							: message
						) +
					'</div>' +
					( footer != undefined ?
						'<div class="modal-footer">' + footer_buttons + '</div>'
						: ''
					) +
				'</div>' +
			'</div>' +
		'</div>'
	);

	for(i = 0; i < footer.length; i++){
		if(typeof(footer[i].click) == 'function'){
			jQuery('#' + footer[i].id).click(footer[i].click);
		}
	}

	jQuery('#' + id).modal();

	if(typeof(options.close) == 'function'){
		jQuery('#' + id).on('hidden.bs.modal', options.close);
	}

	if(typeof(options.size) == 'undefined') options.size = 'default';

	if(options.size == 'full'){
		jQuery(window).resize(function(){
			jQuery('#' + id + ' .modal-dialog').width(jQuery(window).width() * 0.95);
			jQuery('#' + id + ' .modal-body').height(jQuery(window).height() * 0.7);
		}).trigger('resize');
	}

	return id;
}

//---------------------------------------------------------------------------------------------------------------------

/**
 *  Display div element slowly then hide after 5 sec
 *  @param  divElement: div element to be showed
 			location: redirect location after displaying div, 
 					  pass false if no redirect needed
 */
function dismissible_msg( divElement , location ){
	  $j(divElement).show("slow", function(){
			setTimeout(function(){
				$j(divElement).hide("slow"); 
				if (location){
					window.location.href = location;
				}
			}, 5000);
		});		
}
