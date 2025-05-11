<?php

namespace ManageServer\Connector;

use Base3\Api\IOutput;
use Base3\Configuration\Api\IConfiguration;
use Base3\Accesscontrol\Api\IAccesscontrol;

class ServerConnector implements IOutput {

    private $configuration;
    private $accesscontrol;
    private $defaultPageSize = 10;

    public function __construct(
        IAccesscontrol $accesscontrol,
        IConfiguration $configuration
    ) {
        $this->accesscontrol = $accesscontrol;
        $this->configuration = $configuration;
    }

    // Implementation of IBase

    public function getName() {
        return "serverconnector";
    }

    // Implementation of IOutput

    public function getOutput($out = "html") {
        if ($out !== "json") return null;
        if (!$this->accesscontrol->getUserId()) return null;

        $directories = $this->configuration->get('directories');
        $datadir = $directories['data'];

        $file = $datadir . DIRECTORY_SEPARATOR . 'manageserver' . DIRECTORY_SEPARATOR . 'servers.json';
        if (!file_exists($file)) {
            return json_encode(['error' => true, 'message' => 'File not found']);
        }

        $json = file_get_contents($file);
        $servers = json_decode($json, true);
        if (!is_array($servers)) {
            return json_encode(['error' => true, 'message' => 'Invalid JSON format']);
        }

        // Boolesche Werte in Strings umwandeln
        array_walk_recursive($servers, function (&$value, $key) {
            if (is_bool($value)) {
                $value = $value ? 'true' : 'false';
            }
        });

        // details
foreach ($servers as $k => $server) {
    if (isset($server['load_average']) && is_array($server['load_average'])) {
        $loadCnt = 0;
        $loadAverage = 0;
        foreach ($server['load_average'] as $l) {
            $loadCnt++;
            $loadAverage += $l;
        }
        $servers[$k]['load_average'] = round($loadAverage / $loadCnt, 2);
    } else {
        $servers[$k]['load_average'] = null;  // Setze auf null oder einen anderen Default-Wert
    }

    if (isset($server['disk']) && is_array($server['disk'])) {
        $servers[$k]['disk_total_kb'] = isset($server['disk']['total_kb']) ? $server['disk']['total_kb'] : null;
        $servers[$k]['disk_free_kb'] = isset($server['disk']['free_kb']) ? $server['disk']['free_kb'] : null;
        unset($servers[$k]['disk']);
    } else {
        $servers[$k]['disk_total_kb'] = null;
        $servers[$k]['disk_free_kb'] = null;
    }

    if (isset($server['memory']) && is_array($server['memory'])) {
        $servers[$k]['memory_total_kb'] = isset($server['memory']['total_kb']) ? $server['memory']['total_kb'] : null;
        $servers[$k]['memory_used_kb'] = isset($server['memory']['used_kb']) ? $server['memory']['used_kb'] : null;
        $servers[$k]['memory_free_kb'] = isset($server['memory']['free_kb']) ? $server['memory']['free_kb'] : null;
        $servers[$k]['memory_available_kb'] = isset($server['memory']['available_kb']) ? $server['memory']['available_kb'] : null;
        unset($servers[$k]['memory']);
    } else {
        $servers[$k]['memory_total_kb'] = null;
        $servers[$k]['memory_used_kb'] = null;
        $servers[$k]['memory_free_kb'] = null;
        $servers[$k]['memory_available_kb'] = null;
    }
}

        // Sortierung
        $sort = $_GET['sort'] ?? 'url';
        $direction = strtolower($_GET['direction'] ?? 'asc');
        usort($servers, function ($a, $b) use ($sort, $direction) {
            $aVal = strtolower($a[$sort] ?? '');
            $bVal = strtolower($b[$sort] ?? '');
            return ($direction === 'desc' ? -1 : 1) * strcmp($aVal, $bVal);
        });

        // Filter
        $filters = $_GET['filter'] ?? [];
        $servers = array_filter($servers, function ($server) use ($filters) {
            foreach ($filters as $key => $val) {
                if (!isset($server[$key])) return false;
                if (stripos($server[$key], $val) === false) return false;
            }
            return true;
        });

        // Paging
        $total = count($servers);
        $pageSize = $this->defaultPageSize;
        $totalPages = ceil($total / $pageSize);
        $page = min(max(1, intval($_GET['page'] ?? 1)), $totalPages);
        $offset = ($page - 1) * $pageSize;
        $pagedData = array_slice($servers, $offset, $pageSize);

        return json_encode([
            'total' => $total,
            'page' => $page,
            'pageSize' => $pageSize,
            'totalPages' => $totalPages,
            'data' => $pagedData
        ]);
    }

    public function getHelp() {
        return "Liefert eine Liste gecrawlter Server als JSON (aus servers.json). Optional: ?sort=url&direction=asc&page=1&filter[key]=value";
    }
}

