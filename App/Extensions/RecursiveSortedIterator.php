<?php

namespace App\Extensions;

use SplHeap;
use Iterator;

/**
 * Recursive sorted iterator.
 *
 * @category Extension
 * @package  Website
 * @author   Ice <info@iceframework.org>
 * @license  iceframework.org Ice
 * @link     iceframework.org
 */
class RecursiveSortedIterator extends SplHeap
{
    /**
     * Iterator constructor.
     *
     * @param object $iterator Iterator
     */
    public function __construct(Iterator $iterator)
    {
        foreach ($iterator as $item) {
            $this->insert($item);
        }
    }

    /**
     * Compare the values.
     *
     * @param object $b First node
     * @param object $a Second node
     *
     * @return boolean
     */
    public function compare($b, $a)
    {
        return -strcmp($a->getRealpath(), $b->getRealpath());
    }
}
