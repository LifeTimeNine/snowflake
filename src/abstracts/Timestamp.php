<?php
/*
 * @Description   时间戳存储抽象类
 * @Author        lifetime
 * @Date          2021-08-10 17:04:10
 * @LastEditTime  2021-08-10 18:21:08
 * @LastEditors   lifetime
 */

namespace snowflake\abstracts;

/**
 * 时间戳存储抽象类
 * @class Timestamp
 */
abstract class Timestamp
{
    /**
     * 设置时间戳
     * @param   int     $timestamp
     */
    abstract public function set($timestamp);

    /**
     * 获取时间戳
     * @return int
     */
    abstract public function get();
}
