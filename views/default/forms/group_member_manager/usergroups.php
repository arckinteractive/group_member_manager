<?php

$user = $vars['entity'];

$groups = gmm_get_user_groups_array($user);

echo elgg_view('input/tokeninput', array(
        'value' => $groups, // An array of values (guids or entities) to pre-populate the input with
        'name' => 'groups',
        'callback' => 'group_member_manager_group_search',
        'multiple' => true
    ));


echo elgg_view('input/hidden', array('name' => 'guid', 'value' => $user->guid));

echo elgg_view('input/submit', array('value' => elgg_echo('submit')));