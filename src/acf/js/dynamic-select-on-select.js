
	// https://github.com/Hube2/acf-dynamic-ajax-select-example/tree/master/dynamic-select-example
	
	jQuery(document).ready(function($){
		if (typeof acf == 'undefined') { return; }
		
		$(document).on('change', '[data-key="field_5f845ee95ad4c"] .acf-input select', function(e) {
			update_lists_on_board_change(e, $);
        });
        
		$('[data-key="field_5f845ee95ad4c"] .acf-input select').trigger('ready');
	});
    
    


	function update_lists_on_board_change(e, $) {

        // if a recent request has been made abort it
		if (this.request) {
			this.request.abort();
		}
		
		// get the list select field, and remove all exisiting choices
		var list_select = $('[data-key="field_5f845efb5ad4d"] select');
		list_select.empty();
        
        
		// get the target of the event and then get the value of that field
		var target = $(e.target);
		var board = target.val();
        
        // no board selected
        // don't need to do anything else
		if (!board) {
			return;
		}
		
		// set and prepare data for ajax
		var data = {
			action: 'load_list_field_choices',
			board: board
		}
		
		// call the acf function that will fill in other values
		// like post_id and the acf nonce
		data = acf.prepareForAjax(data);
		
		// make ajax request
		// instead of going through the acf.ajax object to make requests like in <5.7
		// we need to do a lot of the work ourselves, but other than the method that's called
		// this has not changed much
		this.request = $.ajax({
			url: acf.get('ajaxurl'), // acf stored value
			data: data,
			type: 'post',
			dataType: 'json',
			success: function(json) {
				if (!json) {
					return;
				}
				// add the new options to the list field
				for(i=0; i<json.length; i++) {
					var list_item = '<option value="'+json[i]['value']+'">'+json[i]['label']+'</option>';
					list_select.append(list_item);
				}
			}
		});
		
	}