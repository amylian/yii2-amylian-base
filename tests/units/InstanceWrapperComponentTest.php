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

namespace abexto\amylian\yii\base\tests\units;

/**
 * Description of newPHPClass
 *
 * @author Andreas Prucha, Abexto - Helicon Software Development
 */
class InstanceWrapperComponentTest extends \abexto\amylian\yii\phpunit\AbstractYiiTestCase
{

    public function testAccessInst()
    {
        static::mockYiiWebApplication(['components' => ['foo' => [
                    'class'     => \abexto\amylian\yii\base\tests\app\classes\FooWrapperComponent::class,
                    'instClass' => \abexto\amylian\yii\base\tests\app\classes\BarClass::class
        ]]]);
        $this->assertInstanceOf(\abexto\amylian\yii\base\tests\app\classes\FooWrapperComponent::class,
                                \Yii::$app->foo);
        $this->assertInstanceOf(\abexto\amylian\yii\base\tests\app\classes\BarClass::class, \Yii::$app->foo->inst);
    }

    public function testAfterNewInstEvent()
    {
        static::mockYiiWebApplication(['components' => ['foo' => [
                    'class'           => \abexto\amylian\yii\base\tests\app\classes\FooWrapperComponent::class,
                    'instClass'       => \abexto\amylian\yii\base\tests\app\classes\BarClass::class,
                    'on afterNewInst' => function(\abexto\amylian\yii\base\common\InstanceWrapperComponentEvent $event) {
                            $event->inst->testAfterNewInstEventValue = 'success';
                        },
        ]]]);
        $this->assertSame('success', \Yii::$app->foo->inst->testAfterNewInstEventValue);
    }
    
    public function testInstPropertyMappings()
    {
        static::mockYiiWebApplication(['components' => ['foo' => [
                    'class'     => \abexto\amylian\yii\base\tests\app\classes\FooWrapperComponent::class,
                    'instClass' => \abexto\amylian\yii\base\tests\app\classes\BarClass::class
        ]]]);
        $this->assertSame(1, \Yii::$app->foo->inst->getValue1());
        $this->assertSame(2, \Yii::$app->foo->inst->getValue2());
        $this->assertSame(3, \Yii::$app->foo->inst->getValue3());
        $this->assertSame(4, \Yii::$app->foo->inst->getValue4());
        $this->assertSame(5, \Yii::$app->foo->inst->getValue5());
    }

}
