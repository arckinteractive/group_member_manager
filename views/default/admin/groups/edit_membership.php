<?php

$guid = get_input('guid');
$group = get_entity($guid);

if (!$group || !elgg_instanceof($group, 'group')) {
	register_error(elgg_echo('group_member_manager:error:invalid:guid'));
	forward(REFERER);
}

$vars['group'] = $group;

echo elgg_view_form('group_member_manager/update_membership', array(), $vars);