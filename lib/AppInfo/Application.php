<?php

declare(strict_types=1);

namespace OCA\Cerberus\AppInfo;

use OCP\AppFramework\App;
use OCP\AppFramework\Bootstrap\IBootContext;
use OCP\AppFramework\Bootstrap\IBootstrap;
use OCP\AppFramework\Bootstrap\IRegistrationContext;

use OCA\Cerberus\Controller\PermissionController;
use OCP\IRequest;
use OCP\Files\IRootFolder;
use OCP\IUserSession;

use OCA\Cerberus\Controller\TestController;

class Application extends App implements IBootstrap {
	public const APP_ID = 'cerberus';

	public function __construct() {
		parent::__construct(self::APP_ID);
	}

	public function register(IRegistrationContext $context): void {
		$container = $this->getContainer();
		
		// Register controllers
		$context->registerService('PermissionController', function($c) {
			return new PermissionController(
				self::APP_ID,
				$c->query(IRequest::class),
				$c->query(IRootFolder::class),
				$c->query(IUserSession::class)
			);
		});
		
		$context->registerService('TestController', function($c) {
			return new TestController(
				self::APP_ID,
				$c->query(IRequest::class)
			);
		});
		
		// Register routes
		$context->registerRoute('test.hello', '/hello', [
			'_controller' => 'TestController#hello',
			'_action' => 'hello',
			'_verb' => 'GET'
		]);
	}

	public function boot(IBootContext $context): void {
	}
}
