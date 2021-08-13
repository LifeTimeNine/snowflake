<?php
/*
 * @Description   时间戳存储类
 * @Author        lifetime
 * @Date          2021-08-10 17:16:45
 * @LastEditTime  2021-08-13 10:10:48
 * @LastEditors   lifetime
 */
namespace snowflake\drives;

/**
 * 时间戳存储类
 * @class Timestamp
 */
class Timestamp extends \snowflake\abstracts\Timestamp
{
    /**
     * 获取文件路径
     * @return string
     */
    protected function getFilePath()
    {
        $dir = dirname(__DIR__) . '/runtime/';
        if (!is_dir($dir)) mkdir($dir, 0777, true);
        return $dir . "timestamp";
    }
    /**
     * 设置时间戳
     * @param   int     $timestamp
     */
    public function set($timestamp)
    {
        @file_put_contents($this->getFilePath(), $timestamp);
    }

    /**
     * 获取时间戳
     * @return int
     */
    public function get()
    {
        $timestamp = @file_get_contents($this->getFilePath());
        return $timestamp ?: 0;
    }
}
