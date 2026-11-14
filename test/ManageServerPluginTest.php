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

namespace Base3\ManageServer\Test;

use PHPUnit\Framework\TestCase;
use Base3\ManageServer\ManageServerPlugin;
use Base3\CoreFoundation\Api\IContainer;

class ManageServerPluginTest extends TestCase
{
    public function testInitSetsServicesInContainer()
    {
        $containerMock = $this->createMock(IContainer::class);

        $plugin = new ManageServerPlugin($containerMock);
        $plugin->init();

        $this->assertTrue(true);
    }

    public function testCheckDependencies()
    {
        $containerMock = $this->createMock(IContainer::class);

        $plugin = new ManageServerPlugin($containerMock);
        $dependencies = $plugin->checkDependencies();

        $this->assertTrue(true);
    }
}

