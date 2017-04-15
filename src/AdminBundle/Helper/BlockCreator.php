<?php

namespace AdminBundle\Helper;

class BlockCreator
{
    /**
     * @param array $words
     * @return array
     */
    public static function getBlocks($words)
    {
        $wordBlocks = array();

        $temp = array();
        foreach ($words as $index => $word) {
            if ($index === 0) {
                $temp[] = $word;

                continue;
            }

            if (($index % 4) === 0) {
                $wordBlocks[] = $temp;
                $temp = array();

                $temp[] = $word;

                continue;
            }

            $temp[] = $word;
        }

        if (!empty($temp)) {
            $wordBlocks[] = $temp;
        }

        return $wordBlocks;
    }
}