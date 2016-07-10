<?php

namespace Marwelln;

use Carbon\Carbon;

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
    public function get(int $year = null) {
        $this->year($year);

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
     * Set the current year to work with.
     *
     * @param  int    $year
     *
     * @return this
     */
    public function year(int $year) {
        $this->year = $year ?? $this->year ?? date('Y');

        return $this;
    }

    /**
     * When's New Year's Day (Nyårsdagen)?
     *     1st january
     *
     * @return Carbon
     */
    protected function holidayNewYearsDay() {
        $date = Carbon::create($this->year, 1, 1, 0, 0, 0);
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
        $date = Carbon::create($this->year, 1, 6, 0, 0, 0);
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

        $date = Carbon::create($this->year, 3, 21, 0, 0, 0);
        $date->addDays(easter_days($date->year));

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

        return $this->holidays['goodfriday'] = (new Carbon($this->holidays['easter']))->subDays(2);
    }

    /**
     * When's Easter Monday (Annandag påsk)?
     *     Day after easter.
     *
     * @return Carbon
     */
    protected function holidayEasterMonday() {
        $this->holidayEaster();

        return $this->holidays['eastermonday'] = (new Carbon($this->holidays['easter']))->addDay();
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
        return $this->holidays['ascensionday'] = (new Carbon($this->holidays['easter']))->addDays(4 + 5 * 7);
    }

    /**
     * When's Pentecost Day (Pingstdagen)?
     *     Seventh sunday after Easter.
     *
     * @return Carbon
     */
    protected function holidayPentecostDay() {
        $this->holidayEaster();

        return $this->holidays['pentecostday'] = (new Carbon($this->holidays['easter']))->addDays(7 * 7);
    }

    /**
     * When's May Day (Första maj)?
     *     1 maj.
     *
     * @return Carbon
     */
    protected function holidayMayDay() {
        return $this->holidays['mayday'] = Carbon::create($this->year, 5, 1, 0, 0, 0);
    }

    /**
     * When's Swedish national day (Svenska nationaldagen)?
     *     6 june.
     *
     * @return Carbon
     */
    protected function holidaySwedishNationalDay() {
        return $this->holidays['swedishnationalday'] = Carbon::create($this->year, 6, 6, 0, 0, 0);
    }

    /**
     * When's Midsummer day (Midsommar)?
     *     The saturday that occours between 20th and the 26th of june.
     *
     * @return Carbon
     */
    protected function holidayMidsummerDay() {
        $date = Carbon::create($this->year, 6, 20, 0, 0, 0);

        for ($i = 0; $i <= 6; ++$i) {
            if ($i) $date->addDay();

            if ($date->dayOfWeek == 6)
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
        $date = Carbon::create($this->year, 10, 31, 0, 0, 0);

        for ($i = 0; $i <= 6; ++$i) {
            if ($i) $date->addDay();

            if ($date->dayOfWeek == 6)
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
        return $this->holidays['christmasday'] = Carbon::create($this->year, 12, 25, 0, 0, 0);
    }

    /**
     * When's Boxing Day (Annandag jul)?
     *     26 december.
     *
     * @return Carbon
     */
    protected function holidayBoxingDay() {
        return $this->holidays['boxingday'] = Carbon::create($this->year, 12, 26, 0, 0, 0);
    }
}