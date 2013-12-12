//<script>

elgg.provide('elgg.gmm');

elgg.gmm.init = function() {
	
	$('input.gmm-group-select').live('click', function(e) {
		var userguid = $(this).attr('data-guid');
		var groupguid = $(this).attr('data-groupguid');
		var join = 0;
		
		if ($(this).is(':checked')) {
			join = 1;
		}
		
		elgg.action('group_member_manager/usergroups', {
			data: {
				guid: userguid,
				groupguid: groupguid,
				join: join
			}
		});
	});

}

elgg.register_hook_handler('init', 'system', elgg.gmm.init);