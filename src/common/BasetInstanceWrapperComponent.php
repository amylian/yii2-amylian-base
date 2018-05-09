<?php

/*
 * BSD 3-Clause License
 * 
 * Copyright (c) 2018, Abexto - Helicon Software Development / Amylian Project
 * 
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 * 
 * * Redistributions of source code must retain the above copyright notice, this
 *   list of conditions and the following disclaimer.
 * 
 * * Redistributions in binary form must reproduce the above copyright notice,
 *   this list of conditions and the following disclaimer in the documentation
 *   and/or other materials provided with the distribution.
 * 
 * * Neither the name of the copyright holder nor the names of its
 *   contributors may be used to endorse or promote products derived from
 *   this software without specific prior written permission.
 * 
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE
 * FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
 * DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
 * SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY,
 * OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 * 
 */

namespace abexto\amylian\yii\base\common;

/**
 * Abstract component for encapsulation of Non-Yii classes
 *
 * @author Andreas Prucha, Abexto - Helicon Software Development
 * 
 * @property object $inst Wrapped Object Instance ({@see getInst()})
 */
Abstract class BasetInstanceWrapperComponent extends \yii\base\Component implements InstanceWrapperComponentInterface
{

    const EVENT_AFTER_NEW_INST = 'afterNewInst';

    /**
     * @var string Class of the instance to wrap
     */
    public $instClass = null;

    /**
     *  Array of arguments to be passed to the constructor
     * 
     * @var array|null Parameters to be passed to constructor 
     */
    public $constructorArgs = null;

    /**
     * Additional properties to be set in the wrapped instance
     * @var array Array of propertName => value pairs. 
     */
    public $addInstProperties = [];

    /**
     * @var object|null Wrapped object instance
     */
    protected $_inst = null;
    protected $_instInitFinalized = false;

    /**
     *  Array of additional properties to set in the wrapped instance
     * 
     * @var array Array of propertyName => Value pairs
     */

    /**
     * Returns an array of component => inst property mappings for automatic configuration of the wraped instance
     * 
     * The keys are the property names used in this component
     * 
     * The values may be:
     * <dl>
     *  <dt>
     *    true 
     *  </dt>
     *  <dd>
     *    if the value is true, the {@link setInstProperties()} tries to figure out the best way to use set the value.
     *    If {@link $inst} defines a public property with the same name, it's set.
     *    If {@link $inst} defines a public method with the name setXxxx (where Xxxx is the name of the proprety), it's called.
     *    NOTE: Just the value is passed to the method.
     *    If this has a method named setInstXxxxProperty (where Xxxx is the name of the proprety) this method
     *    is called. The value and the instance of the wrapped object is passed to this method. 
     *  </dd>
     *  <dt>
     *     Callable:
     *  </dt>
     *     if the first element of the callable array is null 
     *  <dd>
     *  </dd>
     *  <dt>
     *    false 
     *  </dt>
     *  <dd>
     *      Ignore this property
     *  </dd>
     * </ul>
     * 
     * @return array
     */
    protected function getInstPropertyMappings()
    {
        return[];
    }

    /**
     * Sets the value of a property
     * 
     * Automatic mapping
     * 
     * Automatic mapping is used, if $mappingDefinition is true or contains a property name. 
     * In this case, the following steps are performed:
     * 
     * 1)   Check if a method named setInstPropertyXxxx exists in $this. If the method exists, it is called and the called
     *      method is responsible to set the property in $inst. 
     * 2)   Check if a method name setXxxx exists in $inst. If this method exists, it is called. The property value
     *      is passed in the first parameter. 
     * 3)   $inst->Xxxx is set directly. 
     * 
     * @param type $inst
     * @param type $propertyName
     * @param type $mappingDefinition
     * @param type $propertyValue
     * @return boolean
     * @throws \yii\base\ErrorException
     */
    protected function setInstProperty($inst, $propertyName, $mappingDefinition, $propertyValue = null)
    {
        if ($mappingDefinition === false) {
            return false; // Do not even attempt to set this property
        }
        if (func_num_args() <= 3) {
            $propertyValue = $this->$propertyName;
        }
        if ($mappingDefinition === true ||
                is_string($mappingDefinition)) {
            $instPropertyName = $mappingDefinition === true ? $propertyName : $mappingDefinition;

            $setInstPropertyMethod = 'setInstProperty' . ucfirst($instPropertyName);
            if (method_exists($this, $setInstPropertyMethod)) {
                $this->$setInstPropertyMethod($propertyValue, $inst);
                return;
            } else {
                $instSetterMethod = 'set' . ucfirst($instPropertyName);
                if (method_exists($inst, $instSetterMethod)) {
                    $inst->$instSetterMethod($propertyValue);
                    return;
                } else {
                    $inst->$instPropertyName = $propertyValue;
                    return;
                }
            }
        } elseif (is_array($mappingDefinition)) {
            if (reset($mappingDefinition) === null) {
                $mappingDefinition[0] = $inst;
                call_user_func_array($mappingDefinition, [$propertyValue]);
                return;
            } elseif (reset($mappingDefinition) === $this) {
                call_user_func_array($mappingDefinition, [$propertyValue, $inst]);
                return;
            }
        }
        throw new \yii\base\ErrorException('Could not set property ' . $propertyName . ' in wrapped object');
    }

    /**
     * @param object $inst
     */
    protected function setInstProperites($inst, array $mappings)
    {
        foreach ($mappings as $cpn => $mapping) {
            $this->setInstProperty($inst, $cpn, $mapping, $this->$cpn);
        }
        foreach ($this->addInstProperties as $p => $v) {
            $this->setInstProperty($inst, $p, true, $v);
        }
    }

    /**
     * Creates the wrapped instance
     * @return object
     */
    protected function createNewInst()
    {
        $classReflection = new \ReflectionClass($this->instClass);
        if ($this->constructorArgs === null) {
            return $classReflection->newInstance();
        } else {
            return $classReflection->newInstanceArgs($this->concstructorArgs);
        }
    }
    
    /**
     * Destroys the object
     * 
     * @param object $inst Instance to destroy
     */
    protected function destroyInst($inst)
    {
        unset($this->$inst);
        $this->_instInitFinalized = false;
    }

    protected function afterNewInst()
    {
        $this->trigger(static::EVENT_AFTER_NEW_INST, new InstanceWrapperComponentEvent(['inst' => $this->_inst]));
    }

    protected function doCreateNewInst()
    {
        $this->_instInitFinalized = false;
        $this->_inst              = $this->createNewInst();
        $this->afterNewInst();
        $this->setInstProperites($this->_inst, $this->getInstPropertyMappings());
        $this->_instInitFinalized = true;
    }

    /**
     * Returns the wrapped object instance
     * 
     * @return object Wrapped object
     * 
     */
    public function getInst()
    {
        if (!isset($this->_inst)) {
            $this->doCreateNewInst();
        }
        return $this->_inst;
    }

    public function hasInst($allowUnfinalizedInst = false)
    {
        return $this->_inst !== null && ($allowUnfinalizedInst || $this->_instInitFinalized);
    }

}
