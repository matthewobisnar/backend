<?php
namespace Api\Models;

use Api\Models\ProcessManagerRepository;

class ExamProcessManagerClass
{
    public CONST PROCESS_CONFIG = '{
        "process_name": "Exam Process",
        "process_code": "examProcessCode",
        "process_flow": {
          "ExamProcessManagerClass": {
            "extractData": "DefaultExtractorData",
            "validateData": {
              "ExamDataValidatorClass": [
                "candidateHasTakenTheWrittenExam",
                "candidateHasTakenInterview"
              ]
            },
            "additionalProcess": {
              "ExamAdditionalProcessClass": [
                "candidateHasTakenTheWrittenExam",
                "candidateHasTakenInterview"
              ]
            }
          }
        }
    }';
    
    public static $extractedData = null;
    public static $config = null;
    public static $className = null;
    public static $validatorResults = [];
    public static $additionalProcessorResults = [];

    public function __construct()
    {
        self::$className = Helper::className($this);
        self::$config = Helper::parseConfig(self::PROCESS_CONFIG);   
    }

    public function extractor()
    {
        if (!empty(self::$config['process_flow'][self::$className]['extractData'])) {
            if (class_exists(__NAMESPACE__ . "\\" . self::$config['process_flow'][self::$className]['extractData'])) {
                
                $class = __NAMESPACE__ . "\\" . self::$config['process_flow'][self::$className]['extractData'];
                self::$extractedData = (new $class())->extractedData;

            }
        }

        return $this;
    }

    public function validator()
    {

        if (!empty(self::$config['process_flow'][self::$className]['validateData'])) {
            foreach (self::$config['process_flow'][self::$className]['validateData'] as $class => $functions) {
                if (class_exists(__NAMESPACE__ . "\\" . $class)) {

                    $className = __NAMESPACE__ . "\\" . $class;
                    $classObj = new $className(self::$extractedData);

                    foreach ($functions as $function) {
                        self::$validatorResults[$function] = $classObj->{$function}();
                    }

                }
            }
        }

        return $this;
    }

    public function additionalProcessor()
    {
        if (!empty(self::$config['process_flow'][self::$className]['additionalProcess'])) {
            foreach (self::$config['process_flow'][self::$className]['additionalProcess'] as $class => $functions) {
                if (class_exists(__NAMESPACE__ . "\\" . $class)) {

                    $className = __NAMESPACE__ . "\\" . $class;
                    $classObj = new $className(self::$extractedData);

                    foreach ($functions as $function) {
                        self::$additionalProcessorResults[$function] = $classObj->{$function}();
                    }

                }
            }
        }

        return $this;
    }

    public function output()
    {   
        $extracted = json_encode(self::$extractedData);
        $processCode = Helper::getArrayValue(self::$extractedData, 'process_code');
        $score = Helper::getArrayValue(Helper::getArrayValue(self::$extractedData, 'request_data'), 'score');
        $scoreResult = (!empty($score) && (int) $score >= 75) ? "Passed" : "failed";

        $dataValidation = null;
        $dataAdditionalProcess = null;

        if (!empty(self::$validatorResults)) {
            
            foreach (self::$validatorResults as $key => $value) {
                $dataValidation .= "<br/><b>" . $key . "</b> : " . $value;
            }
        }

        if (!empty(self::$additionalProcessorResults)) {
            
            $dataAdditionalProcess = "<br/><b>Addition Process</b>:";
            
            foreach (self::$additionalProcessorResults as $key => $value) {
                $dataAdditionalProcess .= "<br/><b>" . $key . "</b> : " . $value;
            }
        }

        $output = "<p>
            <p><b>Data Extracted</b>: {$extracted}</p>
            <p><b>Process Code</b>: {$processCode}</p>
            <p><b>Data Validation</b>:
            {$dataValidation}
            <br/><b>score</b>: {$scoreResult}</p>
            {$dataAdditionalProcess}
        </p>"; 

        die($output);
        return;
    }

}