# PHP 雪花算法

### 使用示例

```php
<?php
$snowflake = new \snowflake\Snowflake();
$snowflake->setDataCenterIdBits(4)
    ->setWorkerIdBits(4)
    ->setSequenceBites(8);

$id = $snowflake->getId();
```

### 时间戳驱动

继承 `snowflake\abstracts\Timestamp` 完成 `set/get` 方法

### 序列号驱动

继承 `snowflake\abstracts\Sequence` 完成 `set/get` 方法

> 默认驱动采用储存到文件的方式，速度比较慢，可以自定义驱动

```php
<?php
$snowflake = new \snowflake\Snowflake();
$snowflake->setTimestampDrive(new \snowflake\drives\Timestamp())
    ->setSequenceDrive(new \snowflake\drives\Sequence());
```