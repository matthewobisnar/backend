<?php
namespace Api\Controllers;

use Api\Models\ExamProcessManagerClass;
use Api\Models\ProcessManagerRepository;

class Emapta
{
    public function actionExam()
    {
        return (new ProcessManagerRepository())->execute(new ExamProcessManagerClass());
    }
}