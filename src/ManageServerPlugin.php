<?php declare(strict_types=1);

namespace ManageServer;

use Base3\Api\IContainer;
use Base3Manager\Plugin\AbstractPlugin;

class ManageServerPlugin extends AbstractPlugin {

	// Implementation of IPlugin

	public function init() {

		$this->container
			->set($this->getName(), $this, IContainer::SHARED)
			;
	}

	// Implementation of ICheck

	public function checkDependencies(): array {
		return array(
			"Check" => "Ok"
		);
	}

}
