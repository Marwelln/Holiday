# Requirements

This class requires **PHP 7** unless you use the `php5` branch. The php5 branch does not us our `Collection` class.

# Installation

For **PHP 7** and later:

    composer require marwelln/holiday:~2.0

For **PHP 5.4** and later:

    compsoer require marwelln/holiday:dev-php5

# About

This class helps you know when holidays occours in Sweden. You can either get all holidays in an iterable `Marwelln\Holiday\Collection` or check when a specific holiday occours. All results returns a [`DateTime`](http://php.net/manual/en/class.datetime.php) object that you can use to format the date the way you want it.

# Usage

    // Get all holidays as a `Marwelln\Holiday\Collection`.
    $holidays = (new \Marwelln\Holiday)->get($year); // `$year` can be removed if you want to use current year.
    $holidays = (new \Marwelln\Holiday)->year($year)->get();

    // Check when easter occours.
    $easter = (new \Marwelln\Holiday)->when('easter'); // 27 mars, as of 2016 (current year)
    $easter = (new \Marwelln\Holiday)->year(2015)->when('easter'); // 5 april

    // Get holidays between two dates.
    $holidays = (new \Marwelln\Holiday)->between(new \DateTime('2015-01-01'), new \DateTime('2015-03-24')); // Collection of holidays

    // Format the collection.
    $holidays = (new \Marwelln\Holiday)->get();
    $formatted = $holidays->map(function($holiday){
        return [
            'id' => $holiday['id'],
            'date' => $holiday['date']->format('Y-m-d'),
            'name' => trans('holiday.' . $holiday['id']), // Custom function from Laravel
            'onWeekend' => in_array($holiday['date']->format('N'), [6, 7])
        ];
    }, $holidays);

# Available holidays

- newyearsday (nyårsdagen)
- epiphany (trettondedag jul)
- easter (påskdagen)
- goodfriday (långfredagen)
- eastermonday (annandag påsk)
- ascensionday (kristi himmelfärdsdag)
- pentecostday (pingstdagen)
- mayday (första maj)
- swedishnationalday (svenska nationaldagen)
- midsummerday (midsommar)
- allsaintsday (alla helgons dag)
- christmasday (juldagen)
- boxingday (annandag jul)