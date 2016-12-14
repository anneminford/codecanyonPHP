<div class="modal fade" id="report" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<?php if(!User::logged_in()) { ?>
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title"><?php echo $language->global->error_message->command_denied; ?></h4>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $language->global->close;; ?></button>
				</div>
			<?php } else { ?>
			<form method="post" role="form" class="report">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title"><?php echo $language->process_reports->modal->header; ?></h4>
				</div>

				<div class="modal-body">

					<div class="form-group">
						<input type="hidden" name="token" value="<?php echo $token->hash; ?>" />
						<input type="hidden" name="type" value="" />
						<input type="hidden" name="reported_id" value="" />
					</div>

					<div class="form-group">
						<label><?php echo $language->process_reports->modal->input; ?></label>
						<textarea name="message" class="form-control" rows="4" style="resize:none;"></textarea>
					</div>

					<div class="form-group" id="report_recaptcha">

					</div>

				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $language->global->close;; ?></button>
					<button type="submit" class="btn btn-primary"><?php echo $language->global->submit_button; ?></button>
				</div>
			</form>
			<?php } ?>
		</div>
	</div>
</div>

<script>
$(document).ready(function() {
	/*Get the recaptcha code */
	$('#report').on('show.bs.modal',  function () {
		$('#recaptcha').appendTo('#report_recaptcha').show();
	});
	/* Transfer the recaptcha code */
	$('#report').on('hide.bs.modal', function () {
		$('#recaptcha').appendTo('#recaptcha_base').hide();
	});

	/* Initialize the success message variable */
	var SuccessMessage = $('#response').html();

	$('form.report').submit(function(event) {
		var $button = $(this).find(':submit');

		/* Close the modal */
		$('#report').modal('hide')
		
		/* Get the form element the submit button belongs to */
		var $form = $(this).closest('form');

		/* Get the values from elements on the specific form */
		var Data = $form.serializeArray();

		/* Insert the captcha code into the posting data */
		if($('[name="captcha"]').length) {
			var captcha = $('[name="captcha"]').val();
			Data.push({name: 'captcha', value: captcha});
		} else {
			var recaptcha_response_field = $('[name="recaptcha_response_field"]').val();
			var recaptcha_challenge_field = $('[name="recaptcha_challenge_field"]').val();
			Data.push({name: 'recaptcha_response_field', value: recaptcha_response_field}, {name: 'recaptcha_challenge_field', value: recaptcha_challenge_field});
		}

		/* Post and get response */
		$.post('processing/process_reports.php', Data, function(data) {
			$('html, body').animate({scrollTop:0},'slow');

			if(data == "success") {
				/* Display success message */
				$('#response').html(SuccessMessage).fadeIn('slow');
			} else {
				$('#response').hide().html(data).fadeIn('slow');
			}
			setTimeout(function() {
				$('#response').fadeOut('slow');
			}, 5000);

			/* Clear the textarea */
			$('textarea').val('');

			/* Reload captcha */
			if($('[name="captcha"]').length) {
				$('#captcha').attr('src', $('#captcha').attr('src')+'?timestamp=' + Math.random);
				$('[name="captcha"]').val('');
			} else {
				Recaptcha.reload();
			}
		});

		event.preventDefault();
	});

});
</script>