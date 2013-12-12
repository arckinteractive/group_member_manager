<?php

elgg_load_css('group_member_manager');
elgg_load_js('group_member_manager');

$user = $vars['entity'];
$limit = (int)get_input('limit', 20);
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

$count = elgg_get_entities($options);
		
if ($count) {
	unset($options['count']);
	$groups = elgg_get_entities($options);
	
	echo '<div class="center">';
	echo '<table id="group-member-manager">';
	foreach ($groups as $key => $group) {
		$tr_class = ($key % 2) ? 'odd' : 'even';
		$selected = $group->isMember($user) ? ' checked="checked"' : '';
		$img = elgg_view('output/img', array(
			'src' => $group->getIconURL('tiny'),
			'style' => 'vertical-align: middle'
		));
		$link = elgg_view('output/url', array(
			'text' => $group->name,
			'href' => $group->getURL()
		));
?>

<tr class="<?php echo $tr_class; ?>">
	<td>
		<input type="checkbox" class="gmm-group-select"<?php echo $selected; ?> data-groupguid="<?php echo $group->guid; ?>" data-guid="<?php echo $user->guid; ?>">
	</td>
	<td>
		<?php echo $img . '&sdot;' . $link; ?>
	</td>
</tr>

<?php
	}
	echo '</table>';
	echo '</div>';
	
	echo elgg_view('navigation/pagination', array(
		'limit' => $limit,
		'offset' => $offset,
		'count' => $count
	));
}
else {
	echo elgg_echo('group_member_manager:nogroups');
}
