
	// https://github.com/Hube2/acf-dynamic-ajax-select-example/tree/master/dynamic-select-example
	

	/**
	 * Wait for a change on the 'board' selection.
	 */
	jQuery(document).ready(function($){
		if (typeof acf == 'undefined') { return; }
		
		$(document).on('change', '[data-key="field_5f845ee95ad4c"] .acf-input select', function(e) {
			update_lists_on_board_change(e, $);
			update_labels_on_board_change(e, $);
			update_custom_fields_on_board_change(e, $);
        });
        
		$('[data-key="field_5f845ee95ad4c"] .acf-input select').trigger('ready');
	});
    
    


	function update_labels_on_board_change(e, $) {

        // if a recent request has been made abort it
		if (this.request_label) {
			this.request_label.abort();
		}
		
		// get the label select field, and remove all exisiting choices
		var label_select = $('[data-key="field_5f8489754e199"] select');
		label_select.empty();
        
        
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
			action: 'load_label_field_choices',
			board: board
		}
		
		// call the acf function that will fill in other values
		// like post_id and the acf nonce
		data = acf.prepareForAjax(data);
		
		// make ajax request_label
		// instead of going through the acf.ajax object to make request_labels like in <5.7
		// we need to do a lot of the work ourselves, but other than the method that's called
		// this has not changed much
		this.request_label = $.ajax({
			url: acf.get('ajaxurl'), // acf stored value
			data: data,
			type: 'post',
			dataType: 'json',
			success: function(json) {
				if (!json) {
					return;
				}
				// add the new options to the label field
				for(i=0; i<json.length; i++) {
					var label_item = '<option value="'+json[i]['value']+'">'+json[i]['label']+'</option>';
					label_select.append(label_item);
				}
			}
		});
		
	}




	function update_lists_on_board_change(e, $) {

        // if a recent request has been made abort it
		if (this.request_list) {
			this.request_list.abort();
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
		
		// make ajax request_list
		// instead of going through the acf.ajax object to make request_lists like in <5.7
		// we need to do a lot of the work ourselves, but other than the method that's called
		// this has not changed much
		this.request_list = $.ajax({
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


	function update_custom_fields_on_board_change(e, $) {

        // if a recent request has been made abort it
		if (this.custom_field) {
			this.custom_field.abort();
		}
		
		// get the list select field, and remove all exisiting choices
		var custom_fields_select = $('[data-key="field_5f85a329b1641"] select');
		custom_fields_select.empty();
        
        
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
			action: 'load_custom_fields_choices',
			board: board
		}
		
		// call the acf function that will fill in other values
		// like post_id and the acf nonce
		data = acf.prepareForAjax(data);
		
		// make ajax custom_field
		// instead of going through the acf.ajax object to make custom_fields like in <5.7
		// we need to do a lot of the work ourselves, but other than the method that's called
		// this has not changed much
		this.custom_field = $.ajax({
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
					var custom_fields_item = '<option value="'+json[i]['value']+'">'+json[i]['label']+'</option>';
					custom_fields_select.append(custom_fields_item);
				}
			}
		});
		
	}