<?php

namespace storages;

/**
 * Все типы хранилищ должны реализовывать этот интерфейс
 * Интерфейс был выбран, потому что объекты хранилищь имеют разные структуры и от них требуется
 * только общее поведение. Не стал уделять много времени хранилищам, стандартное действие, а выжно
 * показать архитектуру.
 *
 * @author user
 */
interface Storage
{
    public function load($id);
    public function save($chessboard);
}
