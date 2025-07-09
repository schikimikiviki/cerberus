<?php

namespace OCA\Cerberus\Controller;

use OCP\AppFramework\Http\DataResponse;
use OCP\AppFramework\Controller;
use OCP\IRequest;
use OCP\Files\IRootFolder;
use OCP\IUserSession;

use OCP\IDBConnection; 

class FileController extends Controller {
    protected $request;
    protected $rootFolder;
    protected $userSession;
    protected $db; 

    public function __construct(string $AppName, IRequest $request, IRootFolder $rootFolder, IUserSession $userSession,  IDBConnection $db) {
        parent::__construct($AppName, $request);
        $this->request = $request;
        $this->rootFolder = $rootFolder;
        $this->userSession = $userSession;
        $this->db = $db;
    }

 /**
 * @NoAdminRequired
 * @NoCSRFRequired
 */
public function getFile(): DataResponse {


    // only let admin fetch
    $currentUser = $this->userSession->getUser();
    if (!$currentUser || $currentUser->getUID() !== 'admin') {
        return new DataResponse(['error' => 'Access denied'], 403);
    }

    try {
        $path = $this->request->getParam('path', '');
       
        $stmt = $this->db->prepare('SELECT 
        s.id,
        s.item_type,
        s.share_type,
        s.share_with,
        s.uid_owner,
        s.file_source,
        s.permissions,
        f.path
    FROM 
        oc_share s
    JOIN 
        oc_filecache f ON s.file_source = f.fileid
    WHERE 
        f.path = ?');

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
public function getGroup(): DataResponse {


    // only let admin fetch
    $currentUser = $this->userSession->getUser();
    if (!$currentUser || $currentUser->getUID() !== 'admin') {
        return new DataResponse(['error' => 'Access denied'], 403);
    }

    // check if the table exists first
    if (!$this->tableExists('oc_group_folders')) {
        return new DataResponse(['result' => []]);
    }
    

    try {
        $mount_point = $this->request->getParam('mount_point', '');
       
        $stmt = $this->db->prepare("SELECT 
        gf.folder_id,
        gf.mount_point,
        gfg.group_id,
        gfg.permissions,
        CASE WHEN gfg.permissions & 1 THEN 'Ja' ELSE 'Nein' END as Lesen,
        CASE WHEN gfg.permissions & 2 THEN 'Ja' ELSE 'Nein' END as Schreiben,
        CASE WHEN gfg.permissions & 4 THEN 'Ja' ELSE 'Nein' END as Erstellen,
        CASE WHEN gfg.permissions & 8 THEN 'Ja' ELSE 'Nein' END as Loeschen,
        CASE WHEN gfg.permissions & 16 THEN 'Ja' ELSE 'Nein' END as Teilen
    FROM 
        oc_group_folders gf
    JOIN 
        oc_group_folders_groups gfg ON gf.folder_id = gfg.folder_id
    WHERE 
        gf.mount_point = ?");
    
    

    $result = $stmt->execute([$mount_point]);

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

// public function getPermissions(): DataResponse {
//     return new DataResponse([
//         'success' => true,
//         'path' => $this->request->getParam('path', '')
//     ]);
// }

private function tableExists(string $tableName): bool {
    try {
        // Different database types might need different queries
        $platform = $this->db->getDatabasePlatform();
        
        
            // MySQL/MariaDB
            $sql = "SELECT TABLE_NAME 
                    FROM INFORMATION_SCHEMA.TABLES 
                    WHERE TABLE_SCHEMA = DATABASE() 
                    AND TABLE_NAME = ?";
        } 
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$tableName]);
        
        return (bool) $stmt->fetch();
    } catch (\Exception $e) {
        // Log error if needed
        // error_log('Error checking table existence: ' . $e->getMessage());
        return false;
    }
}




}