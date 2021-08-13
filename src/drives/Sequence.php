<?php
/*
 * @Description   毫秒内序列号存储类
 * @Author        lifetime
 * @Date          2021-08-10 17:33:59
 * @LastEditTime  2021-08-13 10:16:12
 * @LastEditors   lifetime
 */
namespace snowflake\drives;

/**
 * 毫秒内序列号存储类
 * @class Sequence
 */
class Sequence extends \snowflake\abstracts\Sequence
{
    /**
     * 获取文件路径
     * @return string
     */
    protected function getFilePath()
    {
        $dir = dirname(__DIR__) . '/runtime/';
        if (!is_dir($dir)) mkdir($dir, 0777, true);
        return $dir . "sequence";
    }

    /**
     * 设置序列号
     * @param   int     $value
     */
    public function set($value)
    {
        @file_put_contents($this->getFilePath(), $value);
    }

    /**
     * 获取序列号
     * @return int
     */
    public function get()
    {
        $timestamp = @file_get_contents($this->getFilePath());
        return $timestamp ?: 0;
    }
}
