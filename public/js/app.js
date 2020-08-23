var saving_event = false;
var event_list = null;

$(document).ready(function() {

    event_list = $('#event_list').DataTable({
        ajax: {
            url: '/api.php?c=event&m=getevents',
            dataSrc: 'events'
        },
        columns: [
            { data: 'event_id' },
            { data: 'event_name' },
            { data: 'event_datetime' },
            { data: 'school_name' },
            { data: 'venue_name' },
            { data: 'category_name' },
            { data: 'distance' },
            { data: 'travel_time' },
            { data: 'organisers' },
            { data: 'participants' },
            { data: 'attendees' }
        ]
    });

    $('#event_list tbody').on('click', 'tr', function () {
        var data = event_list.row( this ).data();
        getEvent(data.event_id);
    } );


    $('.select2').select2().on('change', function() {
        $(this).valid();
    });

    $('#event_form').validate({
        errorElement: 'span',
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        },
        submitHandler: function (form) {
            if( !saving_event ){
                saving_event = true;
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: $(form).serialize(),
                    success: function (response) {
                        saving_event = false;
                        $(".alert_success").addClass('alert-hidden');
                        if( response.event_id ){
                            $("#alert_success").html('Event saved').removeClass('alert-hidden');
                            $("#event_form").find('select').each(function(){
                                $(this).val(null).change;
                            });
                            clearForm();
                            event_list.ajax.reload();
                        } else if( response.error ){
                            $("#alert_error").html(response.error).removeClass('alert-hidden');
                        }
                    }
                });
            }
        }
    });

    $('#venue_form').validate({
        errorElement: 'span',
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        },
        submitHandler: function (form) {
            if( !saving_event ){
                saving_event = true;
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: $(form).serialize(),
                    success: function (response) {
                        saving_event = false;

                        if( response.venue.venue_id ) {
                            $("#new_venue_id").val('0');
                            $("#new_venue_name").val('');
                            $("#new_venue_address").val('');
                            $("#modal_venue").modal('hide');
                            var data = {
                                id: response.venue.venue_id,
                                text: response.venue.venue_name
                            };
                            var newOption = new Option(data.text, data.id, false, false);
                            $('#venue_id').append(newOption).val(response.venue.venue_id).trigger('change');
                        } else {
                            alert('Failed to add a new event');
                        }
                    }
                });
            }
        }
    });

    $('#category_form').validate({
        errorElement: 'span',
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        },
        submitHandler: function (form) {
            if( !saving_event ){
                saving_event = true;
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: $(form).serialize(),
                    success: function (response) {
                        saving_event = false;

                        if( response.category.category_id ) {
                            $("#new_category_id").val('0');
                            $("#new_category_name").val('');
                            $("#modal_category").modal('hide');
                            var data = {
                                id: response.category.category_id,
                                text: response.category.category_name
                            };
                            var newOption = new Option(data.text, data.id, false, false);
                            $('#category_id').append(newOption).val(response.category.category_id).trigger('change');
                        } else {
                            alert('Failed to add a new event');
                        }
                    }
                });
            }
        }
    });

    $('#organiser_form').validate({
        errorElement: 'span',
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        },
        submitHandler: function (form) {
            if( !saving_event ){
                saving_event = true;
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: $(form).serialize(),
                    success: function (response) {
                        saving_event = false;

                        if( response.organiser.organiser_id ) {
                            $("#new_organiser_id").val('0');
                            $("#new_organiser_name").val('');
                            $("#modal_organiser").modal('hide');
                            var data = {
                                id: response.organiser.organiser_id,
                                text: response.organiser.organiser_name
                            };
                            var newOption = new Option(data.text, data.id, false, false);
                            $('#organiser_id').append(newOption).val(response.organiser.organiser_id).trigger('change');
                        } else {
                            alert('Failed to add a new event');
                        }
                    }
                });
            }
        }
    });


    $('#event_datetime').datetimepicker({
        format:'d/m/Y H:i'
    });

});

function getEvent( event_id ) {
    clearFeedback();
    clearForm();
    $.ajax({
        method: "POST",
        url: "api.php",
        data: {
            c: 'event',
            m: 'getEvent',
            event_id: event_id
        }
    }).done(function( data ) {
        $("#event_id").val(data.event.event_id);
        $("#event_name").val(data.event.event_name);
        $("#event_datetime").val(data.event.event_datetime);
        $("#description").val(data.event.description);
        $("#school_id").val(data.event.school_id).trigger('change.select2');
        $("#venue_id").val(data.event.venue_id).trigger('change.select2');
        $("#category_id").val(data.event.category_id).trigger('change.select2');

        $("#organiser_id").val(data.event.organisers).change();
        $("#participant_id").val(data.event.participants).change();
        $("#attendee_id").val(data.event.attendees).change();

        $("#bt_save, #bt_new, #bt_delete").show();
    });
}

function clearForm() {
    $("#event_id").val(0);
    $("#school_id").val(null).change();
    $("#venue_id").val(null).change();
    $("#category_id").val(null).change();
    $("#organiser_id").val(null).change();
    $("#participant_id").val(null).change();
    $("#attendee_id").val(null).change();
    $("#event_form")[0].reset();
    $("#event_form").find('.error').hide();

    $("#bt_save").show();
    $("#bt_new, #bt_delete").hide();
}

function clearFeedback() {
    $("#alert_success, #alert_error").addClass('alert-hidden');
}

function confirmDeleteEvent() {
    $("#modal_delete_confirm").modal('show');
}

function deleteEvent() {
    $.ajax({
        method: "POST",
        url: "api.php",
        data: {
            c: 'event',
            m: 'deleteEvent',
            event_id: $("#event_id").val()
        }
    }).done(function( data ) {
        $("#alert_success").html('Event deleted').removeClass('alert-hidden');
        clearForm();
        event_list.ajax.reload();
    });
}

function showModal( modal ) {
    $("#modal_"+modal).modal('show');
}