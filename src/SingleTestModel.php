<?php

namespace Invoker\TestToolkit;


class SingleTestModel {

    public $class;
    public $method;
    public $fullPath;

    /**
     * @return mixed
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @param mixed $class
     * @return SingleTestModel
     */
    public function setClass($class): SingleTestModel
    {
        $this->class = $class;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param mixed $method
     * @return SingleTestModel
     */
    public function setMethod($method): SingleTestModel
    {
        $this->method = $method;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFullPath()
    {
        return $this->fullPath;
    }

    /**
     * @param mixed $fullPath
     * @return SingleTestModel
     */
    public function setFullPath($fullPath): SingleTestModel
    {
        $this->fullPath = $fullPath;
        return $this;
    }



}