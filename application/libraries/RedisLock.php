<?php
/**
 *  redis 分布式锁基本方法
 */

/** 加锁
 * @return bool|int
 */
function lock()
{
    $redis = new Redis();
    $key = ''; # 加锁的key
    $expire = 10; # 加锁超时时间
    $sleep = 1; # 循环等待解锁时间
    while (true){
        $time = time();
        $expireAt = $time + $expire;
        $lockTime1 = $redis->get($key);
        # 有锁且未超时
        if($lockTime1 > $time){
            sleep($sleep);
            continue;
        }
        # 未锁或已超时
        else{
            # 未锁，加锁
            if(empty($lockTime1)){
                $status = $redis->setnx($key,$expireAt);
            }
            # 超时，替换死锁
            else{
                $lockTime2 = $redis->getSet($key,$expireAt);
                $status = $lockTime2 == $lockTime1;
            }
            if($status){
                return $expireAt;
                break;
            }
            # 未获得锁
            else{
                sleep($sleep);
                continue;
            }
        }
    }

    return false;
}

/** 解锁
 * @param $lockTime
 */
function unLock($expireAt)
{
    $redis = new Redis();
    $key = '';
    $lockTime = $redis->get($key);
    if($lockTime == $expireAt){
        $redis->del($key);
    }
}