<?php
namespace Api\Models;

class DefaultExtractorData 
{
    public $extractedData = null;

    public function __construct()
    {   
        $this->extractedData = Helper::postRequest();
        return;
    }

}