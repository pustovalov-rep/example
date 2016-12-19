<?php

namespace pieces;

/**
 * Description of Knight
 *
 * @author user
 */
class Knight extends \pieces\Piece
{
    public function tryToMove($x1, $y1, $x2, $y2)
    {
        if (
                (abs($this->chessboard->step['x1'] - $this->chessboard->step['x2']) = 1
                    and abs($this->chessboard->step['y1'] - $this->chessboard->step['y2']) = 2)
                or (abs($this->chessboard->step['x1'] - $this->chessboard->step['x2']) = 2
                    and abs($this->chessboard->step['y1'] - $this->chessboard->step['y2']) = 1)
        ) {
            return TRUE;
        }

        return FALSE;
    }
}
