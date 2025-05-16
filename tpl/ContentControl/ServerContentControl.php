<div id="ServerContentControl" class="contentcontrol">
        <div id="wrap">
                <div id="serverDatatable"></div>
        </div>
</div>

<script>
	var columns = [
		{ key: 'group', label: 'Gruppe' },
		{ key: 'url', label: 'URL' },
		{ key: 'time', label: 'Server-Zeit' },
		{ key: 'hostname', label: 'Hostname' },
		{ key: 'uptime', label: 'Uptime' },
		{ key: 'load_average', label: 'Load' },
		{ key: 'php_version', label: 'PHP' },
		{ key: 'disk_total_kb', label: 'Disk tot' },
		{ key: 'disk_free_kb', label: 'Disk free' },
		{ key: 'memory_total_kb', label: 'Mem tot' },
		{ key: 'memory_used_kb', label: 'Mem used' },
		{ key: 'memory_free_kb', label: 'Mem free' },
		{ key: 'memory_available_kb', label: 'Mem avl' }
	];

	$('#serverDatatable').jqueryDataTable({
		dataSource: '?name=serverconnector&out=json',
		columns: columns,
		sortColumn: 'url',
		sortDirection: 'asc',
		pageSize: 20,
		onRowClick: function(row) {
			console.log('Zeile geklickt:', row);
		}
	});
</script>


