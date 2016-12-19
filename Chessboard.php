<?php

/**
 * Класс представляет собой модель верхнего уровня и инкапсулирует использование классов фигур
 * Для сохраниния состояния во внешнее хранилище, доска сериализуется в строку формата JSON.
 * Восстановить состояние можно передав сохраненную строку, как параметр конструктору.
 * Менять текущее состояние на сохранённое не имеет смысла, поэтому сетера нет.
 *
 * @author user
 */
class Chessboard
{
    /**
     * В данной переменной сохраняется доска между запросами
     * Возможно понадобится преобразовать в объект и вызывать метод,
     * но для простоты - строка.
     *
     * @var string
     */
    private $storage;

    /**
     * Массив методов обратного вызова при событии
     *
     * @var array
     */
    private $callbacks = array();

    /**
     * Состояние доски
     *
     * @var array
     */
    private $state = array();

    /**
     * Состояние доски по умолчания
     *
     * @var array 
     */
    // TODO не помню правила, но надо заполнить
    private static $default   = array(
        
    );

    /**
     * Размер доски, для простоты используется константа
     * Если понадобится можно добавить сетер
     *
     * @var int
     */
    private $max = 8;

    /**
     * Конструктор будет будет создавать обьект доски с помошью приватных методов. Первый метод будет
     * инициализировать доску по умолчанию. Второй - восстанавливать из строки сохранённое состояние.
     * 
     * @param string строка JSON, полученаю методом toJson
     */
    public function __construct($storage = NULL)
    {
        $this->storage = ($storage) ?: $_SESSION['state'];

        if (empty($this->storage)) {
            $this->state = self::$default;
        } else {
            $this->state = json_decode($_SESSION['state']);
        }
    }

    public function __destruct()
    {
        $_SESSION['state'] = json_encode($this->state);
    }

    /**
     * Уведомление о событии
     * Содержимое объекта уведомления зависит от бизнес-логики приложения
     *
     * @param int $type тип передвинутой или удаленной фигуры
     */
    private function notify($type = NULL)
    {
        if ($type and isset($this->callbacks[$type])) {
            foreach ($this->callbacks[$type] as $callback) {
                $callback($type);
            }
        }

        foreach ($this->callbacks['all'] as $callback) {
            $callback($type);
        }
    }

    /**
     * Подписка на событие перемещения фигур, либо всех, либо конкретного типа.
     *
     * @param callable $callback
     * @param int $type
     * @return boolean
     */
    public function addListener($callback, $type = 'all')
    {
        $this->callbacks[$type][] = $callback;

        return TRUE;
    }

    /**
     * Добавление новой фигуры на доску
     *
     * @param int $x
     * @param int $y
     * @throws Exception
     */
    public function addPiece($type, $color, $x, $y)
    {
        if (
                isset($this->state[$x][$y])
                or !$this->checkRange($x, $y)
        ) {
            throw new Exception('Destination unavailable');
        } else {
            $this->state[$x][$y] = array(
                'type'  => $type,
                'color' => $color,
            );
            $this->notify($type);
        }
    }

    /**
     * Удаляет фигуру из указанной клетки
     * Логично было сделать метод приватным, но в ТЗ сказано нужно.
     *
     * @param int $x
     * @param int $y
     */
    public function removePiece($x, $y)
    {
        if (isset($this->state[$x][$y])) {
            $this->notify($this->state[$x][$y]['type']);
            unset($this->state[$x][$y]);
        }
    }

    /**
     * Перемещает фигуру с первой клетки во вторую
     *
     * @param int $x1
     * @param int $y1
     * @param int $x2
     * @param int $y2
     * @throws Exception
     */
    public function movePiece($x1, $y1, $x2, $y2)
    {
        if ( !isset($this->state[$x1][$y1]) ) {
            throw new Exception('Empty position');
        }

        if ( !$this->checkRange($x2, $y2) ) {
            throw new Exception('Destination unavailable');
        }

        if ( $this->state[$x1][$y1]['color'] === $this->state[$x2][$y2]['color'] ) {
            throw new Exception('Destination unavailable');
        }

        $this->step = array(
            'x1' => $x1,
            'y1' => $y1,
            'x2' => $x2,
            'y2' => $y2,
        );

        $piece = \pieces\Piece::build($this, $this->state[$x1][$y1]);

        if ($piece->tryToMove()) {
            if (isset($this->state[$x2][$y2])) {
                $this->removePiece($x2, $y2);
            }

            $this->state[$x2][$y2] = $this->state[$x1][$y1];
            unset($this->state[$x1][$y1]);
        }
    }

    /**
     * Простая проверка не выходит ли координата за пределы доски
     *
     * @param int $x
     * @param int $y
     * @return boolean
     */
    private function checkRange($x, $y)
    {
        if (
                $x > $this->max
                or $x < 0
                or $y > $this->max
                or $y < 0
        ) {
            return FALSE;
        } else {
            return TRUE;
        }

    }

    /**
     * Преобразует состояние доски в строку формата JSON
     * для сохранения во внешнее хранилище.
     *
     * @return string состояние доски в формате JSON
     */
    public function toJson()
    {
        return json_encode($this->state);
    }

    public function getColorAt($x, $y)
    {
        return (isset($this->state[$x][$y])) ? $this->state[$x][$y]['color'] : NULL;
    }
}
