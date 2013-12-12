<?php

elgg_register_event_handler('init', 'system', 'group_member_manager_init');

function group_member_manager_init() {
	
	$css = elgg_get_simplecache_url('css', 'group_member_manager/css');
	elgg_register_simplecache_view('css/group_member_manager/css');
	elgg_register_css('group_member_manager', $css);
	
	$js = elgg_get_simplecache_url('js', 'group_member_manager/js');
	elgg_register_simplecache_view('js/group_member_manager/js');
	elgg_register_js('group_member_manager', $js);
	
	elgg_register_admin_menu_item('administer', 'groups');
	elgg_register_admin_menu_item('administer', 'member_manager', 'groups');
	
	elgg_register_plugin_hook_handler('register', 'menu:user_hover', 'group_member_manager_user_hover');
	
	
	elgg_register_action('group_member_manager/update_membership', dirname(__FILE__) . '/actions/membership_update.php', 'admin');
	elgg_register_action('group_member_manager/usergroups', dirname(__FILE__) . '/actions/usergroups.php', 'admin');
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


function gmm_get_user_groups_array($user) {
	if (!elgg_instanceof($user, 'user')) {
		return array();
	}
	
	$dbprefix = elgg_get_config('dbprefix');
	// get an array of guids for all members of the group
	// this could be a memory intensive search, so use no callback
	$options = array(
		'type' => 'group',
		'relationship' => 'member',
		'relationship_guid' => $user->guid,
		'joins' => array("JOIN {$dbprefix}groups_entity ge ON e.guid = ge.guid"),
		'order_by' => 'ge.name ASC',
		'limit' => false,
		'callback' => false
	);
	
	$batch = new ElggBatch('elgg_get_entities_from_relationship', $options, null, 100);

	$selected = array();
	foreach ($batch as $g) {
		// note $m is not a user entity
		$selected[] = $g->guid;
	}
	
	return $selected;
}


function group_member_manager_user_hover($hook, $type, $return, $params) {
	if (!elgg_is_admin_logged_in()) {
		return $return;
	}
	
	$href = 'admin/users/managegroups?username=' . urlencode($params['entity']->username);
	$item = new ElggMenuItem('group_member_manager', elgg_echo('group_member_manager:user_hover'), $href);
	$item->setSection('admin');
	
	$return[] = $item;
	
	return $return;
}


function group_member_manager_group_search($query, $options = array()) {
	$query = sanitize_string($query);
	
	$dbprefix = elgg_get_config('dbprefix');
	
	return elgg_get_entities(array(
		'type' => 'group',
		'joins' => array("JOIN {$dbprefix}groups_entity ge ON e.guid = ge.guid"),
		'wheres' => array("ge.name LIKE '%{$query}%'"),
		'order_by' => 'ge.name ASC'
	));
}