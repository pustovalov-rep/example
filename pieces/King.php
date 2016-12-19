<?php

namespace pieces;

/**
 * Description of King
 *
 * @author user
 */
class King extends \pieces\Piece
{
    public function tryToMove($x1, $y1, $x2, $y2)
    {
        if (
                abs($x1 - $x2) = 1 and abs($y1 - $y2) = 2
                or abs($x1 - $x2) = 2 and abs($y1 - $y2) = 1
        ) {
            return $this->endMove($x1, $y1, $x2, $y2);
        }

        return FALSE;
    }
}
