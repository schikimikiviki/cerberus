<?php

namespace OCA\Cerberus\Controller;

use OCP\AppFramework\Http\DataResponse;
use OCP\AppFramework\Controller;
use OCP\IRequest;
use OCP\Files\IRootFolder;
use OCP\IUserSession;

use OCP\DB\ISchemaWrapper;
use OCP\IDBConnection;



class UserController extends Controller {
    protected $request;
    protected $rootFolder;
    protected $userSession;
    private IDBConnection $db;


public function __construct(string $AppName,
                            IRequest $request,
                            IRootFolder $rootFolder,
                            IUserSession $userSession,
                            IDBConnection $db) {
    parent::__construct($AppName, $request);
    $this->rootFolder = $rootFolder;
    $this->userSession = $userSession;
    $this->db = $db;
}


 /**
 * @NoAdminRequired
 * @NoCSRFRequired
 */
public function getUsers(): DataResponse {
    $currentUser = $this->userSession->getUser();
    if (!$currentUser || $currentUser->getUID() !== 'admin') {
        return new DataResponse(['error' => 'Access denied'], 403);
    }

    try {
        $stmt = $this->db->prepare('SELECT uid FROM `*PREFIX*users`');
        $result = $stmt->execute();
        $users = [];

        while ($row = $result->fetch()) {
            $users[] = $row['uid'];
        }

        return new DataResponse(['users' => $users]);
    } catch (\Exception $e) {
        return new DataResponse([
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
}





}