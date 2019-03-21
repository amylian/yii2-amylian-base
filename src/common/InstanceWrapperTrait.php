<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace amylian\yii\base\common;

use yii\base\UnknownPropertyException;
use yii\base\UnknownMethodException;

/**
 *
 * @author andreas
 */
trait InstanceWrapperTrait
{

    /**
     * Returns the wrapped object
     * 
     * @param bool $autoCreate Create the object if it has not been created
     * @return object|null Wrapped object or null if it has not been created
     */
    abstract function getWrappedInst($autoCreate = true): ?object;

    /**
     * Checks if the wrapped object has been created
     * 
     * @return bool
     */
    public function isWrappedInstInitialized(): bool
    {
        return $this->getWrappedInst(false) !== null;
    }

    public function __get($propertyName)
    {

        $t = $this;
        try {
            if ($t instanceof \yii\base\Component) {
                return parent::__get($propertyName, $value);
            }
        } catch (UnknownPropertyException $exc1) {
            try {
                return $this->getPropertyFromWrappedInst($propertyName, $value);
            } catch (UnknownMethodException $exc2) {
                throw new UnknownMethodException($exc1->getMessage() . ' and ' . $exc2);
            }
        }
    }

    public function __set($propertyName, $value)
    {
        $t = $this;
        try {
            if ($t instanceof \yii\base\Component) {
                return parent::__set($propertyName, $value);
            }
        } catch (UnknownPropertyException $exc1) {
            try {
                $this->setPropertyInWrappedInst($propertyName, $value);
            } catch (UnknownMethodException $exc2) {
                throw new UnknownMethodException($exc1->getMessage() . ' and ' . $exc2);
            }
        }
    }

    public function __call($method, $args)
    {
        $t = $this;
        try {
            if ($t instanceof \yii\base\Component) {
                return parent::__call($method, $args);
            }
        } catch (UnknownMethodException $exc1) {
            try {
                $this->callMethodInWrappedInst($method, $args);
            } catch (UnknownMethodException $exc2) {
                throw new UnknownMethodException($exc1->getMessage() . ' and ' . $exc2);
            }
        }
    }

    /**
     * Calls the method in the wrapped instance
     * 
     * This method is used internally in order to call a method 
     * of the wrapped interface. Usually this function is called
     * by the magic function __call if the Wrapper does implement
     * the called method. 
     * 
     * @param type $method
     * @param type $args
     */
    public function callMethodInWrappedInst($method, $args)
    {
        $i = $this->getWrappedInst();
        if (method_exists($i, $method)) {
            return call_user_method_array($method, $i, $args);
        }
        throw new UnknownMethodException('Calling unknown method: ' . get_class($i) . "::$method()");
    }

    public function getPropertyFromWrappedInst($propertyName)
    {
        $w = $this->getWrappedInst();
        if (property_exists($w, $propertyName)) {
            return $w->$propertyName;
        } else {
            $fn = 'get' . ucfirst($propertyName);
            if (method_exists($w, $fn)) {
                return $w->$fn;
            } else {
                throw new UnknownPropertyException('Getting unknown property: ' . get_class($w) . '::' . $propertyName);
            }
        }
    }

    public function setPropertyInWrappedInst($propertyName, $value)
    {
        $w = $this->getWrappedInst();
        if (property_exists($w, $propertyName)) {
            $w->$propertyName = $value;
        } else {
            $fn = 'set' . ucfirst($propertyName);
            if (method_exists($w, $fn)) {
                $w->$fn($value);
            } else {
                throw new UnknownPropertyException('Setting unknown property: ' . get_class($w) . '::' . $propertyName);
            }
        }
    }

}
