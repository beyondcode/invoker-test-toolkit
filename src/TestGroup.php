<?php

namespace Invoker\TestToolkit;


class TestGroup {

    public $namespace;
    public $class;
    public $fullPath;

    /**
     * @return mixed
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * @param mixed $namespace
     * @return TestGroup
     */
    public function setNamespace($namespace): TestGroup
    {
        $this->namespace = $namespace;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @param mixed $class
     * @return TestGroup
     */
    public function setClass($class): TestGroup
    {
        $this->class = $class;
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
     * @return TestGroup
     */
    public function setFullPath($fullPath): TestGroup
    {
        $this->fullPath = $fullPath;
        return $this;
    }


}
