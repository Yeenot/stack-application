<?php

namespace App\Services;

use App\Models\Stack;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class GetStacks
{
    /**
     * Get stacks
     *
     * @return array
     */
    public function execute()
    {
        $flatStacks = Stack::orderBy('index')->orderBy('order')->get();
        return $this->variations($this->structureStacks($flatStacks));
    }

    /**
     * Structure stacks from flat to multi-dimensional array
     *
     * @param array $stacks
     * @return array
     */
    private function structureStacks($stacks)
    {
        $structured = [];
        foreach($stacks as $stack) {
            $structured[$stack->index][] = $stack;
        }
        return $structured;
    }

    /**
     * Get variations
     *
     * @param array $items
     * @return array
     */
    private function variations($items)
    {
        if (count($items) === 1)
            return $items[0];

        $stacks = [];
        foreach($items[0] as $item) {
            // Get remaining items and get combinations
            $remainingItems = array_values(Arr::except($items, 0));
            $children = $this->variations($remainingItems);

            // Assign combinations to every item
            foreach ($children as $child) {
                $descendant = (!is_array($child)) ? [$child] : $child;
                $stacks[] = array_merge([$item], $descendant);
            }
        }

        return $stacks;

    }
}