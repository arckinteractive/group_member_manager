<?php
set_time_limit(0); // just in case this is a very large group

$guid = get_input('guid');
$group = get_entity($guid);
$owner = $group->getOwnerEntity();
$members = get_input('members', array()); // end result of members

if (!$group || !elgg_instanceof($group, 'group')) {
	register_error(elgg_echo('group_member_manager:error:invalid:guid'));
	forward(REFERER);
}

// get list of current members
$current = gmm_get_group_membership_array($group);

// get an array of members that need to be removed
$delete = array_diff($current, $members);

// get an array of members that need to be added
$add = array_diff($members, $current);

foreach ($delete as $d) {
	if ($owner->guid == $d) {
		// the owner can't be removed
		system_message(elgg_echo('group_member_manager:cannot:remove:owner', array($owner->name)));
		continue;
	}
	
	leave_group($group->guid, $d);
}


foreach ($add as $a) {
	join_group($group->guid, $a);
}


system_message(elgg_echo('group_member_manager:membership:updated'));
forward(REFERER);