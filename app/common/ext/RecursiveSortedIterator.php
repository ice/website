<?php

namespace App\Extensions;

use SplHeap;
use Iterator;

class RecursiveSortedIterator extends SplHeap
{
    public function __construct(Iterator $iterator)
    {
        foreach ($iterator as $item) {
            $this->insert($item);
        }
    }

    public function compare($b, $a)
    {
        return -strcmp($a->getRealpath(), $b->getRealpath());
    }
}
