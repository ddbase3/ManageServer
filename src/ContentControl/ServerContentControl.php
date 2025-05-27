<?php declare(strict_types=1);

namespace ManageServer\ContentControl;

use Base3Manager\ContentControl\AbstractContentControl;

class ServerContentControl extends AbstractContentControl {

        // Implementation of IBase

        public static function getName(): string {
                return "servercontentcontrol";
        }

	// Implementation of AbstractContentControl

        protected function getPath(): string {
                return DIR_PLUGIN . 'ManageServer';
        }

        protected function getTemplate(): string {
                return 'ContentControl/ServerContentControl.php';
        }

}

