<?php
namespace OCA\Cerberus\Controller;


use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\DataResponse;
use OCP\IRequest;

class TestController extends Controller {
    public function __construct(string $appName, IRequest $request) {
        parent::__construct($appName, $request);
    }

    /**
     * @NoAdminRequired
     * @NoCSRFRequired
     */
    public function hello(): DataResponse {
        return new DataResponse(['message' => 'Hello World!']);
    }
}