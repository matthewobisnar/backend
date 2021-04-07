<?php
namespace Api\Models;

class ExamAdditionalProcessClass 
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

        return ($takenWrittenExam) ? "Already taken the Exam" : "Unable to take the Exam";

    }

    public function candidateHasTakenInterview()
    {
        $requesData = Helper::getArrayValue(self::$extractedData, 'request_data');
        $takenInterview = (bool) Helper::getArrayValue($requesData, 'taken_interview');

        return ($takenInterview) ?  "Done with the Interview" : "Unable to attend the Interview";
    }
}