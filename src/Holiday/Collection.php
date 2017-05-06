<?php
namespace Marwelln\Holiday;

use DateTime;

class Collection implements \IteratorAggregate, \ArrayAccess, \Countable, \JsonSerializable {
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
     * Use given key from callback on the array.
     */
    public function keyBy(callable $callback) : Collection {
        $holidays = [];

        foreach ($this->holidays as $key => $holiday) {
            $holidays[call_user_func($callback, $holiday)] = $holiday;
        }

        return new static($holidays);
    }

    /**
     * Get all holidays as an array.
     */
    public function toArray() : array {
        return $this->holidays;
    }

    /**
     * Make sure we can run json_encode on the class.
     */
    public function jsonSerialize() : array {
        return $this->toArray();
    }

    /**
     * Count have many holidays we have fetched.
     * @return [type]
     */
    public function count() {
        return count($this->holidays);
    }

    /**
     * ArrayAccess method.
     */
    public function offsetGet($offset) : ?array {
        return isset($this->holidays[$offset]) ? $this->holidays[$offset] : null;
    }

    /**
     * ArrayAccess method.
     */
    public function offsetExists($offset) : bool {
        return isset($this->holidays[$offset]);
    }

    /**
     * ArrayAccess method.
     */
    public function offsetSet($offset, $value) { }
    public function offsetUnset($offset) { }

}