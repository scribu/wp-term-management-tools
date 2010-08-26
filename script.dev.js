jQuery(document).ready(function($) {
	var l10n = window.tmtL10n,
	    actions = ['merge'];

	if ( l10n.hierarchical )
		actions.push('set_parent');

	$.each(actions, function(i, val) {
		actions[i] = {
			action: 'bulk_' + val,
			name: l10n[val],
			el: $('#tmt-input-' + val)
		}
	});

	$('.actions select')
	.each(function() {
		var $option = $(this).find('option:first');

		$.each(actions, function() {
			$option.after($('<option>', {value: this.action, html: this.name}));
		});
	})
	.change(function() {
		var $self = $(this);

		$.each(actions, function() {
			if ( $self.val() == this.action ) {
				this.el.insertAfter($self).css('display', 'inline').find(':input').focus();
			} else {
				this.el.css('display', 'none');
			}
		});
	});
});

