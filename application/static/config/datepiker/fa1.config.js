$(function() {
    $(DatePickerElementAreaId).datepicker({
        dateFormat: 'y/mm/dd',
        showButtonPanel: true,
        autoSize: true,
        changeMonth: true,
        changeYear: true,
        showOn: 'button',
        buttonImage: PenguDatePickerPluginUrl+'/styles/images/calendar.png',
        buttonImageOnly: true
    });
 
});                        