<?php

namespace ManageServer\Job;

use Base3\Worker\Api\IJob;
use Base3\Configuration\Api\IConfiguration;
use Base3\Api\ICheck;

class ServerLoaderJob implements IJob, ICheck {

	private $configuration;
	private $nextRun = 6 * 3600;

	public function __construct(IConfiguration $configuration) {
		$this->configuration = $configuration;
	}

	// Implementation of IBase

	public static function getName(): string {
		return 'serverloaderjob';
	}

	// Implementation of IJob

	public function isActive() {
		return true;
	}

	public function getPriority() {
		return 1;
	}

	public function go() {
		$dataDir = $this->getDataDir();
		if (!strlen($dataDir)) return 'data dir undefined';

		$nextRunFile = $this->getNextRunFile();
		$now = time();
		$nextRun = is_file($nextRunFile) ? intval(file_get_contents($nextRunFile)) : 0;
		if ($now < $nextRun) return 'skipped (next run: ' . date('c', $nextRun) . ')';
		file_put_contents($nextRunFile, strval($now + $this->nextRun));

		$result = $this->getServers();
		return $result;
	}

	// Implementation of ICheck

	public function checkDependencies() {
		return array(
			'manageserver_dir_defined' => strlen($this->getDataDir()) ? 'Ok' : 'manageserver dir not defined',
			'manageserver_dir_writable' => is_writable($this->getDataDir()) ? 'Ok' : 'manageserver dir not writable'
		);
	}

	// Private methods

	private function getDataDir() {
		$directories = $this->configuration->get('directories');
		return isset($directories['data'])
			? $directories['data'] . DIRECTORY_SEPARATOR . 'manageserver' . DIRECTORY_SEPARATOR
			: '';
	}

	private function getNextRunFile(): string {
		return $this->getDataDir() . 'nextrun';
	}

private function getServers(): string {
    $dataDir = $this->getDataDir();
    if (!strlen($dataDir)) return 'data dir undefined';

    $configFile = $dataDir . 'manageserver-config.ini';
    if (!is_file($configFile)) return 'config file missing';

    $configuration = parse_ini_file($configFile, true);
    $servers = [];

    foreach ($configuration as $group => $entries) {
        if (!isset($entries['url'])) continue;
        $urls = is_array($entries['url']) ? $entries['url'] : [$entries['url']];
        foreach ($urls as $url) {

            $info = [
                'group' => $group,
                'url' => substr($url, 0, strpos($url, '?')),
                'time' => null,
                'hostname' => null,
                'uptime' => null,
                'load_average' => null,
                'disk' => null,
                'memory' => null,
                'php_version' => null
            ];

$content = @file_get_contents($url);
if ($content === false) {
    $info['error'] = 'Failed to fetch data from ' . $url;
} else {
    $data = json_decode($content, true);
    if ($data !== null) {
        $info['time'] = $data['time'];
        $info['hostname'] = $data['hostname'];
        $info['uptime'] = $data['uptime'];
        $info['load_average'] = $data['load_average'];
        $info['disk'] = $data['disk'];
        $info['memory'] = $data['memory'];
        $info['php_version'] = $data['php_version'];
    } else {
        $info['error'] = 'Invalid JSON response from ' . $url;
    }
}

            $servers[] = $info;
        }
    }

    file_put_contents($dataDir . 'servers.json', json_encode($servers, JSON_PRETTY_PRINT));
    return 'done';
}

}
