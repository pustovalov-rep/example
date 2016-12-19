<?php

namespace pieces;

/**
 * Description of Pawn
 *
 * @author user
 */
class Pawn extends \pieces\Piece
{
    public function tryToMove()
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
