<?php declare(strict_types=1);

/***********************************************************************
 * This file is part of ManageServer for BASE3 Framework.
 *
 * ManageServer provides server administration controls.
 * Its views are integrated into Base3Manager.
 * Server operations keep their established service boundaries.
 *
 * Developed by Daniel Dahme
 * Licensed under GPL-3.0
 * https://www.gnu.org/licenses/gpl-3.0.en.html
 *
 * https://base3.de
 ***********************************************************************/


namespace Base3\ManageServer;

use Base3\CoreFoundation\Api\IContainer;
use Base3\Base3Manager\Plugin\AbstractPlugin;

class ManageServerPlugin extends AbstractPlugin {

	// Implementation of IPlugin

	public function init() {

		$this->container
			->set(self::getName(), $this, IContainer::SHARED);
	}

	// Implementation of ICheck

	public function checkDependencies(): array {
		return array(
			"Check" => "Ok"
		);
	}
}
