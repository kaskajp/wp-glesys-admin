jQuery(function($) {

	var cmd = $('#cmd');
	var emailaccount = $('#emailaccount');
	var hiddenemailaccount = $('#hiddenemailaccount');

	var hiddenemailalias = $('#hiddenemailalias');

	// Change password
	$('.change-password').on('click', function(event){
		event.preventDefault();

		$('.column-password').find('.save').remove();
		$('.column-password').find('.newpassword').remove();
		$('.column-password').find('.change-password').show(0);

		$('.column-quota').find('.save').remove();
		$('.column-quota').find('.newquota').remove();
		$('.column-quota').find('.button').show(0);

		var button = $(this);

		cmd.val('change_password');
		hiddenemailaccount.val(button.attr('data-emailaccount'));

		button.hide(0);
		$('<input type="button" class="button save" name="" value="Save" />').insertAfter(button);
		$('<input type="text" class="newpassword" name="newpassword" placeholder="Enter new password" value="" />').insertAfter(button);

	});

	// Change quota
	$('.change-quota').on('click', function(event){
		event.preventDefault();

		$('.column-quota').find('.save').remove();
		$('.column-quota').find('.newquota').remove();
		$('.column-quota').find('.button').show(0);

		$('.column-password').find('.save').remove();
		$('.column-password').find('.newpassword').remove();
		$('.column-password').find('.change-password').show(0);

		var button = $(this);

		cmd.val('change_quota');
		hiddenemailaccount.val(button.attr('data-emailaccount'));

		button.hide(0);
		$('<input type="button" class="button save" name="" value="Save" />').insertAfter(button);
		$('<input type="text" class="newquota" name="newquota" placeholder="Quota" value="" />').insertAfter(button);

	});

	// Add new account
	$('#add_new_account').on('click', function(event){
		event.preventDefault();

		cmd.val('create_account');
		$('#create-new-account-form').toggle(0);
		
	});

	// Delete account
	$('.delete-account').on('click', function(event){
		event.preventDefault();
		var button = $(this);

		var really = confirm('Delete ' + button.attr('data-emailaccount') + '?');
		if(really) {
			hiddenemailaccount.val(button.attr('data-emailaccount'));
			cmd.val('delete_account');
			$('#cmd').trigger('click');
		}
	});

	// Add new alias
	$('#add_new_alias').on('click', function(event){
		event.preventDefault();

		cmd.val('create_alias');
		$('#create-new-alias-form').toggle(0);
		
	});

	// Save email alias
	$('.edit-goto').on('click', function(event){
		event.preventDefault();

		$('.column-email-goto').find('.save').remove();
		$('.column-email-goto').find('.forward-to').remove();
		$('.column-email-goto').find('.current-goto').show(0);
		$('.column-email-goto').find('.edit-goto').show(0);

		var button = $(this);
		var currentGoto = button.siblings('.current-goto');

		cmd.val('save_alias');
		hiddenemailalias.val(button.attr('data-emailalias'));

		button.hide(0);
		currentGoto.hide(0);
		$('<input type="button" class="button save" name="" value="Save" />').insertAfter(button);
		$('<input type="text" class="forward-to" name="goto" placeholder="Email addresses" value="'+currentGoto.text()+'" />').insertAfter(button);
	});

	// Delete alias
	$('.delete-alias').on('click', function(event){
		event.preventDefault();
		var button = $(this);

		var really = confirm('Delete ' + button.attr('data-emailalias') + '?');
		if(really) {
			hiddenemailalias.val(button.attr('data-emailalias'));
			cmd.val('delete_alias');
			$('#cmd').trigger('click');
		}
	});

	// Trigger click on submit button when you click on save buttons
	$('body').on('click', '.save', function(){
		$('#cmd').trigger('click');
	});

}); 