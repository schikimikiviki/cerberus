<?php

namespace OCA\Cerberus\Controller;

use OCP\AppFramework\Http\DataResponse;
use OCP\AppFramework\Controller;
use OCP\IRequest;
use OCP\IUserSession;

use OCP\IDBConnection;

class UserController extends Controller
{
    protected $request;
    protected $userSession;
    protected $db;

    public function __construct(string $AppName, IRequest $request, IUserSession $userSession,  IDBConnection $db)
    {
        parent::__construct($AppName, $request);
        $this->request = $request;
        $this->userSession = $userSession;
        $this->db = $db;
    }

    /**
     * @NoAdminRequired
     * @NoCSRFRequired
     */
    public function getUsersAndGroups(): DataResponse
    {
        $currentUser = $this->userSession->getUser();
        if (!$currentUser || $currentUser->getUID() !== 'admin') {
            return new DataResponse(['error' => 'Access denied'], 403);
        }

        try {
            $stmt = $this->db->prepare('SELECT uid AS id FROM oc_users UNION SELECT gid AS id FROM oc_groups;');
            $stmt->execute();

            $rows = $stmt->fetchAll(\PDO::FETCH_COLUMN);

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
    public function getUsers(): DataResponse
    {
        $currentUser = $this->userSession->getUser();
        if (!$currentUser || $currentUser->getUID() !== 'admin') {
            return new DataResponse(['error' => 'Access denied'], 403);
        }

        try {
            $stmt = $this->db->prepare('SELECT uid FROM oc_users;');
            $stmt->execute();

            $rows = $stmt->fetchAll(\PDO::FETCH_COLUMN);

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
    public function getGroups(): DataResponse
    {
        $currentUser = $this->userSession->getUser();
        if (!$currentUser || $currentUser->getUID() !== 'admin') {
            return new DataResponse(['error' => 'Access denied'], 403);
        }

        try {
            $stmt = $this->db->prepare('SELECT gid AS id FROM oc_groups;');
            $stmt->execute();

            $rows = $stmt->fetchAll(\PDO::FETCH_COLUMN);

            return new DataResponse(['result' => $rows]);
        } catch (\Exception $e) {
            return new DataResponse([
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }
}
