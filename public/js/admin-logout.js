$(document).ready(function () {
    // Handle logout link click
    $(document).on('click', '#logout-link', function (e) {
        e.preventDefault();

        // Create a form and submit it
        var form = $('<form>', {
            'method': 'POST',
            'action': '/logout'
        });

        var token = $('<input>', {
            'type': 'hidden',
            'name': '_token',
            'value': $('meta[name="csrf-token"]').attr('content')
        });

        form.append(token);
        $('body').append(form);
        form.submit();
    });
});
