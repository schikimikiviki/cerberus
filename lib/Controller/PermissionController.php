<?php

namespace OCA\Cerberus\Controller;

use OCP\AppFramework\Http\DataResponse;
use OCP\AppFramework\Controller;
use OCP\IRequest;
use OCP\Files\IRootFolder;
use OCP\IUserSession;

class PermissionController extends Controller {
    protected $request;
    protected $rootFolder;
    protected $userSession;

    public function __construct(string $AppName, IRequest $request, IRootFolder $rootFolder, IUserSession $userSession) {
        parent::__construct($AppName, $request);
        $this->request = $request;
        $this->rootFolder = $rootFolder;
        $this->userSession = $userSession;
    }

    /**
     * @NoAdminRequired
     * @NoCSRFRequired
     */
    public function checkPermissions(string $fileName): DataResponse {
        $fileName = urldecode($fileName);
        $fileName = ltrim($fileName, '/');
        
        $user = $this->userSession->getUser();
        if (!$user) {
            return new DataResponse(['error' => 'User not authenticated'], 401);
        }
    
        $userFolder = $this->rootFolder->getUserFolder($user->getUID());
        
        try {
            if (!$userFolder->nodeExists($fileName)) {
                return new DataResponse(['error' => 'File not found'], 404);
            }
    
            $file = $userFolder->get($fileName);
            $ncPerms = $file->getPermissions();
            
            // Convert Nextcloud permissions to Unix-style
            $unixPerms = $this->convertToUnixPermissions($ncPerms);
            
            return new DataResponse([
                'file' => $fileName,
                'nextcloud_permissions' => $ncPerms,
                'unix_permissions' => $unixPerms,
                'permissions_octal' => decoct($unixPerms) // Shows as 0644
            ]);
        } catch (\Exception $e) {
            return new DataResponse(['error' => $e->getMessage()], 500);
        }
    }


/**
 * Convert Nextcloud permissions to Unix-style permissions
 */
private function convertToUnixPermissions(int $ncPerms): int {
    $unixPerms = 0;
    
    // Nextcloud permission constants:
    // const PERMISSION_READ = 1;
    // const PERMISSION_UPDATE = 2;
    // const PERMISSION_CREATE = 4;
    // const PERMISSION_DELETE = 8;
    // const PERMISSION_SHARE = 16;
    
    // Owner permissions (6 = read+write)
    $unixPerms |= 0600; // Owner always has read+write in this conversion
    
    // Group permissions
    if ($ncPerms & \OCP\Constants::PERMISSION_READ) {
        $unixPerms |= 0040; // Add group read
    }
    if ($ncPerms & \OCP\Constants::PERMISSION_UPDATE) {
        $unixPerms |= 0020; // Add group write
    }
    
    // Others permissions
    if ($ncPerms & \OCP\Constants::PERMISSION_READ) {
        $unixPerms |= 0004; // Add others read
    }
    
    return $unixPerms;
}
}
