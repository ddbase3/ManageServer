<div id="ServerContentControl" class="contentcontrol">
        <div id="wrap">
                <div id="datatable"></div>
        </div>
</div>

<script>
	(async function () {
		await AssetLoader.loadCssAsync('<?php echo $this->_['resolve']('plugin/ClientStack/assets/jquerydatatable/jquery.datatable.min.css'); ?>');
		await AssetLoader.loadScriptAsync('<?php echo $this->_['resolve']('plugin/ClientStack/assets/jquerydatatable/jquery.datatable.min.js'); ?>');

		var ajaxUrl = '?name=serverconnector&out=json';

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

		$('#datatable').jqueryDataTable({
			dataSource: ajaxUrl,
			columns: columns,
			pageSize: 10,
			sortColumn: columns[0]?.key ?? null,
			sortDirection: 'asc',
			layoutTargets: {
				'.header-left': ['columnSelector'],
				'.header-right': ['compactPager'],
				'.footer-left': ['resetButton'],
				'.footer-center': ['info'],
				'.footer-right': ['pageSizeSelector']
			}
		});
	})();
</script>


