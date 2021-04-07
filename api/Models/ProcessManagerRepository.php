<?php
namespace Api\Models;

class ProcessManagerRepository implements IProcessManagerRepository
{
    public static $extractedData = null;

    public function extractor($object)
    {
        return $object->extractor();
    }

    public function validator($object)
    {
        return $object->validator();
    }

    public function additionalProcessor($object)
    {
        return $object->additionalProcessor();
    }

    public function output($object)
    {
        return $object->output();
    }

    public function execute($object)
    {
        return $this->extractor($object)
            ->validator($object)
            ->additionalProcessor($object)
            ->output($object);
    }
}

interface IProcessManagerRepository
{
    function extractor($object);
    function validator($object);
    function additionalProcessor($object);
}
