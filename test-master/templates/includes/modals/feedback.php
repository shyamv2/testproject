<div class="modal fade" id="FeedbackModal" tabindex="-1" role="dialog" aria-labelledby="FeedbackLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="FeedbackLabel">Give Feedback</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                    <div class="form-group">
                        <label for="message-text" class="col-form-label">Message:</label>
                        <textarea class="form-control" name="feedback" id="feedback-text" placeholder="Everything is welcome, including suggestions and bug reports..."></textarea>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" onclick='giveFeedback(this)' class="btn btn-primary">Send Message</button>
            </div>
        </div>
    </div>
</div>
