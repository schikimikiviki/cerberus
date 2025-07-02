<?php

// declare(strict_types=1);

// use OCP\Util;
// use OC\Files\View;

// error_reporting(E_ALL);
// ini_set('display_errors', '1');

// Util::addScript(OCA\Cerberus\AppInfo\Application::APP_ID, OCA\Cerberus\AppInfo\Application::APP_ID . '-main');
// Util::addStyle(OCA\Cerberus\AppInfo\Application::APP_ID, OCA\Cerberus\AppInfo\Application::APP_ID . '-main');

// $info = "Must specify file name";

// $fileName = $_GET["fileName"] ?? '';

// if ($fileName === '') {
//     $info = "No fileName specified.";
// } else {
//     $filePath = 'files/' . $fileName; // relative path

//     // Get current user ID
//     $userId = \OC::$server->getUserSession()->getUser()->getUID();
//     $view = new View('/' . $userId . '/');

//     if ($view->file_exists($filePath)) {
//         $perms = $view->stat($filePath)['mode'];
//         $permissionString = substr(sprintf('%o', $perms), -4);
//         $info = "Permissions: $permissionString";
//     } else {
//         $info = "File not found.";
//     }
// }

?>

<div id="cerberus">
   
</div>
