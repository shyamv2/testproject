<div id="snackbar"></div>
<!--JQuery-->
<script src="/assets/js/jquery-1.11.3.min.js" type="text/javascript"></script>
<script src="/assets/libs/tether/dist/js/tether.min.js"></script>
<script src="/assets/libs/bootstrap/js/bootstrap.min.js"></script>
<!--Scripts -->
<script src="/assets/js/ajax.js"></script>
<script src="/assets/js/jquery.form.min.js"></script>

<script>
$(document).ready(function () {
  $('[data-toggle="tooltip"]').tooltip();
});
$(function () {
  $('[data-toggle="popover"]').popover({
    trigger: 'focus',
    html: true
  });
});
//Sidebar Collapse
$(document).ready(function () {
    // when opening the sidebar
    $('#sidebarCollapse').on('click', function () {
        // open sidebar
        $('.sidebar').addClass('active');
        // fade in the overlay
        $('.overlay').fadeIn(200);
        $('.collapse.in').toggleClass('in');
        $('a[aria-expanded=true]').attr('aria-expanded', 'false');
    });
    // if dismiss or overlay was clicked
    $('#dismiss, .overlay, #new-class-button').on('click', function () {
      // hide the sidebar
      $('.sidebar').removeClass('active');
      // fade out the overlay
      $('.overlay').fadeOut();
    });
});

//Focus on the inputs and textarea when modal is open
$(document).on('focusin', function(e) {
    if ($(event.target).closest(".cke_dialog_ui_input_text").length) {
        e.stopImmediatePropagation();
    }
    if ($(event.target).closest(".cke_dialog_ui_input_textarea").length) {
        e.stopImmediatePropagation();
    }
});
</script>

<?php if(isset($_SESSION['id']) AND $_SESSION['verified'] == 1) : ?>
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#FeedbackModal" style="position: fixed; bottom: 20px; right: 20px">
    Give Feedback
</button>

<?php include "{$_SERVER['DOCUMENT_ROOT']}/templates/includes/modals/feedback.php"; ?>
<?php endif; ?>
