<?php

elgg_register_event_handler('init', 'system', 'group_member_manager_init');

function group_member_manager_init() {
	
	$css = elgg_get_simplecache_url('css', 'group_member_manager/css');
	elgg_register_simplecache_view('css/group_member_manager/css');
	elgg_register_css('group_member_manager', $css);
	
	elgg_register_admin_menu_item('administer', 'groups');
	elgg_register_admin_menu_item('administer', 'member_manager', 'groups');
	
	
	elgg_register_action('group_member_manager/update_membership', dirname(__FILE__) . '/actions/membership_update.php', 'admin');
}


function gmm_get_group_membership_array($group) {
	if (!elgg_instanceof($group, 'group')) {
		return array();
	}
	
	$dbprefix = elgg_get_config('dbprefix');
	// get an array of guids for all members of the group
	// this could be a memory intensive search, so use no callback
	$options = array(
		'type' => 'user',
		'relationship' => 'member',
		'relationship_guid' => $group->guid,
		'inverse_relationship' => true,
		'joins' => array("JOIN {$dbprefix}users_entity ue ON e.guid = ue.guid"),
		'order_by' => 'ue.name ASC',
		'limit' => false,
		'callback' => false
	);
	
	$batch = new ElggBatch('elgg_get_entities_from_relationship', $options, null, 100);

	$selected = array();
	foreach ($batch as $m) {
		// note $m is not a user entity
		$selected[] = $m->guid;
	}
	
	return $selected;
}