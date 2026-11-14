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


namespace Base3\ManageServer\ContentControl;

use Base3\CoreFoundation\Api\IAssetResolver;
use Base3\CoreFoundation\Api\IMvcView;
use Base3\CoreFoundation\LinkTarget\Api\ILinkTargetService;
use Base3\Base3Manager\Service\Base3Manager;
use Base3\Base3Manager\ContentControl\AbstractContentControl;

class ServerContentControl extends AbstractContentControl {

	public function __construct(
		protected IMvcView $view,
		protected Base3Manager $base3manager,
		protected ILinkTargetService $linktargetservice,
		private readonly IAssetResolver $assetResolver
	) {
		parent::__construct($view, $base3manager, $linktargetservice);
	}

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

	protected function fillView() {
		$this->view->assign('resolve', fn($src) => $this->assetResolver->resolve($src));
	}
}
