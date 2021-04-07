<?php 
namespace Api\Models;

class ExamDataValidatorClass 
{
    public static $extractedData = null;

    public function __construct($data)
    {
        self::$extractedData = $data;
    }

    public function candidateHasTakenTheWrittenExam()
    {
        $requesData = Helper::getArrayValue(self::$extractedData, 'request_data');
        $takenWrittenExam = (bool) Helper::getArrayValue($requesData, 'taken_written_exam');

        return ($takenWrittenExam) ? "I've taken the Exam" : "I will take the exam later";

    }

    public function candidateHasTakenInterview()
    {
        $requesData = Helper::getArrayValue(self::$extractedData, 'request_data');
        $takenInterview = (bool) Helper::getArrayValue($requesData, 'taken_interview');

        return ($takenInterview) ?  "I've taken the Interview" : "I will take the interview later";
    }
}