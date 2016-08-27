<?php

namespace Marwelln;

use DateTime;
use DateInterval;

/**
 * Get swedish holidays.
 *
 * Get all holidays:
 *     var_dump((new Holiday)->get($year = null));
 *
 * Check when specific holiday occours:
 *     echo (new Holiday)->when('midsummerday');
 *
 * Check the holidays property to see which holidays are available.
 */
class Holiday {
    /**
     * @var int
     */
    protected $year;

    /**
     * @var array
     */
    protected $holidays = [
        'newyearsday' => null,
        'epiphany' => null,
        'easter' => null,
        'goodfriday' => null,
        'eastermonday' => null,
        'ascensionday' => null,
        'pentecostday' => null,
        'mayday' => null,
        'swedishnationalday' => null,
        'midsummerday' => null,
        'allsaintsday' => null,
        'christmasday' => null,
        'boxingday' => null
    ];

    /**
     * Get all holidays for the current year.
     *
     * @return array
     */
    public function get($year = null) {
        $this->year((int) $year);

        $this->run([
            'newYearsDay', 'epiphany', 'easter',
            'goodfriday', 'eastermonday', 'ascensionday',
            'pentecostday', 'mayday', 'swedishnationalday',
            'midsummerday', 'allsaintsday', 'christmasday',
            'boxingday'
        ]);

        return $this->holidays;
    }

    /**
     * Get dates for selected holidays.
     *
     * @param  array  $holidays
     * @return [type]
     */
    public function run(array $holidays) {
        foreach ($holidays as $holiday) {
            $this->{"holiday" . $holiday}();
        }
    }

    /**
     * Get when does the selected holiday occour.
     *
     * @param  string $holiday
     *
     * @return Carbon
     */
    public function when(string $holiday) {
        if ( ! method_exists($this, "holiday" . $holiday))
            throw new \Exception("Could not find holiday \"$holiday\". Valid holidays are: " . implode(', ', array_keys($this->holidays)) . ".");

        return $this->{"holiday" . $holiday}();
    }

    /**
     * Get all holidays between selected dates.
     *
     * @param  DateTime $start
     * @param  DateTime $end
     *
     * @return array
     */
    public function between(DateTime $start, DateTime $end) : array {
        $holidays = [];
        for ($year = $start->format('Y'); $year <= $end->format('Y'); ++$year) {
            foreach ((new static)->get($year) as $id => $holiday) {
                if ($holiday->format('Y-m-d') >= $start->format('Y-m-d') &&
                    $holiday->format('Y-m-d') <= $end->format('Y-m-d')) {
                    $holidays[] = ['id' => $id, 'date' => $holiday];
                }
            }
        }

        return $holidays;
    }

    /**
     * Set the current year to work with.
     *
     * @param  int    $year
     *
     * @return this
     */
    public function year(int $year) {
        $this->year = $year ? $year : $this->year ?? date('Y');

        return $this;
    }

    /**
     * When's New Year's Day (Nyårsdagen)?
     *     1st january
     *
     * @return Carbon
     */
    protected function holidayNewYearsDay() {
        $date = DateTime::createFromFormat('Y-m-d H:i:s', $this->year . '-01-01 00:00:00');
        $this->holidays['newyearsday'] = $date;

        return $date;
    }

    /**
     * When's Ephinany (Trettondedag jul)?
     *     6st january
     *
     * @return Carbon
     */
    protected function holidayEpiphany() {
        $date = DateTime::createFromFormat('Y-m-d H:i:s', $this->year . '-01-06 00:00:00');
        $this->holidays['epiphany'] = $date;

        return $date;
    }

    /**
     * When's Easter (Påskdagen)?
     *     Closest sunday after the fullmoon that occours closest on or after 21 mars (in Sweden).
     *
     * @return Carbon
     */
    protected function holidayEaster() {
        if ($this->holidays['easter'] !== null)
            return $this->holidays['easter'];

        $date = DateTime::createFromFormat('Y-m-d H:i:s', $this->year . '-03-21 00:00:00');
        $date->add(new DateInterval('P' . easter_days($this->year) . 'D'));

        $this->holidays['easter'] = $date;

        return $date;
    }

    /**
     * When's Good Friday (Långfredagen)?
     *     Closest friday before easter.
     *
     * @return Carbon
     */
    protected function holidayGoodFriday() {
        $this->holidayEaster();

        $date = (new DateTime($this->holidays['easter']->format('Y-m-d')));
        $date->sub(new DateInterval('P2D'));

        return $this->holidays['goodfriday'] = $date;
    }

    /**
     * When's Easter Monday (Annandag påsk)?
     *     Day after easter.
     *
     * @return Carbon
     */
    protected function holidayEasterMonday() {
        $this->holidayEaster();

        $date = (new DateTime($this->holidays['easter']->format('Y-m-d')));
        $date->add(new DateInterval('P1D'));

        return $this->holidays['eastermonday'] = $date;
    }

    /**
     * When's Feast of Ascension (Kristi himmelfärdsdag)?
     *     Sixth thursday after easter.
     *
     * @return Carbon
     */
    protected function holidayAscensionDay() {
        $this->holidayEaster();

        // 4 days to next thursday, then 5 weeks of days after that.
        $date = (new DateTime($this->holidays['easter']->format('Y-m-d')));
        $date->add(new DateInterval('P' . (4 + 5 * 7) . 'D'));

        return $this->holidays['ascensionday'] = $date;
    }

    /**
     * When's Pentecost Day (Pingstdagen)?
     *     Seventh sunday after Easter.
     *
     * @return Carbon
     */
    protected function holidayPentecostDay() {
        $this->holidayEaster();

        $date = (new DateTime($this->holidays['easter']->format('Y-m-d')));
        $date->add(new DateInterval('P' . (7 * 7) . 'D'));

        return $this->holidays['pentecostday'] = $date;
    }

    /**
     * When's May Day (Första maj)?
     *     1 maj.
     *
     * @return Carbon
     */
    protected function holidayMayDay() {
        return $this->holidays['mayday'] = DateTime::createFromFormat('Y-m-d H:i:s', $this->year . '-05-01 00:00:00');
    }

    /**
     * When's Swedish national day (Svenska nationaldagen)?
     *     6 june.
     *
     * @return Carbon
     */
    protected function holidaySwedishNationalDay() {
        return $this->holidays['swedishnationalday'] = DateTime::createFromFormat('Y-m-d H:i:s', $this->year . '-06-06 00:00:00');
    }

    /**
     * When's Midsummer day (Midsommar)?
     *     The saturday that occours between 20th and the 26th of june.
     *
     * @return Carbon
     */
    protected function holidayMidsummerDay() {
        $date = DateTime::createFromFormat('Y-m-d H:i:s', $this->year . '-06-20 00:00:00');

        for ($i = 0; $i <= 6; ++$i) {
            if ($i) $date->add(new DateInterval('P1D'));

            if ($date->format('w') == 6)
                return $this->holidays['midsummerday'] = $date;
        }

        throw new \Exception('Could not find midsummer day.');
    }

    /**
     * When's All Saint's Day (Alla helgons dag)?
     *     The saturday that occours between 31st october to 6th november.
     *
     * @return Carbon
     */
    protected function holidayAllSaintsDay() {
        $date = DateTime::createFromFormat('Y-m-d H:i:s', $this->year . '-10-31 00:00:00');

        for ($i = 0; $i <= 6; ++$i) {
            if ($i) $date->add(new DateInterval('P1D'));

            if ($date->format('w') == 6)
                return $this->holidays['allsaintsday'] = $date;
        }

        throw new \Exception('Could not find All Saints\' day.');
    }

    /**
     * When's Christmas Day (Juldagen)?
     *     25 december.
     *
     * @return Carbon
     */
    protected function holidayChristmasDay() {
        return $this->holidays['christmasday'] = DateTime::createFromFormat('Y-m-d H:i:s', $this->year . '-12-25 00:00:00');
    }

    /**
     * When's Boxing Day (Annandag jul)?
     *     26 december.
     *
     * @return Carbon
     */
    protected function holidayBoxingDay() {
        return $this->holidays['boxingday'] = DateTime::createFromFormat('Y-m-d H:i:s', $this->year . '-12-26 00:00:00');
    }
}