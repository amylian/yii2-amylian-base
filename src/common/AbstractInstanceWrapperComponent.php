<?php

/*
 * Copyright 2018 Andreas Prucha, Abexto - Helicon Software Development.
 */

namespace abexto\amylian\yii2\base\common;

/**
 * Description of AbstractInstanceWrapperComponent
 *
 * @author Andreas Prucha, Abexto - Helicon Software Development
 * 
 * @property object $inst Wrapped Object Instance
 */
Abstract class AbstractInstanceWrapperComponent extends yii\base\Component
{
    /**
     *
     * @var string Class of the instance to wrap
     */
    public $instClass = null;
    
    /**
     *
     * @var array|null Parameters to be passed to constructor 
     */
    public $constructorArgs = null;

    /**
     * @var object|null Wrapped object instance
     */
    protected $_inst = null;
    
    /**
     * Creates the wrapped instance
     * @return object
     */
    protected function createNewInst()
    {
        $classReflection = new ReflectionClass($this->instClass);
        if ($this->constructorArgs === null) {
            return $classReflection->newInstance();
        } else {
            return $classReflection->newInstanceArgs($this->concstructorArgs);
        }
    }
    
    /**
     * Returns the wrapped object instance
     */
    public function getInst()
    {
      if (!isset($this->_inst)) {
          $this->inst = $this->createNewInst();
      }
      return $this->inst;
    }
}
