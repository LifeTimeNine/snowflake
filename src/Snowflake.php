<?php
/*
 * @Description   雪花算法
 * @Author        lifetime
 * @Date          2021-08-10 16:46:37
 * @LastEditTime  2021-08-13 11:40:45
 * @LastEditors   lifetime
 */

namespace snowflake;

use snowflake\abstracts\Sequence;
use snowflake\abstracts\Timestamp;

/**
 * 雪花算法
 * @class Snowflake
 */
class Snowflake
{
    /**
     * 起始时间戳
     * @var int
     */
    protected $startTime = 0;
    
    /**
     * 机器标识位数
     * @var int
     */
    protected $workerIdBits = 5;

    /**
     * 机器标识
     * @var int
     */
    protected $workerId = 0;

    /**
     * 数据中心标识位数
     * @var int
     */
    protected $dataCenterIdBits = 5;

    /**
     * 数据中心标识
     * @var int
     */
    protected $dataCenterId = 0;

    /**
     * 毫秒内自增位数
     * @var int
     */
    protected $sequenceBits = 12;

    /**
     * 时间戳驱动
     * @var Timestamp
     */
    protected $timestampDrive;

    /**
     * 序列号驱动
     * @var Sequence
     */
    protected $sequenceDrive;

    /**
     * 构造函数
     * @param   int     $dataCenterId 数据中心标识
     * @param   int     $workerId   机器标识
     */
    public function __construct(int $dataCenterId = 0, int $workerId = 0)
    {
        $this->setDataCenterId($dataCenterId);
        $this->setWorkerId($workerId);
        $this->timestampDrive = new \snowflake\drives\Timestamp();
        $this->sequenceDrive = new \snowflake\drives\Sequence();
    }

    /**
     * 设置起始时间戳
     * @param   int     $timestamp
     * @return $this
     */
    public function setStartTime(int $timestamp)
    {
        $this->startTime = $timestamp >= 0 ? $timestamp : 0;
        return $this;
    }

    /**
     * 设置机器标识位数
     * @param   int     $workerIdBits
     * @return $this
     */
    public function setWorkerIdBits(int $workerIdBits)
    {
        $this->workerIdBits = $workerIdBits >= 0 ? $workerIdBits : 0;
        return $this;
    }

    /**
     * 设置机器标识
     * @param   int     $workerId
     * @return $this
     */
    public function setWorkerId(int $workerId)
    {
        $this->workerId = $workerId >= 0 ? $workerId : 0;
        // 计算最大机器标识
        $maxWorkerId = -1 ^ (-1 << $this->workerIdBits);
        if ($this->workerId > $maxWorkerId) {
            throw new \Exception("The maximum worker id is {$maxWorkerId}, and the current value is {$this->workerId}");
        }
        return $this;
    }

    /**
     * 设置数据中心标识位数
     * @param   int     $dataCenterIdBits
     * @return $this
     */
    public function setDataCenterIdBits(int $dataCenterIdBits)
    {
        $this->dataCenterIdBits = $dataCenterIdBits >= 0 ? $dataCenterIdBits : 0;
        return $this;
    }

    /**
     * 设置数据中心标识
     * @param   int     $dataCenterId
     * @return $this
     */
    public function setDataCenterId(int $dataCenterId)
    {
        $this->dataCenterId = $dataCenterId >= 0 ? $dataCenterId : 0;
        // 计算最大的数据中心ID
        $maxDataCenterId = -1 ^ (-1 << $this->dataCenterIdBits);
        if ($this->dataCenterId > $maxDataCenterId) {
            throw new \Exception("The maximum data center id is {$maxDataCenterId}, and the current value is {$this->dataCenterId}");
        }
        return $this;
    }

    /**
     * 设置毫秒内自增序列号位数
     * @param   int     $sequenceBits
     * @return $this
     */
    public function setSequenceBites(int $sequenceBits)
    {
        $this->sequenceBits = $sequenceBits >= 1 ? $sequenceBits : 1;
        return $this;
    }

    /**
     * 设置时间戳驱动
     * @param   Timestamp      $timestampDrive
     * @return $this
     */
    public function setTimestampDrive(Timestamp $timestampDrive)
    {
        $this->timestampDrive = $timestampDrive;
        return $this;
    }

    /**
     * 设置序列号驱动
     * @param   Sequence    $sequenceDrive
     * @return $this
     */
    public function setSequenceDrive(Sequence $sequenceDrive)
    {
        $this->sequenceDrive = $sequenceDrive;
        return $this;
    }

    /**
     * 获取ID
     * @return int
     */
    public function getId()
    {
        // 获取当前时间戳
        $timestamp = $this->getCurrentTimestamp();
        // 获取上次生成ID的时间戳
        $lastTimeStamp = $this->timestampDrive->get();

        if ($timestamp ==  $lastTimeStamp) {
            $sequence = $this->sequenceDrive->get() + 1;
            if ((-1 ^ (-1 << $this->sequenceBits)) & $sequence == 0) {
                while($timestamp <= $lastTimeStamp) {
                    $timestamp = $this->getCurrentTimestamp();
                }
                $sequence = 0;
            }
        } else {
            $sequence = 0;
        }
        $this->timestampDrive->set($timestamp);
        $this->sequenceDrive->set($sequence);
        return (($timestamp - $this->startTime) << ($this->dataCenterIdBits + $this->workerIdBits + $this->sequenceBits)) |
        ($this->dataCenterId << ($this->workerIdBits + $this->sequenceBits)) |
        ($this->workerId << ($this->sequenceBits)) |
        $sequence;
    }

    /**
     * 获取当前时间戳
     * @return int
     */
    protected function getCurrentTimestamp()
    {
        return floor(microtime(true) * 1000);
    }
}