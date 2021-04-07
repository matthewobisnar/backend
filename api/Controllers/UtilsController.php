<?php
namespace Api\Controllers;

use Core\Helpers\Helper;
use Core\Helpers\Database;
use Api\Models\MigrationModel;

class UtilsController
{
    public function actionMigrate()
    {
        die (var_dump(new MigrationModel()));
    }
}