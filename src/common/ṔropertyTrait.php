<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace amylian\yii\base\common;

/**
 * Provides easy property support
 * 
 * 
 * This trait provides easy access to get and set methods like member variables:
 * 
 * It follows the following convention:
 * - <b>Property-Getter</b> methods MUST be named getXxx()
 * - <b>Property-Setter</b> methods MUST be named setXxx($vlaue)
 * 
 * <b>Calling Getters:</b> <code>$v = $myObject->myProperty<code> is equal to
 * <code>$v = $myObject->getMyProperty();</code>
 *          
 * <b>Calling Setters:</b> <code>$myObject->myProperty = 'xxx'</code> is equal
 * to<code>$v = $myObject->setMyProperty('xxx')</code>
 * 
 * <b>ATTENTION:</b>In the case of an access to an unknown property an
 * exception of class 
 * 
 *              
 *       
 * 
 * @author Andreas Prucha
 */
trait ṔropertyTrait
{
    /**
     * Checks if the property exists
     * 
     * This function checks if a setter method and/or a getter method
     * is implemented for the property or the member variable is declared public
     * 
     * @param string $propertyName
     * @return bool
     */
    
    protected function __propertyTraitHasProperty($propertyName)
    {
        // Check if getter or setter method exists
        if (method_exists($this, 'get'.$propertyName) || method_exists($this, 'set'.$propertyName)) {
            return true;
        }
        // Check if property is public
        try
        {
            $classReflection = new \ReflectionProperty(get_class($this), $propertyName);
            return $classReflection->isPublic();
        } catch (\ReflectionException $ex) {
            return false;
        }
    }
    
    /**
     * Checks if the property exists
     * 
     * This function checks if a setter method and/or a getter method
     * is implemented for the property or the member variable is declared public
     * 
     * @param string $propertyName
     * @return bool
     */
    public function hasProperty($propertyName)
    {
        return $this->__propertyTraitHasProperty($propertyName);
    }
    
    /**
     * Tries to call the Getter for a property
     * 
     * @param type $propertyName
     * @return void
     * @throws UnknownPropertyException
     */
    public function __get($propertyName)
    {
        $methodName = 'get'.$propertyName;
        if (method_exists($this, $methodName)) {
            return $this->$methodName();
        } else {
            throw new UnknownPropertyException('Error getting property: "'.$propertyName.
                    '" in object of class. "'.get_class($this).' Getter "get'.ucfirst($propertyName).'" not implemented');
        }
    }
    
    /**
     * Tries to call the Setter for a property
     * 
     * @param string $propertyName
     * @param mixed $value
     * @return void
     * @throws UnknownPropertyException
     */
    public function __set($propertyName, $value)
    {
        $methodName = 'set'.$propertyName;
        if (method_exists($this, $methodName)) {
            $this->$methodName($value);
        } else {
            throw new UnknownPropertyException('Error seting property: "'.$propertyName.
                    '" in object of class. "'.get_class($this).' Setter "set'. ucfirst($propertyName).'" not implemented');
        }
    }
    
    /**
     * Checks if the property is not null
     * @param type $propertyName
     */
    public function __isset($propertyName)
    {
        $methodName = 'get'.$propertyName;
        if (method_exists($this, $methodName)) {
            return ($this->$methodName() !== null);
        } else {
            return false;
        }
    }
    
    /**
     * Checks if the property is not null
     * @param string $propertyName
     */
    public function __unset($propertyName)
    {
        $methodName = 'set'.$propertyName;
        if (method_exists($this, $methodName)) {
            $this->$methodName(null);
        } else {
            throw new UnknownPropertyException('Error seting property: "'.$propertyName.
                    '" in object of class. "'.get_class($this).' Setter "set'. ucfirst($propertyName).'" not implemented');
        }
    }
    
    
}
