<?php
/*
 * @Description   序列存储抽象类
 * @Author        lifetime
 * @Date          2021-08-10 17:09:24
 * @LastEditTime  2021-08-13 10:15:55
 * @LastEditors   lifetime
 */

namespace snowflake\abstracts;

/**
 * 序列存储抽象类
 * @class Sequence
 */
abstract class Sequence
{
    /**
     * 设置序列值
     * @param   int     $value
     */
    abstract public function set($value);

    /**
     * 获取序列值
     * @return int
     */
    abstract public function get();
}
