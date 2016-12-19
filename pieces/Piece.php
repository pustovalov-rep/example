<?php

namespace pieces;

/**
 * Все фигуры должны иметь свой тип и цвет команды
 * Так же восстанавливать свое состояние из строки и преобразовываться в строку
 * Для фигур выбран родительский класс, так как они имеют общие данные, которые должны присутствовать
 * во всех дочерних классов.
 *
 * @author user
 */
abstract class Piece
{
    const PREFIX = '\\pieces\\';
    
    // Типы фигур, значиния будут использоваться для событий и храниния типа фигуры
    const TYPE_KING   = 0;
    const TYPE_PAWN   = 1;
    const TYPE_KNIGHT = 2;

    const COLOR_WHITE = 0;
    const COLOR_BLACK = 1;

    /**
     * Наследуемое поле цвет фигуры
     *
     * @var int
     */
    protected $color;
    /**
     * Наследуемое поле тип фигуры
     *
     * @var int
     */
    protected $type;
    protected $chessboard;

    /**
     * Карта соответствий между типами фигур и классами этих типов
     *
     * @var array
     */
    private $map = array(
        self::TYPE_KING   => 'King',
        self::TYPE_PAWN   => 'Pawn',
        self::TYPE_KNIGHT => 'Knight',
    );

    /**
     * Фабричный метод, упростит инициализацию доски
     *
     * @param int $type
     * @param int $color
     * @return \pieces\Piece
     * @throws Exception
     */
    public static function build($chessboard, $state)
    {
        if ( !isset($this->map[$state['type']]) ) {
            throw new Exception;
        }

        $class = self::PREFIX + $this->map[$state['type']];

        return new $class($state);
    }

    /**
     * Создает фигуру указанного типа и цвета
     *
     * @param int $color   костанта self::COLOR_*
     * @param int $type    костанта self::TYPE_*
     */
    public function __construct($state)
    {
        $this->color = $state['color'];
        $this->type  = $state['type'];
    }

    public function getColor()
    {
        return $this->color;
    }

    public function getType()
    {
        return $this->type;
    }

    /**
     * Валидация хода
     *
     * @return boolean
     */
    public abstract function tryToMove();
}
