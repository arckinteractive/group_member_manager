<?php

$user = get_user(get_input('guid'));
$groups = get_input('groups');
if (is_string($groups)) {
    $groups = explode(',', $groups);
}

if (!is_array($groups)) {
	$groups = array();
}

if (!$user) {
	register_error(elgg_echo('group_member_manager:error:invalid:guid'));
	forward(REFERER);
}

$current = gmm_get_user_groups_array($user);

// get an array of groups that need to be removed
$delete = array_diff($current, $groups);

// get an array of groups that need to be added
$add = array_diff($groups, $current);

foreach ($delete as $d) {
	$group = get_entity($d);
	if ($group && $group->owner_guid == $user->guid) {
		system_message(elgg_echo('group_member_manager:cannot:remove:owner:from_group', array($group->name)));
		continue;
	}
	leave_group($d, $user->guid);
}

foreach ($add as $a) {
	join_group($a, $user->guid);
}

system_message(elgg_echo('group_member_manager:membership:updated'));
forward(REFERER);