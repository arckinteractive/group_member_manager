<?php

$user = get_user(get_input('guid'));
$group = get_entity(get_input('groupguid'));

if (!$user || !$user->canEdit()) {
	register_error(elgg_echo('group_member_manager:error:invalid:guid'));
	forward(REFERER);
}

if (!elgg_instanceof($group, 'group')) {
	register_error(elgg_echo('group_member_manager:error:invalid:group'));
	forward(REFERER);
}

if (get_input('join', 0)) {
	join_group($group->guid, $user->guid);
	system_message(elgg_echo('group_member_manager:membership:joined', array($group->name)));
}
else {
	leave_group($group->guid, $user->guid);
	system_message(elgg_echo('group_member_manager:membership:left', array($group->name)));
}

forward(REFERER);