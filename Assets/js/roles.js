$(function () {
    //init switcher
    $('.single-entries-changeable-state').each(function(i, switcher) {
        new Switchery(switcher);
    });

    //init editable

    $('.js-role-name').editable({
        type: 'text'
    });
    // custom x-editable buttons
    $.fn.editableform.buttons = '<button type="submit" class="btn btn-primary btn-xs editable-submit">Save</button>' +
        '<button type="button" class="btn btn-xs editable-cancel">Cancel</button>';


    // adding new role
    var form = $('.js-form-submit');
    form.find('input[type="submit"]').prop('disabled', false);

    form.on('submit', function (e) {
        e.preventDefault();
        var form = $(this);
        var dangerAlert = form.find('.alert-danger');
        var successAlert = form.find('.alert-success');

        dangerAlert.addClass('hidden').empty();

        $.ajax({
            type: 'POST',
            url: form.attr('action'),
            data: form.serializeArray(),
            success: function(response) {
                successAlert.hide().removeClass('hidden').text(response.message).fadeIn();

                if(response.redirect != undefined) {
                    setTimeout(function () {
                        window.location = response.redirect;
                    }, 1000);
                }

                form.find('input[type="submit"]').prop('disabled', false);
            },
            error: function (response) {
                var errors = response.responseJSON.errors;
                var errorList = '';
                $.each(errors, function (field, error) {
                    console.log(error);
                    errorList += '<li>' + error[0] + '</li>'
                });
                dangerAlert.hide().removeClass('hidden').html(errorList).fadeIn();
                form.find('input[type="submit"]').prop('disabled', false);
            },
            beforeSend: function () {
                form.find('input[type="submit"]').prop('disabled', true);
            }
        });
    });
});