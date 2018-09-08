<?php
namespace libraries;
/**
 *  列表迭代器
 * @user 罗仕辉
 * @create 2018-06-20
 * @update 2018-06-20
 */
class ListIterator implements \Iterator
{
    private $list;
    private $index = 0;
    private $first = 0;
    private $step  = 1;

    /**
     * ListIterator constructor.
     * @param array $list  迭代数据
     * @param int $index   初始索引
     * @param int $step    遍历步长
     */
    public function __construct(array $list, $index=0, $step=1) {
        $this->list  = $list;
        $this->index = $index;
        $this->first = $index;
        $this->step  = $step;
    }

    // 将索引游标指向初始位置
    function rewind() {
        $this->index = $this->first;
    }

    // 返回当前元素
    function current() {
        return $this->list[$this->index];
    }

    // 返回当前元素的键
    function key() {
        return $this->index;
    }

    // 向前移动到下一个元素
    function next() {
        $this->index += $this->step;
    }

    // 检查当前位置是否有效
    function valid() {
        return isset($this->list[$this->index]);
    }
}