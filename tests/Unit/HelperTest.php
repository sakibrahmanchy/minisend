<?php

namespace Tests\Unit;

use App\Enums\Status;
use App\Exceptions\ShouldBeStringException;
use PHPUnit\Framework\TestCase;

class HelperTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testEnumHelperMethod()
    {
        $statusInstance = enum(Status::class);
        $this->assertInstanceOf(\ReflectionClass::class, $statusInstance->getClass());

        try {
            enum('UnknowClass');
        } catch (\Exception $exception) {
            $this->assertInstanceOf(\ReflectionException::class, $exception);
        }

        $values = $statusInstance->getValues();
        $this->assertIsArray($values);
        $this->assertEquals(['0', '1', '2'], $values);

        $properties = $statusInstance->getProperties();
        $this->assertIsArray($values);
        $this->assertEquals([
            $statusInstance->getKey('0'),
            $statusInstance->getKey('1'),
            $statusInstance->getKey('2'),
        ], $properties);

        $this->assertIsString($statusInstance->getKey(Status::POSTED));

        $this->assertIsBool($statusInstance->getKey('UNKNOWN'));
    }

    public function testDefaultDateFormat()
    {
        $date = new \DateTime();
        try {
           default_date_format_from_string($date);
        } catch (\Exception $exception) {
            $this->assertInstanceOf(ShouldBeStringException::class, $exception);
        }

        list($day,$month,$year,$hour,$min,$sec,$a) = explode("/",date('d/m/Y/h/i/s/a'));
        $formattedDate = default_date_format_from_string($month.'/'.$day.'/'.$year.' '.$hour.':'.$min.':'.$sec.' '.$a);
        $this->assertEquals("$year/$month/$day $hour:$min $a", $formattedDate);
    }

    public function testformatSizeUnits()
    {
        $this->assertEquals('0 bytes', formatSizeUnits(-1));
        $this->assertEquals('1 byte', formatSizeUnits(1));
        $this->assertEquals('100 bytes', formatSizeUnits(100));
        $this->assertEquals('1023 bytes', formatSizeUnits(1023));
        $this->assertEquals('1.00 KB', formatSizeUnits(1025));
        $this->assertEquals('1.37 KB', formatSizeUnits(1400));
        $this->assertEquals('1.00 MB', formatSizeUnits(1048577));
        $this->assertEquals('1.00 GB', formatSizeUnits(1073741825));
        $this->assertEquals('9.38 GB', formatSizeUnits(10073741825));
    }
}
