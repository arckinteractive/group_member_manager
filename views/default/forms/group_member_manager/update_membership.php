<?php

$group = $vars['group'];

echo elgg_view('input/userpicker', array(
	'name' => 'members',
	'value' => gmm_get_group_membership_array($group)
));


echo elgg_view('input/hidden', array('name' => 'guid', 'value' => $group->guid));
echo elgg_view('input/submit', array('value' => elgg_echo('submit')));