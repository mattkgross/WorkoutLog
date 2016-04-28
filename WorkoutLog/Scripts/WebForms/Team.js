/**
 * Vertically center Bootstrap 3 modals so they aren't always stuck at the top
 * http://www.abeautifulsite.net/vertically-centering-bootstrap-modals/
 */
$(document).ready(function () {
    // Modal Positioning.
    $(function () {
        function reposition() {
            var modal = $(this),
                dialog = modal.find('.modal-dialog');
            modal.css('display', 'block');

            // Dividing by two centers the modal exactly, but dividing by three 
            // or four works better for larger screens.
            dialog.css("margin-top", Math.max(0, ($(window).height() - dialog.height()) / 4));
        }
        // Reposition when a modal is shown
        $('.modal').on('show.bs.modal', reposition);
        // Reposition when the window is resized
        $(window).on('resize', function () {
            $('.modal:visible').each(reposition);
        });
    });

    // Disable autocomplete for form fields. This is done by setting the 'autocomplete' attribute (which is deprecated)
    // as an indicator that we want this field to not be autofilled. We also set these forms to load as readonly (which
    // will cause browsers to ignore them), and now we mark them writable.
    $('form[autocomplete="off"] input, input[autocomplete="off"]').each(function () {
        var input = this;
        setTimeout(function () {
            $(input).removeAttr('readonly');
        }, 200); // 100 does not work - too fast.
    });
});
