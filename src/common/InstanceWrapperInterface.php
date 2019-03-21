<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace amylian\yii\base\common;

/**
 *
 * @author andreas
 */
interface InstanceWrapperInterface
{
    
    /**
     * Returns the wrapped object
     * 
     * @param bool $autoCreate Create the object if it has not been created
     * @return object|null Wrapped object or null if it has not been created
     */
    public function getWrappedInst($autoCreate = true): ?object;

    /**
     * Checks if the wrapped object has been created
     * 
     * @return bool
     */
    public function isWrappedInstInitialized(): bool;
    
}
