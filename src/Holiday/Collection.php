<?php
namespace Marwelln\Holiday;

use DateTime;

class Collection implements \IteratorAggregate {
    protected $holidays = [];

    public function __construct(array $holidays) {
        $this->holidays = $holidays;
    }

    public function getIterator() {
        return new \ArrayIterator($this->holidays);
    }

    /**
     * Check if selected date is a holiday.
     *
     * @param  DateTime $date
     */
    public function isHoliday(DateTime $date) : bool {
        foreach ($this->holidays as $holiday) {
            if ($holiday['date']->format('Y-m-d') === $date->format('Y-m-d'))
                return true;
        }

        return false;
    }

    /**
     * Return the collection with a custom format.
     */
    public function map(callable $callback) : Collection {
        return new static(array_map($callback, $this->holidays));
    }

    /**
     * Get all holidays as an array.
     */
    public function toArray() : array {
        return $this->holidays;
    }
}