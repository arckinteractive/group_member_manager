<?php

$user = get_user_by_username(urldecode(get_input('username')));

if (!$user) {
	register_error(elgg_echo('group_member_manager:error:invalid:username'));
	forward(REFERER);
}

echo elgg_view_entity($user, array('full_view' => false));

$vars['entity'] = $user;

echo elgg_view_form('group_member_manager/usergroups', array(), $vars);