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
    $key = '';
    while (true){
        $time = time();
        $lockTime = $time + 10;
        $lockTime1 = $redis->get($key);
        # 有锁且未超时
        if($lockTime1 > $time){
            sleep(1);
            continue;
        }
        # 未锁或已超时
        else{
            # 未锁，加锁
            if(empty($lockTime1)){
                $status = $redis->setnx($key,$lockTime);
            }
            # 超时，替换死锁
            else{
                $lockTime2 = $redis->getSet($key,$lockTime);
                $status = $lockTime2 == $lockTime1;
            }
            if($status){
                return $lockTime;
                break;
            }
            # 未获得锁
            else{
                sleep(1);
                continue;
            }
        }
    }

    return false;
}

/** 解锁
 * @param $lockTime
 */
function unLock($lockTime)
{
    $redis = new Redis();
    $key = '';
    $lockTime1 = $redis->get($key);
    if($lockTime1 == $lockTime){
        $redis->del($key);
    }
}