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
