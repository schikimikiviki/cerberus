<?php

declare(strict_types=1);

use OCP\Util;
use OC\Files\Filesystem;
use OC\Files\View;

Util::addScript(OCA\Cerberus\AppInfo\Application::APP_ID, OCA\Cerberus\AppInfo\Application::APP_ID . '-main');
Util::addStyle(OCA\Cerberus\AppInfo\Application::APP_ID, OCA\Cerberus\AppInfo\Application::APP_ID . '-main');

$info = "Must specify file name";

$filePath = 'files/screenshot.png'; // relative path inside the user's storage
$info = 'File not found in Nextcloud storage';

// Get the current user's root view
$view = new View('/' . \OC::$server->getUserSession()->getUser()->getUID() . '/');

// Check if file exists
if ($view->file_exists($filePath)) {
    $perms = $view->stat($filePath)['mode'];
    $permissionString = substr(sprintf('%o', $perms), -4);
    $info = "Permissions: $permissionString";
} 


?>

<div id="cerberus">
<p><?php echo $info; ?></p>
</div>
