<?php
use PHPUnit\Framework\TestCase;

use Marwelln\{ Holiday, Holiday\Collection };

class HolidayTest extends TestCase {
    public function testGetAllHolidaysFromCurrentYear() {
        $holidays = (new Holiday)->get();

        $this->assertInstanceOf(Collection::class, $holidays);
    }

    public function testGetAllHolidaysFromSelectedYear() {
        $holidays = (new Holiday)->get(2017);

        $this->assertInstanceOf(Collection::class, $holidays);
    }

    public function testWhenAHoliodayOccours() {
        $this->assertInstanceOf(DateTime::class, (new Holiday)->year(2017)->when('mayday'));
        $this->assertInstanceOf(DateTime::class, (new Holiday)->year(2016)->when('mayday'));
        $this->assertInstanceOf(DateTime::class, (new Holiday)->year(null)->when('mayday'));

        $year = date('Y');

        $holiday = (new Holiday)->when('newyearsday');
        $this->assertInstanceOf(DateTime::class, $holiday);
        $this->assertEquals("{$year}-01-01", $holiday->format('Y-m-d'));

        $holiday = (new Holiday)->year(2015)->when('newyearseve');
        $this->assertInstanceOf(DateTime::class, $holiday);
        $this->assertEquals('2015-12-31', $holiday->format('Y-m-d'));

        $holiday = (new Holiday)->year(2016)->when('newyearsday');
        $this->assertInstanceOf(DateTime::class, $holiday);
        $this->assertEquals('2016-01-01', $holiday->format('Y-m-d'));

        $holiday = (new Holiday)->when('epiphany');
        $this->assertInstanceOf(DateTime::class, $holiday);
        $this->assertEquals("{$year}-01-06", $holiday->format('Y-m-d'));

        $holiday = (new Holiday)->year(2016)->when('epiphany');
        $this->assertInstanceOf(DateTime::class, $holiday);
        $this->assertEquals('2016-01-06', $holiday->format('Y-m-d'));

        $holiday = (new Holiday)->year(2017)->when('easter');
        $this->assertInstanceOf(DateTime::class, $holiday);
        $this->assertEquals("2017-04-16", $holiday->format('Y-m-d'));

        $holiday = (new Holiday)->year(2016)->when('easter');
        $this->assertInstanceOf(DateTime::class, $holiday);
        $this->assertEquals('2016-03-27', $holiday->format('Y-m-d'));

        $holiday = (new Holiday)->year(2017)->when('goodfriday');
        $this->assertInstanceOf(DateTime::class, $holiday);
        $this->assertEquals("2017-04-14", $holiday->format('Y-m-d'));

        $holiday = (new Holiday)->year(2016)->when('goodfriday');
        $this->assertInstanceOf(DateTime::class, $holiday);
        $this->assertEquals('2016-03-25', $holiday->format('Y-m-d'));

        $holiday = (new Holiday)->year(2017)->when('eastermonday');
        $this->assertInstanceOf(DateTime::class, $holiday);
        $this->assertEquals("2017-04-17", $holiday->format('Y-m-d'));

        $holiday = (new Holiday)->year(2016)->when('eastermonday');
        $this->assertInstanceOf(DateTime::class, $holiday);
        $this->assertEquals('2016-03-28', $holiday->format('Y-m-d'));

        $holiday = (new Holiday)->year(2017)->when('ascensionday');
        $this->assertInstanceOf(DateTime::class, $holiday);
        $this->assertEquals("2017-05-25", $holiday->format('Y-m-d'));

        $holiday = (new Holiday)->year(2016)->when('ascensionday');
        $this->assertInstanceOf(DateTime::class, $holiday);
        $this->assertEquals('2016-05-05', $holiday->format('Y-m-d'));

        $holiday = (new Holiday)->year(2017)->when('pentecostday');
        $this->assertInstanceOf(DateTime::class, $holiday);
        $this->assertEquals("2017-06-04", $holiday->format('Y-m-d'));

        $holiday = (new Holiday)->year(2016)->when('pentecostday');
        $this->assertInstanceOf(DateTime::class, $holiday);
        $this->assertEquals('2016-05-15', $holiday->format('Y-m-d'));

        $holiday = (new Holiday)->when('mayday');
        $this->assertInstanceOf(DateTime::class, $holiday);
        $this->assertEquals("{$year}-05-01", $holiday->format('Y-m-d'));

        $holiday = (new Holiday)->year(2016)->when('mayday');
        $this->assertInstanceOf(DateTime::class, $holiday);
        $this->assertEquals('2016-05-01', $holiday->format('Y-m-d'));

        $holiday = (new Holiday)->when('swedishnationalday');
        $this->assertInstanceOf(DateTime::class, $holiday);
        $this->assertEquals("{$year}-06-06", $holiday->format('Y-m-d'));

        $holiday = (new Holiday)->year(2016)->when('swedishnationalday');
        $this->assertInstanceOf(DateTime::class, $holiday);
        $this->assertEquals('2016-06-06', $holiday->format('Y-m-d'));

        $holiday = (new Holiday)->year(2017)->when('midsummerday');
        $this->assertInstanceOf(DateTime::class, $holiday);
        $this->assertEquals("2017-06-24", $holiday->format('Y-m-d'));

        $holiday = (new Holiday)->year(2016)->when('midsummerday');
        $this->assertInstanceOf(DateTime::class, $holiday);
        $this->assertEquals('2016-06-25', $holiday->format('Y-m-d'));

        $holiday = (new Holiday)->year(2017)->when('allsaintsday');
        $this->assertInstanceOf(DateTime::class, $holiday);
        $this->assertEquals("2017-11-04", $holiday->format('Y-m-d'));

        $holiday = (new Holiday)->year(2016)->when('allsaintsday');
        $this->assertInstanceOf(DateTime::class, $holiday);
        $this->assertEquals('2016-11-05', $holiday->format('Y-m-d'));

        $holiday = (new Holiday)->when('christmasday');
        $this->assertInstanceOf(DateTime::class, $holiday);
        $this->assertEquals("{$year}-12-25", $holiday->format('Y-m-d'));

        $holiday = (new Holiday)->year(2016)->when('christmaseve');
        $this->assertInstanceOf(DateTime::class, $holiday);
        $this->assertEquals('2016-12-24', $holiday->format('Y-m-d'));

        $holiday = (new Holiday)->year(2016)->when('christmasday');
        $this->assertInstanceOf(DateTime::class, $holiday);
        $this->assertEquals('2016-12-25', $holiday->format('Y-m-d'));

        $holiday = (new Holiday)->when('boxingday');
        $this->assertInstanceOf(DateTime::class, $holiday);
        $this->assertEquals("{$year}-12-26", $holiday->format('Y-m-d'));

        $holiday = (new Holiday)->year(2016)->when('boxingday');
        $this->assertInstanceOf(DateTime::class, $holiday);
        $this->assertEquals('2016-12-26', $holiday->format('Y-m-d'));
    }

    public function testBetweenDates() {
        date_default_timezone_set('UTC');
        $holidays = (new Holiday)->between(new DateTime("2017-04-25"), new DateTime("2017-05-05"));

        $this->assertInstanceOf(Collection::class, $holidays);
        $this->assertCount(1, $holidays);

        $this->assertEquals('[{"id":"mayday","date":{"date":"2017-05-01 00:00:00.000000","timezone_type":3,"timezone":"UTC"}}]', json_encode($holidays));

        date_default_timezone_set('Europe/Stockholm');
        $holidays = (new Holiday)->between(new DateTime("2017-04-25"), new DateTime("2017-05-05"));

        $this->assertEquals('[{"id":"mayday","date":{"date":"2017-05-01 00:00:00.000000","timezone_type":3,"timezone":"Europe\/Stockholm"}}]', json_encode($holidays));
    }
}