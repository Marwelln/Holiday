<?php namespace Marwelln;

use DateTime, DateInterval;
use Marwelln\Holiday\Collection;

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
        'newyearseve' => null,
        'epiphany' => null,
        'easter' => null,
        'goodfriday' => null,
        'eastermonday' => null,
        'ascensionday' => null,
        'pentecostday' => null,
        'mayday' => null,
        'swedishnationalday' => null,
        'midsummereve' => null,
        'midsummerday' => null,
        'allsaintsday' => null,
        'christmaseve' => null,
        'christmasday' => null,
        'boxingday' => null
    ];

    /**
     * Get all holidays for the current year.
     */
    public function get(?int $year = null) : Collection {
        $this->year($year);

        $this->run(array_keys($this->holidays));

        return new Collection($this->holidays);
    }

    /**
     * Get dates for selected holidays.
     */
    public function run(array $holidays) : self {
        foreach ($holidays as $holiday) {
            $this->{"holiday" . $holiday}();
        }

        return $this;
    }

    /**
     * Get when does the selected holiday occour.
     */
    public function when(string $holiday) : DateTime {
        if ( ! method_exists($this, "holiday" . $holiday))
            throw new \Exception("Could not find holiday \"$holiday\". Valid holidays are: " . implode(', ', array_keys($this->holidays)) . ".");

        $this->year(null);

        return $this->{"holiday" . $holiday}();
    }

    /**
     * Get all holidays between selected dates.
     */
    public function between(DateTime $start, DateTime $end) : Collection {
        $holidays = [];
        for ($year = $start->format('Y'); $year <= $end->format('Y'); ++$year) {
            foreach ((new static)->get($year) as $id => $holiday) {
                if ($holiday->format('Y-m-d') >= $start->format('Y-m-d') &&
                    $holiday->format('Y-m-d') <= $end->format('Y-m-d')) {
                    $holidays[] = ['id' => $id, 'date' => $holiday];
                }
            }
        }

        return new Collection($holidays);
    }

    /**
     * Set the current year to work with.
     */
    public function year(?int $year) : Holiday {
        $this->year = $year ? $year : $this->year ?? date('Y');

        return $this;
    }

    /**
     * When's New Year's Eve (Nyårsafton)?
     *     31st december
     */
    protected function holidayNewYearsEve() : DateTime {
        $date = DateTime::createFromFormat('Y-m-d H:i:s', $this->year . '-12-31 00:00:00');
        $this->holidays['newyearseve'] = $date;

        return $date;
    }

    /**
     * When's New Year's Day (Nyårsdagen)?
     *     1st january
     */
    protected function holidayNewYearsDay() : DateTime {
        $date = DateTime::createFromFormat('Y-m-d H:i:s', $this->year . '-01-01 00:00:00');
        $this->holidays['newyearsday'] = $date;

        return $date;
    }

    /**
     * When's Ephinany (Trettondedag jul)?
     *     6st january
     */
    protected function holidayEpiphany() : DateTime {
        $date = DateTime::createFromFormat('Y-m-d H:i:s', $this->year . '-01-06 00:00:00');
        $this->holidays['epiphany'] = $date;

        return $date;
    }

    /**
     * When's Easter (Påskdagen)?
     *     Closest sunday after the fullmoon that occours closest on or after 21 mars (in Sweden).
     */
    protected function holidayEaster() : DateTime {
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
     */
    protected function holidayGoodFriday() : DateTime {
        $this->holidayEaster();

        $date = (new DateTime($this->holidays['easter']->format('Y-m-d')));
        $date->sub(new DateInterval('P2D'));

        return $this->holidays['goodfriday'] = $date;
    }

    /**
     * When's Easter Monday (Annandag påsk)?
     *     Day after easter.
     */
    protected function holidayEasterMonday() : DateTime {
        $this->holidayEaster();

        $date = (new DateTime($this->holidays['easter']->format('Y-m-d')));
        $date->add(new DateInterval('P1D'));

        return $this->holidays['eastermonday'] = $date;
    }

    /**
     * When's Feast of Ascension (Kristi himmelfärdsdag)?
     *     Sixth thursday after easter.
     */
    protected function holidayAscensionDay() : DateTime {
        $this->holidayEaster();

        // 4 days to next thursday, then 5 weeks of days after that.
        $date = (new DateTime($this->holidays['easter']->format('Y-m-d')));
        $date->add(new DateInterval('P' . (4 + 5 * 7) . 'D'));

        return $this->holidays['ascensionday'] = $date;
    }

    /**
     * When's Pentecost Day (Pingstdagen)?
     *     Seventh sunday after Easter.
     */
    protected function holidayPentecostDay() : DateTime {
        $this->holidayEaster();

        $date = (new DateTime($this->holidays['easter']->format('Y-m-d')));
        $date->add(new DateInterval('P' . (7 * 7) . 'D'));

        return $this->holidays['pentecostday'] = $date;
    }

    /**
     * When's May Day (Första maj)?
     *     1 maj.
     */
    protected function holidayMayDay() : DateTime {
        return $this->holidays['mayday'] = DateTime::createFromFormat('Y-m-d H:i:s', $this->year . '-05-01 00:00:00');
    }

    /**
     * When's Swedish national day (Svenska nationaldagen)?
     *     6 june.
     */
    protected function holidaySwedishNationalDay() : DateTime {
        return $this->holidays['swedishnationalday'] = DateTime::createFromFormat('Y-m-d H:i:s', $this->year . '-06-06 00:00:00');
    }

    /**
     * When's Midsummer day (Midsommar)?
     *     The saturday that occours between 20th and the 26th of june.
     */
    protected function holidayMidsummerDay() : DateTime {
        $date = DateTime::createFromFormat('Y-m-d H:i:s', $this->year . '-06-20 00:00:00');

        for ($i = 0; $i <= 6; ++$i) {
            if ($i) $date->add(new DateInterval('P1D'));

            if ($date->format('w') == 6)
                return $this->holidays['midsummerday'] = $date;
        }

        throw new \Exception('Could not find midsummer day.');
    }

    /**
     * When's Midsummer eve (Midsommarafton)?
     *     The friday that occours before midsummer.
     */
    protected function holidayMidsummerEve() : DateTime {
        $this->run(['midsummerday']);

        $date = (clone $this->holidays['midsummerday'])->modify('-1 day');

        return $this->holidays['midsummereve'] = $date;
    }

    /**
     * When's All Saint's Day (Alla helgons dag)?
     *     The saturday that occours between 31st october to 6th november.
     */
    protected function holidayAllSaintsDay() : DateTime {
        $date = DateTime::createFromFormat('Y-m-d H:i:s', $this->year . '-10-31 00:00:00');

        for ($i = 0; $i <= 6; ++$i) {
            if ($i) $date->add(new DateInterval('P1D'));

            if ($date->format('w') == 6)
                return $this->holidays['allsaintsday'] = $date;
        }

        throw new \Exception('Could not find All Saints\' day.');
    }

    /**
     * When's Christmas Eve (Julafton)?
     *     24 december.
     */
    protected function holidayChristmasEve() : DateTime {
        return $this->holidays['christmaseve'] = DateTime::createFromFormat('Y-m-d H:i:s', $this->year . '-12-24 00:00:00');
    }

    /**
     * When's Christmas Day (Juldagen)?
     *     25 december.
     */
    protected function holidayChristmasDay() : DateTime {
        return $this->holidays['christmasday'] = DateTime::createFromFormat('Y-m-d H:i:s', $this->year . '-12-25 00:00:00');
    }

    /**
     * When's Boxing Day (Annandag jul)?
     *     26 december.
     */
    protected function holidayBoxingDay() : DateTime {
        return $this->holidays['boxingday'] = DateTime::createFromFormat('Y-m-d H:i:s', $this->year . '-12-26 00:00:00');
    }
}