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

namespace abexto\amylian\yii\base\tests\app\classes;

/**
 * Description of WrappedTestInst
 *
 * @author Andreas Prucha, Abexto - Helicon Software Development
 */
class BarClass
{

    public $value1   = null;
    private $_value2 = null;
    private $_value3 = null;
    private $_value4 = null;
    private $_value5 = null;

    public function setValue2($value)
    {
        $this->value2 = $value;
    }
    
    public function customInstSetValue3($value)
    {
        $this->_value3 = $value;
    }
    
    public function customInstSetValue4($value)
    {
        $this->_value4 = $value;
    }
    
    public function customInstSetValue5($value)
    {
        $this->_value5 = $value;
    }
    
    function getValue1()
    {
        return $this->value1;
    }

    function getValue2()
    {
        return $this->_value2;
    }

    function getValue3()
    {
        return $this->_value3;
    }

    function getValue4()
    {
        return $this->_value4;
    }

    function getValue5()
    {
        return $this->_value5;
    }


}
