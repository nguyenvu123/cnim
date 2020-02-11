// Init scripts
jQuery(document).ready(function(){
	"use strict";
	
	// Settings and constants
	AXIOM_GLOBALS['shortcodes_delimiter'] = ',';		// Delimiter for multiple values
	AXIOM_GLOBALS['shortcodes_popup'] = null;		// Popup with current shortcode settings
	AXIOM_GLOBALS['shortcodes_current_idx'] = '';	// Current shortcode's index
	AXIOM_GLOBALS['shortcodes_tab_clone_tab'] = '<li id="axiom_shortcodes_tab_{id}" data-id="{id}"><a href="#axiom_shortcodes_tab_{id}_content"><span class="iconadmin-{icon}"></span>{title}</a></li>';
	AXIOM_GLOBALS['shortcodes_tab_clone_content'] = '';

	// Shortcode selector - "change" event handler - add selected shortcode in editor
	jQuery('body').on('change', ".sc_selector", function() {
		"use strict";
		AXIOM_GLOBALS['shortcodes_current_idx'] = jQuery(this).find(":selected").val();
		if (AXIOM_GLOBALS['shortcodes_current_idx'] == '') return;
		var sc = axiom_clone_object(AXIOM_GLOBALS['shortcodes'][AXIOM_GLOBALS['shortcodes_current_idx']]);
		var hdr = sc.title;
		var content = "";
		try {
			content = tinyMCE.activeEditor ? tinyMCE.activeEditor.selection.getContent({format : 'raw'}) : jQuery('#wp-content-editor-container textarea').selection();
		} catch(e) {};
		if (content) {
			for (var i in sc.params) {
				if (i == '_content_') {
					sc.params[i].value = content;
					break;
				}
			}
		}
		var html = (!axiom_empty(sc.desc) ? '<p>'+sc.desc+'</p>' : '')
			+ axiom_shortcodes_prepare_layout(sc);

		// Show Dialog popup
		AXIOM_GLOBALS['shortcodes_popup'] = axiom_message_dialog(html, hdr,
			function(popup) {
				"use strict";
				axiom_options_init(popup);
				popup.find('.axiom_options_tab_content').css({
					maxHeight: jQuery(window).height() - 300 + 'px',
					overflow: 'auto'
				});
			},
			function(btn, popup) {
				"use strict";
				if (btn != 1) return;
				var sc = axiom_shortcodes_get_code(AXIOM_GLOBALS['shortcodes_popup']);
				if (tinyMCE.activeEditor) {
					if ( !tinyMCE.activeEditor.isHidden() )
						tinyMCE.activeEditor.execCommand( 'mceInsertContent', false, sc );
					//else if (typeof wpActiveEditor != 'undefined' && wpActiveEditor != '') {
					//	document.getElementById( wpActiveEditor ).value += sc;
					else
						send_to_editor(sc);
				} else
					send_to_editor(sc);
			});

		// Set first item active
		jQuery(this).get(0).options[0].selected = true;

		// Add new child tab
		AXIOM_GLOBALS['shortcodes_popup'].find('.axiom_shortcodes_tab').on('tabsbeforeactivate', function (e, ui) {
			if (ui.newTab.data('id')=='add') {
				axiom_shortcodes_add_tab(ui.newTab);
				e.stopImmediatePropagation();
				e.preventDefault();
				return false;
			}
		});

		// Delete child tab
		AXIOM_GLOBALS['shortcodes_popup'].find('.axiom_shortcodes_tab > ul').on('click', '> li+li > a > span', function (e) {
			var tab = jQuery(this).parents('li');
			var idx = tab.data('id');
			if (parseInt(idx) > 1) {
				if (tab.hasClass('ui-state-active')) {
					tab.prev().find('a').trigger('click');
				}
				tab.parents('.axiom_shortcodes_tab').find('.axiom_options_tab_content').eq(idx).remove();
				tab.remove();
				e.preventDefault();
				return false;
			}
		});

		return false;
	});

});



// Return result code
//------------------------------------------------------------------------------------------
function axiom_shortcodes_get_code(popup) {
	AXIOM_GLOBALS['sc_custom'] = '';
	
	var sc_name = AXIOM_GLOBALS['shortcodes_current_idx'];
	var sc = AXIOM_GLOBALS['shortcodes'][sc_name];
	var tabs = popup.find('.axiom_shortcodes_tab > ul > li');
	var decor = !axiom_isset(sc.decorate) || sc.decorate;
	var rez = '[' + sc_name + axiom_shortcodes_get_code_from_tab(popup.find('#axiom_shortcodes_tab_0_content').eq(0)) + ']'
			// + (decor ? '\n' : '')
			;
	if (axiom_isset(sc.children)) {
		if (AXIOM_GLOBALS['sc_custom']!='no') {
			var decor2 = !axiom_isset(sc.children.decorate) || sc.children.decorate;
			for (var i=0; i<tabs.length; i++) {
				var tab = tabs.eq(i);
				var idx = tab.data('id');
				if (isNaN(idx) || parseInt(idx) < 1) continue;
				var content = popup.find('#axiom_shortcodes_tab_' + idx + '_content').eq(0);
				rez += (decor2 ? '\n\t' : '') + '[' + sc.children.name + axiom_shortcodes_get_code_from_tab(content) + ']';	// + (decor2 ? '\n' : '');
				if (axiom_isset(sc.children.container) && sc.children.container) {
					if (content.find('[data-param="_content_"]').length > 0) {
						rez += 
							//(decor2 ? '\t\t' : '') + 
							content.find('[data-param="_content_"]').val()
							// + (decor2 ? '\n' : '')
							;
					}
					rez += 
						//(decor2 ? '\t' : '') + 
						'[/' + sc.children.name + ']'
						// + (decor ? '\n' : '')
						;
				}
			}
		}
	} else if (axiom_isset(sc.container) && sc.container && popup.find('#axiom_shortcodes_tab_0_content [data-param="_content_"]').length > 0) {
		rez += 
			//(decor ? '\t' : '') + 
			popup.find('#axiom_shortcodes_tab_0_content [data-param="_content_"]').val()
			// + (decor ? '\n' : '')
			;
	}
	if (axiom_isset(sc.container) && sc.container || axiom_isset(sc.children))
		rez += 
			(axiom_isset(sc.children) && decor && AXIOM_GLOBALS['sc_custom']!='no' ? '\n' : '')
			+ '[/' + sc_name + ']'
			 //+ (decor ? '\n' : '')
			 ;
	return rez;
}

// Collect all parameters from tab into string
function axiom_shortcodes_get_code_from_tab(tab) {
	var rez = ''
	var mainTab = tab.attr('id').indexOf('tab_0') > 0;
	tab.find('[data-param]').each(function () {
		var field = jQuery(this);
		var param = field.data('param');
		if (!field.parents('.axiom_options_field').hasClass('axiom_options_no_use') && param.substr(0, 1)!='_' && !axiom_empty(field.val()) && field.val()!='none' && (field.attr('type') != 'checkbox' || field.get(0).checked)) {
			rez += ' '+param+'="'+axiom_shortcodes_prepare_value(field.val())+'"';
		}
		// On main tab detect param "custom"
		if (mainTab && param=='custom') {
			AXIOM_GLOBALS['sc_custom'] = field.val();
		}
	});
	// Get additional params for general tab from items tabs
	if (AXIOM_GLOBALS['sc_custom']!='no' && mainTab) {
		var sc = AXIOM_GLOBALS['shortcodes'][AXIOM_GLOBALS['shortcodes_current_idx']];
		var sc_name = AXIOM_GLOBALS['shortcodes_current_idx'];
		if (sc_name == 'trx_columns' || sc_name == 'trx_skills' || sc_name == 'trx_team' || sc_name == 'trx_price_table') {	// Determine "count" parameter
			var cnt = 0;
			tab.siblings('div').each(function() {
				var item_tab = jQuery(this);
				var merge = parseInt(item_tab.find('[data-param="span"]').val());
				cnt += !isNaN(merge) && merge > 0 ? merge : 1;
			});
			rez += ' count="'+cnt+'"';
		}
	}
	return rez;
}



// Shortcode parameters builder
//-------------------------------------------------------------------------------------------

// Prepare layout from shortcode object (array)
function axiom_shortcodes_prepare_layout(field) {
	"use strict";
	// Make params cloneable
	field['params'] = [field['params']];
	if (!axiom_empty(field.children)) {
		field.children['params'] = [field.children['params']];
	}
	// Prepare output
	var output = '<div class="axiom_shortcodes_body axiom_options_body"><form>';
	output += axiom_shortcodes_show_tabs(field);
	output += axiom_shortcodes_show_field(field, 0);
	if (!axiom_empty(field.children)) {
		AXIOM_GLOBALS['shortcodes_tab_clone_content'] = axiom_shortcodes_show_field(field.children, 1);
		output += AXIOM_GLOBALS['shortcodes_tab_clone_content'];
	}
	output += '</div></form></div>';
	return output;
}



// Show tabs
function axiom_shortcodes_show_tabs(field) {
	"use strict";
	// html output
	var output = '<div class="axiom_shortcodes_tab axiom_options_container axiom_options_tab">'
		+ '<ul>'
		+ AXIOM_GLOBALS['shortcodes_tab_clone_tab'].replace(/{id}/g, 0).replace('{icon}', 'cog').replace('{title}', 'General');
	if (axiom_isset(field.children)) {
		for (var i=0; i<field.children.params.length; i++)
			output += AXIOM_GLOBALS['shortcodes_tab_clone_tab'].replace(/{id}/g, i+1).replace('{icon}', 'cancel').replace('{title}', field.children.title + ' ' + (i+1));
		output += AXIOM_GLOBALS['shortcodes_tab_clone_tab'].replace(/{id}/g, 'add').replace('{icon}', 'list-add').replace('{title}', '');
	}
	output += '</ul>';
	return output;
}

// Add new tab
function axiom_shortcodes_add_tab(tab) {
	"use strict";
	var idx = 0;
	tab.siblings().each(function () {
		"use strict";
		var i = parseInt(jQuery(this).data('id'));
		if (i > idx) idx = i;
	});
	idx++;
	tab.before( AXIOM_GLOBALS['shortcodes_tab_clone_tab'].replace(/{id}/g, idx).replace('{icon}', 'cancel').replace('{title}', AXIOM_GLOBALS['shortcodes'][AXIOM_GLOBALS['shortcodes_current_idx']].children.title + ' ' + idx) );
	tab.parents('.axiom_shortcodes_tab').append(AXIOM_GLOBALS['shortcodes_tab_clone_content'].replace(/tab_1_/g, 'tab_' + idx + '_'));
	tab.parents('.axiom_shortcodes_tab').tabs('refresh');
	axiom_options_init(tab.parents('.axiom_shortcodes_tab').find('.axiom_options_tab_content').eq(idx));
	tab.prev().find('a').trigger('click');
}



// Show one field layout
function axiom_shortcodes_show_field(field, tab_idx) {
	"use strict";
	
	// html output
	var output = '';

	// Parse field params
	for (var clone_num in field['params']) {
		var tab_id = 'tab_' + (parseInt(tab_idx) + parseInt(clone_num));
		output += '<div id="axiom_shortcodes_' + tab_id + '_content" class="axiom_options_content axiom_options_tab_content">';

		for (var param_num in field['params'][clone_num]) {
			
			var param = field['params'][clone_num][param_num];
			var id = tab_id + '_' + param_num;
	
			// Divider after field
			var divider = axiom_isset(param['divider']) && param['divider'] ? ' axiom_options_divider' : '';
		
			// Setup default parameters
			if (param['type']=='media') {
				if (!axiom_isset(param['before'])) {
					param['before'] = {
						'title': 'Choose image',
						'action': 'media_upload',
						'type': 'image',
						'multiple': false,
						'linked_field': '',
						'captions': { 	
							'choose': 'Choose image',
							'update': 'Select image'
							}
					};
				}
				if (!axiom_isset(param['after'])) {
					param['after'] = {
						'icon': 'iconadmin-cancel',
						'action': 'media_reset'
					};
				}
			}
		
			// Buttons before and after field
			var before = '', after = '', buttons_classes = '', rez, rez2, i, key, opt;
			
			if (axiom_isset(param['before'])) {
				rez = axiom_shortcodes_action_button(param['before'], 'before');
				before = rez[0];
				buttons_classes += rez[1];
			}
			if (axiom_isset(param['after'])) {
				rez = axiom_shortcodes_action_button(param['after'], 'after');
				after = rez[0];
				buttons_classes += rez[1];
			}
			if (axiom_in_array(param['type'], ['list', 'select', 'fonts']) || (param['type']=='socials' && (axiom_empty(param['style']) || param['style']=='icons'))) {
				buttons_classes += ' axiom_options_button_after_small';
			}

			if (param['type'] != 'hidden') {
				output += '<div class="axiom_options_field'
					+ ' axiom_options_field_' + (axiom_in_array(param['type'], ['list','fonts']) ? 'select' : param['type'])
					+ (axiom_in_array(param['type'], ['media', 'fonts', 'list', 'select', 'socials', 'date', 'time']) ? ' axiom_options_field_text'  : '')
					+ (param['type']=='socials' && !axiom_empty(param['style']) && param['style']=='images' ? ' axiom_options_field_images'  : '')
					+ (param['type']=='socials' && (axiom_empty(param['style']) || param['style']=='icons') ? ' axiom_options_field_icons'  : '')
					+ (axiom_isset(param['dir']) && param['dir']=='vertical' ? ' axiom_options_vertical' : '')
					+ (!axiom_empty(param['multiple']) ? ' axiom_options_multiple' : '')
					+ (axiom_isset(param['size']) ? ' axiom_options_size_'+param['size'] : '')
					+ (axiom_isset(param['class']) ? ' ' + param['class'] : '')
					+ divider 
					+ '">' 
					+ "\n"
					+ '<label class="axiom_options_field_label" for="' + id + '">' + param['title']
					+ '</label>'
					+ "\n"
					+ '<div class="axiom_options_field_content'
					+ buttons_classes
					+ '">'
					+ "\n";
			}
			
			if (!axiom_isset(param['value'])) {
				param['value'] = '';
			}
			

			switch ( param['type'] ) {
	
			case 'hidden':
				output += '<input class="axiom_options_input axiom_options_input_hidden" name="' + id + '" id="' + id + '" type="hidden" value="' + axiom_shortcodes_prepare_value(param['value']) + '" data-param="' + axiom_shortcodes_prepare_value(param_num) + '" />';
			break;

			case 'date':
				if (axiom_isset(param['style']) && param['style']=='inline') {
					output += '<div class="axiom_options_input_date"'
						+ ' id="' + id + '_calendar"'
						+ ' data-format="' + (!axiom_empty(param['format']) ? param['format'] : 'yy-mm-dd') + '"'
						+ ' data-months="' + (!axiom_empty(param['months']) ? max(1, min(3, param['months'])) : 1) + '"'
						+ ' data-linked-field="' + (!axiom_empty(data['linked_field']) ? data['linked_field'] : id) + '"'
						+ '></div>'
						+ '<input id="' + id + '"'
							+ ' name="' + id + '"'
							+ ' type="hidden"'
							+ ' value="' + axiom_shortcodes_prepare_value(param['value']) + '"'
							+ ' data-param="' + axiom_shortcodes_prepare_value(param_num) + '"'
							+ (!axiom_empty(param['action']) ? ' onchange="axiom_options_action_'+param['action']+'(this);return false;"' : '')
							+ ' />';
				} else {
					output += '<input class="axiom_options_input axiom_options_input_date' + (!axiom_empty(param['mask']) ? ' axiom_options_input_masked' : '') + '"'
						+ ' name="' + id + '"'
						+ ' id="' + id + '"'
						+ ' type="text"'
						+ ' value="' + axiom_shortcodes_prepare_value(param['value']) + '"'
						+ ' data-format="' + (!axiom_empty(param['format']) ? param['format'] : 'yy-mm-dd') + '"'
						+ ' data-months="' + (!axiom_empty(param['months']) ? max(1, min(3, param['months'])) : 1) + '"'
						+ ' data-param="' + axiom_shortcodes_prepare_value(param_num) + '"'
						+ (!axiom_empty(param['action']) ? ' onchange="axiom_options_action_'+param['action']+'(this);return false;"' : '')
						+ ' />'
						+ before 
						+ after;
				}
			break;

			case 'text':
				output += '<input class="axiom_options_input axiom_options_input_text' + (!axiom_empty(param['mask']) ? ' axiom_options_input_masked' : '') + '"'
					+ ' name="' + id + '"'
					+ ' id="' + id + '"'
					+ ' type="text"'
					+ ' value="' + axiom_shortcodes_prepare_value(param['value']) + '"'
					+ (!axiom_empty(param['mask']) ? ' data-mask="'+param['mask']+'"' : '') 
					+ ' data-param="' + axiom_shortcodes_prepare_value(param_num) + '"'
					+ (!axiom_empty(param['action']) ? ' onchange="axiom_options_action_'+param['action']+'(this);return false;"' : '')
					+ ' />'
				+ before 
				+ after;
			break;
		
			case 'textarea':
				var cols = axiom_isset(param['cols']) && param['cols'] > 10 ? param['cols'] : '40';
				var rows = axiom_isset(param['rows']) && param['rows'] > 1 ? param['rows'] : '8';
				output += '<textarea class="axiom_options_input axiom_options_input_textarea"'
					+ ' name="' + id + '"'
					+ ' id="' + id + '"'
					+ ' cols="' + cols + '"'
					+ ' rows="' + rows + '"'
					+ ' data-param="' + axiom_shortcodes_prepare_value(param_num) + '"'
					+ (!axiom_empty(param['action']) ? ' onchange="axiom_options_action_'+param['action']+'(this);return false;"' : '')
					+ '>'
					+ param['value']
					+ '</textarea>';
			break;

			case 'spinner':
				output += '<input class="axiom_options_input axiom_options_input_spinner' + (!axiom_empty(param['mask']) ? ' axiom_options_input_masked' : '') + '"'
					+ ' name="' + id + '"'
					+ ' id="' + id + '"'
					+ ' type="text"'
					+ ' value="' + axiom_shortcodes_prepare_value(param['value']) + '"' 
					+ (!axiom_empty(param['mask']) ? ' data-mask="'+param['mask']+'"' : '') 
					+ (axiom_isset(param['min']) ? ' data-min="'+param['min']+'"' : '') 
					+ (axiom_isset(param['max']) ? ' data-max="'+param['max']+'"' : '') 
					+ (!axiom_empty(param['step']) ? ' data-step="'+param['step']+'"' : '') 
					+ ' data-param="' + axiom_shortcodes_prepare_value(param_num) + '"'
					+ (!axiom_empty(param['action']) ? ' onchange="axiom_options_action_'+param['action']+'(this);return false;"' : '')
					+ ' />' 
					+ '<span class="axiom_options_arrows"><span class="axiom_options_arrow_up iconadmin-up-dir"></span><span class="axiom_options_arrow_down iconadmin-down-dir"></span></span>';
			break;

			case 'tags':
				var tags = param['value'].split(AXIOM_GLOBALS['shortcodes_delimiter']);
				if (tags.length > 0) {
					for (i=0; i<tags.length; i++) {
						if (axiom_empty(tags[i])) continue;
						output += '<span class="axiom_options_tag iconadmin-cancel">' + tags[i] + '</span>';
					}
				}
				output += '<input class="axiom_options_input_tags"'
					+ ' type="text"'
					+ ' value=""'
					+ ' />'
					+ '<input name="' + id + '"'
						+ ' type="hidden"'
						+ ' value="' + axiom_shortcodes_prepare_value(param['value']) + '"'
						+ ' data-param="' + axiom_shortcodes_prepare_value(param_num) + '"'
						+ (!axiom_empty(param['action']) ? ' onchange="axiom_options_action_'+param['action']+'(this);return false;"' : '')
						+ ' />';
			break;
		
			case "checkbox": 
				output += '<input type="checkbox" class="axiom_options_input axiom_options_input_checkbox"'
					+ ' name="' + id + '"'
					+ ' id="' + id + '"'
					+ ' value="true"' 
					+ (param['value'] == 'true' ? ' checked="checked"' : '') 
					+ (!axiom_empty(param['disabled']) ? ' readonly="readonly"' : '') 
					+ ' data-param="' + axiom_shortcodes_prepare_value(param_num) + '"'
					+ (!axiom_empty(param['action']) ? ' onchange="axiom_options_action_'+param['action']+'(this);return false;"' : '')
					+ ' />'
					+ '<label for="' + id + '" class="' + (!axiom_empty(param['disabled']) ? 'axiom_options_state_disabled' : '') + (param['value']=='true' ? ' axiom_options_state_checked' : '') + '"><span class="axiom_options_input_checkbox_image iconadmin-check"></span>' + (!axiom_empty(param['label']) ? param['label'] : param['title']) + '</label>';
			break;
		
			case "radio":
				for (key in param['options']) { 
					output += '<span class="axiom_options_radioitem"><input class="axiom_options_input axiom_options_input_radio" type="radio"'
						+ ' name="' + id + '"'
						+ ' value="' + axiom_shortcodes_prepare_value(key) + '"'
						+ ' data-value="' + axiom_shortcodes_prepare_value(key) + '"'
						+ (param['value'] == key ? ' checked="checked"' : '') 
						+ ' id="' + id + '_' + key + '"'
						+ ' />'
						+ '<label for="' + id + '_' + key + '"' + (param['value'] == key ? ' class="axiom_options_state_checked"' : '') + '><span class="axiom_options_input_radio_image iconadmin-circle-empty' + (param['value'] == key ? ' iconadmin-dot-circled' : '') + '"></span>' + param['options'][key] + '</label></span>';
				}
				output += '<input type="hidden"'
						+ ' value="' + axiom_shortcodes_prepare_value(param['value']) + '"'
						+ ' data-param="' + axiom_shortcodes_prepare_value(param_num) + '"'
						+ (!axiom_empty(param['action']) ? ' onchange="axiom_options_action_'+param['action']+'(this);return false;"' : '')
						+ ' />';

			break;
		
			case "switch":
				opt = [];
				i = 0;
				for (key in param['options']) {
					opt[i++] = {'key': key, 'title': param['options'][key]};
					if (i==2) break;
				}
				output += '<input name="' + id + '"'
					+ ' type="hidden"'
					+ ' value="' + axiom_shortcodes_prepare_value(axiom_empty(param['value']) ? opt[0]['key'] : param['value']) + '"'
					+ ' data-param="' + axiom_shortcodes_prepare_value(param_num) + '"'
					+ (!axiom_empty(param['action']) ? ' onchange="axiom_options_action_'+param['action']+'(this);return false;"' : '')
					+ ' />'
					+ '<span class="axiom_options_switch' + (param['value']==opt[1]['key'] ? ' axiom_options_state_off' : '') + '"><span class="axiom_options_switch_inner iconadmin-circle"><span class="axiom_options_switch_val1" data-value="' + opt[0]['key'] + '">' + opt[0]['title'] + '</span><span class="axiom_options_switch_val2" data-value="' + opt[1]['key'] + '">' + opt[1]['title'] + '</span></span></span>';
			break;

			case 'media':
				output += '<input class="axiom_options_input axiom_options_input_text axiom_options_input_media"'
					+ ' name="' + id + '"'
					+ ' id="' + id + '"'
					+ ' type="text"'
					+ ' value="' + axiom_shortcodes_prepare_value(param['value']) + '"'
					+ (!axiom_isset(param['readonly']) || param['readonly'] ? ' readonly="readonly"' : '')
					+ ' data-param="' + axiom_shortcodes_prepare_value(param_num) + '"'
					+ (!axiom_empty(param['action']) ? ' onchange="axiom_options_action_'+param['action']+'(this);return false;"' : '')
					+ ' />'
					+ before 
					+ after;
				if (!axiom_empty(param['value'])) {
					var fname = axiom_get_file_name(param['value']);
					var fext  = axiom_get_file_ext(param['value']);
					output += '<a class="axiom_options_image_preview" rel="prettyPhoto" target="_blank" href="' + param['value'] + '">' + (fext!='' && axiom_in_list('jpg,png,gif', fext, ',') ? '<img src="'+param['value']+'" alt="" />' : '<span>'+fname+'</span>') + '</a>';
				}
			break;
		
			case 'button':
				rez = axiom_shortcodes_action_button(param, 'button');
				output += rez[0];
			break;

			case 'range':
				output += '<div class="axiom_options_input_range" data-step="'+(!axiom_empty(param['step']) ? param['step'] : 1) + '">'
					+ '<span class="axiom_options_range_scale"><span class="axiom_options_range_scale_filled"></span></span>';
				if (param['value'].toString().indexOf(AXIOM_GLOBALS['shortcodes_delimiter']) == -1)
					param['value'] = Math.min(param['max'], Math.max(param['min'], param['value']));
				var sliders = param['value'].toString().split(AXIOM_GLOBALS['shortcodes_delimiter']);
				for (i=0; i<sliders.length; i++) {
					output += '<span class="axiom_options_range_slider"><span class="axiom_options_range_slider_value">' + sliders[i] + '</span><span class="axiom_options_range_slider_button"></span></span>';
				}
				output += '<span class="axiom_options_range_min">' + param['min'] + '</span><span class="axiom_options_range_max">' + param['max'] + '</span>'
					+ '<input name="' + id + '"'
						+ ' type="hidden"'
						+ ' value="' + axiom_shortcodes_prepare_value(param['value']) + '"'
						+ ' data-param="' + axiom_shortcodes_prepare_value(param_num) + '"'
						+ (!axiom_empty(param['action']) ? ' onchange="axiom_options_action_'+param['action']+'(this);return false;"' : '')
						+ ' />'
					+ '</div>';			
			break;
		
			case "checklist":
				for (key in param['options']) { 
					output += '<span class="axiom_options_listitem'
						+ (axiom_in_list(param['value'], key, AXIOM_GLOBALS['shortcodes_delimiter']) ? ' axiom_options_state_checked' : '') + '"'
						+ ' data-value="' + axiom_shortcodes_prepare_value(key) + '"'
						+ '>'
						+ param['options'][key]
						+ '</span>';
				}
				output += '<input name="' + id + '"'
					+ ' type="hidden"'
					+ ' value="' + axiom_shortcodes_prepare_value(param['value']) + '"'
					+ ' data-param="' + axiom_shortcodes_prepare_value(param_num) + '"'
					+ (!axiom_empty(param['action']) ? ' onchange="axiom_options_action_'+param['action']+'(this);return false;"' : '')
					+ ' />';
			break;
		
			case 'fonts':
				for (key in param['options']) {
					param['options'][key] = key;
				}
			case 'list':
			case 'select':
				if (!axiom_isset(param['options']) && !axiom_empty(param['from']) && !axiom_empty(param['to'])) {
					param['options'] = [];
					for (i = param['from']; i <= param['to']; i+=(!axiom_empty(param['step']) ? param['step'] : 1)) {
						param['options'][i] = i;
					}
				}
				rez = axiom_shortcodes_menu_list(param);
				if (axiom_empty(param['style']) || param['style']=='select') {
					output += '<input class="axiom_options_input axiom_options_input_select" type="text" value="' + axiom_shortcodes_prepare_value(rez[1]) + '"'
						+ ' readonly="readonly"'
						//+ (!axiom_empty(param['mask']) ? ' data-mask="'+param['mask']+'"' : '')
						+ ' />'
						+ '<span class="axiom_options_field_after axiom_options_with_action iconadmin-down-open" onchange="axiom_options_action_show_menu(this);return false;"></span>';
				}
				output += rez[0]
					+ '<input name="' + id + '"'
						+ ' type="hidden"'
						+ ' value="' + axiom_shortcodes_prepare_value(param['value']) + '"'
						+ ' data-param="' + axiom_shortcodes_prepare_value(param_num) + '"'
						+ (!axiom_empty(param['action']) ? ' onchange="axiom_options_action_'+param['action']+'(this);return false;"' : '')
						+ ' />';
			break;

			case 'images':
				rez = axiom_shortcodes_menu_list(param);
				if (axiom_empty(param['style']) || param['style']=='select') {
					output += '<div class="axiom_options_caption_image iconadmin-down-open">'
						//+'<img src="' + rez[1] + '" alt="" />'
						+'<span style="background-image: url(' + rez[1] + ')"></span>'
						+'</div>';
				}
				output += rez[0]
					+ '<input name="' + id + '"'
						+ ' type="hidden"'
						+ ' value="' + axiom_shortcodes_prepare_value(param['value']) + '"'
						+ ' data-param="' + axiom_shortcodes_prepare_value(param_num) + '"'
						+ (!axiom_empty(param['action']) ? ' onchange="axiom_options_action_'+param['action']+'(this);return false;"' : '')
						+ ' />';
			break;
		
			case 'icons':
				rez = axiom_shortcodes_menu_list(param);
				if (axiom_empty(param['style']) || param['style']=='select') {
					output += '<div class="axiom_options_caption_icon iconadmin-down-open"><span class="' + rez[1] + '"></span></div>';
				}
				output += rez[0]
					+ '<input name="' + id + '"'
						+ ' type="hidden"'
						+ ' value="' + axiom_shortcodes_prepare_value(param['value']) + '"'
						+ ' data-param="' + axiom_shortcodes_prepare_value(param_num) + '"'
						+ (!axiom_empty(param['action']) ? ' onchange="axiom_options_action_'+param['action']+'(this);return false;"' : '')
						+ ' />';
			break;

			case 'socials':
				if (!axiom_is_object(param['value'])) param['value'] = {'url': '', 'icon': ''};
				rez = axiom_shortcodes_menu_list(param);
				if (axiom_empty(param['style']) || param['style']=='icons') {
					rez2 = axiom_shortcodes_action_button({
						'action': axiom_empty(param['style']) || param['style']=='icons' ? 'select_icon' : '',
						'icon': (axiom_empty(param['style']) || param['style']=='icons') && !axiom_empty(param['value']['icon']) ? param['value']['icon'] : 'iconadmin-users-1'
						}, 'after');
				} else
					rez2 = ['', ''];
				output += '<input class="axiom_options_input axiom_options_input_text axiom_options_input_socials'
					+ (!axiom_empty(param['mask']) ? ' axiom_options_input_masked' : '') + '"'
					+ ' name="' + id + '"'
					+ ' id="' + id + '"'
					+ ' type="text" value="' + axiom_shortcodes_prepare_value(param['value']['url']) + '"' 
					+ (!axiom_empty(param['mask']) ? ' data-mask="'+param['mask']+'"' : '') 
					+ ' data-param="' + axiom_shortcodes_prepare_value(param_num) + '"'
					+ (!axiom_empty(param['action']) ? ' onchange="axiom_options_action_'+param['action']+'(this);return false;"' : '')
					+ ' />'
					+ rez2[0];
				if (!axiom_empty(param['style']) && param['style']=='images') {
					output += '<div class="axiom_options_caption_image iconadmin-down-open">'
						//+'<img src="' + rez[1] + '" alt="" />'
						+'<span style="background-image: url(' + rez[1] + ')"></span>'
						+'</div>';
				}
				output += rez[0]
					+ '<input name="' + id + '_icon' + '" type="hidden" value="' + axiom_shortcodes_prepare_value(param['value']['icon']) + '" />';
			break;

			case "color":
				output += '<input class="axiom_options_input axiom_options_input_color'+(AXIOM_GLOBALS['shortcodes_cp']=='internal' || (axiom_isset(param['style']) && param['style']=='custom') ? ' axiom_options_input_color_custom' : '')+'"'
					+ ' name="' + id + '"'
					+ ' id="' + id + '"'
					+ ' type="text"'
					+ ' value="' + axiom_shortcodes_prepare_value(param['value']) + '"'
					+ ' data-param="' + axiom_shortcodes_prepare_value(param_num) + '"'
					+ (!axiom_empty(param['action']) ? ' onchange="axiom_options_action_'+param['action']+'(this);return false;"' : '')
					+ ' />'
					+ (AXIOM_GLOBALS['shortcodes_cp']=='internal' || (axiom_isset(param['style']) && param['style']=='custom') ? '<span class="iColorPicker"></span>' : '');
			break;   
	
			}

			if (param['type'] != 'hidden') {
				output += '</div>';
				if (!axiom_empty(param['desc']))
					output += '<div class="axiom_options_desc">' + param['desc'] + '</div>' + "\n";
				output += '</div>' + "\n";
			}

		}

		output += '</div>';
	}

	
	return output;
}



// Return menu items list (menu, images or icons)
function axiom_shortcodes_menu_list(field) {
	"use strict";
	if (field['type'] == 'socials') field['value'] = field['value']['icon'];
	var list = '<div class="axiom_options_input_menu ' + (axiom_empty(field['style']) ? '' : ' axiom_options_input_menu_' + field['style']) + '">';
	var caption = '';
	for (var key in field['options']) {
		var value = field['options'][key];
		if (axiom_in_array(field['type'], ['list', 'icons', 'socials'])) key = value;
		var selected = '';
		if (axiom_in_list(field['value'], key, AXIOM_GLOBALS['shortcodes_delimiter'])) {
			caption = value;
			selected = ' axiom_options_state_checked';
		}
		list += '<span class="axiom_options_menuitem'
			+ selected 
			+ '" data-value="' + axiom_shortcodes_prepare_value(key) + '"'
			+ '>';
		if (axiom_in_array(field['type'], ['list', 'select', 'fonts']))
			list += value;
		else if (field['type'] == 'icons' || (field['type'] == 'socials' && field['style'] == 'icons'))
			list += '<span class="' + value + '"></span>';
		else if (field['type'] == 'images' || (field['type'] == 'socials' && field['style'] == 'images'))
			//list += '<img src="' + value + '" data-icon="' + key + '" alt="" class="axiom_options_input_image" />';
			list += '<span style="background-image:url(' + value + ')" data-src="' + value + '" data-icon="' + key + '" class="axiom_options_input_image"></span>';
		list += '</span>';
	}
	list += '</div>';
	return [list, caption];
}



// Return action button
function axiom_shortcodes_action_button(data, type) {
	"use strict";
	var class_name = ' axiom_options_button_' + type + (axiom_empty(data['title']) ? ' axiom_options_button_'+type+'_small' : '');
	var output = '<span class="' 
				+ (type == 'button' ? 'axiom_options_input_button'  : 'axiom_options_field_'+type)
				+ (!axiom_empty(data['action']) ? ' axiom_options_with_action' : '')
				+ (!axiom_empty(data['icon']) ? ' '+data['icon'] : '')
				+ '"'
				+ (!axiom_empty(data['icon']) && !axiom_empty(data['title']) ? ' title="'+axiom_shortcodes_prepare_value(data['title'])+'"' : '')
				+ (!axiom_empty(data['action']) ? ' onclick="axiom_options_action_'+data['action']+'(this);return false;"' : '')
				+ (!axiom_empty(data['type']) ? ' data-type="'+data['type']+'"' : '')
				+ (!axiom_empty(data['multiple']) ? ' data-multiple="'+data['multiple']+'"' : '')
				+ (!axiom_empty(data['linked_field']) ? ' data-linked-field="'+data['linked_field']+'"' : '')
				+ (!axiom_empty(data['captions']) && !axiom_empty(data['captions']['choose']) ? ' data-caption-choose="'+axiom_shortcodes_prepare_value(data['captions']['choose'])+'"' : '')
				+ (!axiom_empty(data['captions']) && !axiom_empty(data['captions']['update']) ? ' data-caption-update="'+axiom_shortcodes_prepare_value(data['captions']['update'])+'"' : '')
				+ '>'
				+ (type == 'button' || (axiom_empty(data['icon']) && !axiom_empty(data['title'])) ? data['title'] : '')
				+ '</span>';
	return [output, class_name];
}

// Prepare string to insert as parameter's value
function axiom_shortcodes_prepare_value(val) {
	return typeof val == 'string' ? val.replace(/&/g, '&amp;').replace(/"/g, '&quot;').replace(/'/g, '&#039;').replace(/</g, '&lt;').replace(/>/g, '&gt;') : val;
}
