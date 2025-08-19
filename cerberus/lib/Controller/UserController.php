<?php

namespace OCA\Cerberus\Controller;

use OCP\AppFramework\Http\DataResponse;
use OCP\AppFramework\Controller;
use OCP\IRequest;
use OCP\IUserSession;

use OCP\IDBConnection; 

class UserController extends Controller {
    protected $request;
    protected $userSession;
    protected $db; 

    public function __construct(string $AppName, IRequest $request, IUserSession $userSession,  IDBConnection $db) {
        parent::__construct($AppName, $request);
        $this->request = $request;
        $this->userSession = $userSession;
        $this->db = $db;
    }

 /**
 * @NoAdminRequired
 * @NoCSRFRequired
 */
public function getUsersAndGroups(): DataResponse {


    // only let admin fetch
    $currentUser = $this->userSession->getUser();
    if (!$currentUser || $currentUser->getUID() !== 'root') {
        return new DataResponse(['error' => 'Access denied'], 403);
    }

    try {
        $path = $this->request->getParam('path', '');
       
        // generiert eine Liste aus allen Nutzerinnen und Gruppen
        $stmt = $this->db->prepare('SELECT uid AS id FROM oc_users UNION SELECT gid AS id FROM oc_groups;');

    

    $result = $stmt->execute([$path]);

    $rows = [];

    while ($row = $result->fetch()) {
        $rows[] = $row;
    }

    return new DataResponse(['result' => $rows]);
    } catch (\Exception $e) {
        return new DataResponse([
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
}

/**
 * @NoAdminRequired
 * @NoCSRFRequired
 */
public function getUsers(): DataResponse {


    // only let admin fetch
    $currentUser = $this->userSession->getUser();
    if (!$currentUser || $currentUser->getUID() !== 'root') {
        return new DataResponse(['error' => 'Access denied'], 403);
    }

    try {
        $path = $this->request->getParam('path', '');
       
        // generiert eine Liste aus allen Nutzerinnen 
        $stmt = $this->db->prepare('SELECT uid AS id FROM oc_users;');

    

    $result = $stmt->execute([$path]);

    $rows = [];

    while ($row = $result->fetch()) {
        $rows[] = $row;
    }

    return new DataResponse(['result' => $rows]);
    } catch (\Exception $e) {
        return new DataResponse([
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
}

/**
 * @NoAdminRequired
 * @NoCSRFRequired
 */
public function getGroups(): DataResponse {


    // only let admin fetch
    $currentUser = $this->userSession->getUser();
    if (!$currentUser || $currentUser->getUID() !== 'root') {
        return new DataResponse(['error' => 'Access denied'], 403);
    }

    try {
        $path = $this->request->getParam('path', '');
       
        // generiert eine Liste aus allen Gruppen
        $stmt = $this->db->prepare('SELECT gid AS id FROM oc_groups;');

    

    $result = $stmt->execute([$path]);

    $rows = [];

    while ($row = $result->fetch()) {
        $rows[] = $row;
    }

    return new DataResponse(['result' => $rows]);
    } catch (\Exception $e) {
        return new DataResponse([
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
}








}

