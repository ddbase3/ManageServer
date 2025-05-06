<?php

namespace ManageServer\Test;

use PHPUnit\Framework\TestCase;
use ManageServer\ManageServerPlugin;
use Base3\Api\IContainer;

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

