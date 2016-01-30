// admin group constructor
function GFCB_CheckboxGroup(text) {
    this.text = text;
    this.value = '';
    this.isSelected = false;
    this.price = '';
    this.isCheckboxGroup = true;
}

// used when inserting a group in the admin
function GFCB_InsertFieldGroup(index){
    field = GetSelectedField();

    var new_group = new GFCB_CheckboxGroup("");
    if(window["gform_new_group_" + field.type])
        new_group = window["gform_new_group_" + field.type](field, new_group);

    field.choices.splice(index, 0, new_group);

    LoadFieldChoices(field);
    UpdateFieldChoices(GetInputType(field));
}

jQuery(function($) {

	// hooking to field choices loading
	$(document).on('gform_load_field_choices', function(event, field) {

		// should be disabled for non-select fields
		if (field.type != 'checkbox') {
			return false;
		}

		setTimeout(function() {

			field = GetSelectedField();

			// going through all select options in the admin
			$('#field_' + field.id + ' #field_choices > li').each(function(idx) {
				// preparing group
				if (field.choices[idx].isCheckboxGroup) {
					$(this).addClass('is_checkbox_group'); 
					$(this).find('.gfield_choice_checkbox').attr('disabled', 'disabled');
				}

				// newer GF version button
				var newer = $(this).find('.gf_insert_field_choice');
				if (newer.length) {
					$('<a class="gf_insert_field_group" onclick="GFCB_InsertFieldGroup(' + (idx+1) + ');"><i class="fa fa-caret-square-o-down"></i></a>').insertAfter(newer);
				}

				// older GF version button (compatibility mode)
				var older = $(this).find('.add_field_choice');
				if (older.length) {
					$('<img src="' + window.gfcb_plugin_url + '/images/icon-drop-list.png" class="add_field_group" title="add a group" alt="add a group" onclick="GFCB_InsertFieldGroup(' + (idx+1) + ');">').insertAfter(older);
				}
			});
		}, 20);

	});

});