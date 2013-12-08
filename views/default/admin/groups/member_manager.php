<?php

elgg_load_css('group_member_manager');

$limit = (int)get_input('limit', 10);
$offset = (int)get_input('offset', 0);
$dbprefix = elgg_get_config('dbprefix');

$options = array(
	'type' => 'group',
	'limit' => $limit,
	'offset' => $offset,
	'joins' => array("JOIN {$dbprefix}groups_entity ge ON e.guid = ge.guid"),
	'order_by' => 'ge.name ASC',
	'count' => true
);

echo elgg_view('output/url', array(
	'text' => elgg_echo('groups:add'),
	'href' => 'groups/add/' . elgg_get_logged_in_user_guid(),
	'class' => 'elgg-button elgg-button-action'
));


$count = elgg_get_entities($options);

if ($count) {
	unset($options['count']);
	$groups = elgg_get_entities($options);
?>
<div class="center">
<table id="group-member-manager">
	<tr>
		<td>
			<!-- delete button here -->
		</td>
		<td>
			<strong><?php echo elgg_echo('group_member_manager:group_name'); ?></strong>
		</td>
		<td>
			<strong><?php echo elgg_echo('group_member_manager:members'); ?></strong>
		</td>
	</tr>
		
<?php

foreach ($groups as $key => $group) {
	$tr_class = ($key % 2) ? 'odd' : 'even';
	echo '<tr class="' . $tr_class . '">';
	echo '<td>';
	echo elgg_view('output/url', array(
		'text' => elgg_echo('group_member_manager:edit_membership'),
		'href' => 'admin/groups/edit_membership?guid=' . $group->guid,
		'class' => 'elgg-button elgg-button-action'
	));
	
	echo elgg_view('output/url', array(
		'text' => elgg_echo('delete'),
		'href' => 'action/groups/delete?guid=' . $group->guid,
		'is_action' => true,
		'is_trusted' => true,
		'class' => 'elgg-button elgg-button-action elgg-requires-confirmation'
	));
	echo '</td>';
	echo '<td>';
	echo elgg_view('output/url', array(
		'text' => $group->name,
		'href' => $group->getURL()
	));
	echo '</td>';
	echo '<td>';
	$member_count = $group->getMembers(10,0,true);
	
	$remaining_str = '';
	if ($member_count > 10) {
		$remaining = $member_count - 10;
		$remaining_str = elgg_echo('group_member_manager:remaining', array($remaining));
	}
	
	$members = elgg_get_entities_from_relationship(array(
		'type' => 'user',
		'relationship' => 'member',
		'relationship_guid' => $group->guid,
		'inverse_relationship' => true,
		'limit' => 10
	));
	
	foreach ($members as $member) {
		echo elgg_view_entity_icon($member, 'tiny');
	}
	
	if ($remaining_str) {
		echo elgg_view('output/longtext', array(
			'value' => $remaining_str,
			'class' => 'elgg-subtext'
		));
	}
	echo '</td>';
	echo '</tr>';
}

echo '</table></div>';

echo elgg_view('navigation/pagination', array(
	'limit' => $limit,
	'offset' => $offset,
	'count' => $count
));

}
else {
	echo elgg_view('output/longtext', array(
		'value' => elgg_echo('group_member_manager:noresults'),
		));
}