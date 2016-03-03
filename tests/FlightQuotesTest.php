<?php
/**
 * Project: skyscanner-php-sdk
 *
 * @author Amado Martinez <amado@projectivemotion.com>
 */

namespace Skyscanner\Response;

use Skyscanner\Tests\BaseSkyscannerTest;
use tests\FlightsTest;

class FlightQuotesTest extends BaseSkyscannerTest
{
    /**
     * @var FlightQuotes[]|FlightQuotes
     */
    protected $quotes = null;

    public function setUp()
    {
        parent::setUp();
        $response_obj = unserialize('O:8:"stdClass":1:{s:6:"parsed";O:8:"stdClass":5:{s:5:"Dates";O:8:"stdClass":2:{s:13:"OutboundDates";a:1:{i:0;O:8:"stdClass":4:{s:11:"PartialDate";s:10:"2016-03-05";s:8:"QuoteIds";a:2:{i:0;i:1;i:1;i:2;}s:5:"Price";d:170;s:13:"QuoteDateTime";s:19:"2016-03-03T12:39:00";}}s:12:"InboundDates";a:1:{i:0;O:8:"stdClass":4:{s:11:"PartialDate";s:10:"2016-03-08";s:8:"QuoteIds";a:2:{i:0;i:2;i:1;i:3;}s:5:"Price";d:170;s:13:"QuoteDateTime";s:19:"2016-03-03T12:39:00";}}}s:6:"Quotes";a:3:{i:0;O:8:"stdClass":5:{s:7:"QuoteId";i:1;s:8:"MinPrice";d:110;s:6:"Direct";b:1;s:11:"OutboundLeg";O:8:"stdClass":4:{s:10:"CarrierIds";a:1:{i:0;i:1914;}s:8:"OriginId";i:81959;s:13:"DestinationId";i:43268;s:13:"DepartureDate";s:19:"2016-03-05T00:00:00";}s:13:"QuoteDateTime";s:19:"2016-03-03T12:50:00";}i:1;O:8:"stdClass":6:{s:7:"QuoteId";i:2;s:8:"MinPrice";d:426;s:6:"Direct";b:0;s:11:"OutboundLeg";O:8:"stdClass":4:{s:10:"CarrierIds";a:1:{i:0;i:1368;}s:8:"OriginId";i:81959;s:13:"DestinationId";i:43268;s:13:"DepartureDate";s:19:"2016-03-05T00:00:00";}s:10:"InboundLeg";O:8:"stdClass":4:{s:10:"CarrierIds";a:1:{i:0;i:1368;}s:8:"OriginId";i:43268;s:13:"DestinationId";i:81959;s:13:"DepartureDate";s:19:"2016-03-08T00:00:00";}s:13:"QuoteDateTime";s:19:"2016-03-03T12:39:00";}i:2;O:8:"stdClass":5:{s:7:"QuoteId";i:3;s:8:"MinPrice";d:60;s:6:"Direct";b:1;s:10:"InboundLeg";O:8:"stdClass":4:{s:10:"CarrierIds";a:1:{i:0;i:1914;}s:8:"OriginId";i:43268;s:13:"DestinationId";i:81959;s:13:"DepartureDate";s:19:"2016-03-08T00:00:00";}s:13:"QuoteDateTime";s:19:"2016-03-03T12:50:00";}}s:6:"Places";a:2:{i:0;O:8:"stdClass":7:{s:7:"PlaceId";i:43268;s:8:"IataCode";s:3:"BUD";s:4:"Name";s:8:"Budapest";s:4:"Type";s:7:"Station";s:8:"CityName";s:8:"Budapest";s:6:"CityId";s:4:"BUDA";s:11:"CountryName";s:7:"Hungary";}i:1;O:8:"stdClass":7:{s:7:"PlaceId";i:81959;s:8:"IataCode";s:3:"SKG";s:4:"Name";s:12:"Thessaloniki";s:4:"Type";s:7:"Station";s:8:"CityName";s:12:"Thessaloniki";s:6:"CityId";s:4:"THES";s:11:"CountryName";s:6:"Greece";}}s:8:"Carriers";a:2:{i:0;O:8:"stdClass":2:{s:9:"CarrierId";i:1368;s:4:"Name";s:9:"Lufthansa";}i:1;O:8:"stdClass":2:{s:9:"CarrierId";i:1914;s:4:"Name";s:8:"Wizz Air";}}s:10:"Currencies";a:1:{i:0;O:8:"stdClass":8:{s:4:"Code";s:3:"EUR";s:6:"Symbol";s:3:"€";s:18:"ThousandsSeparator";s:2:" ";s:16:"DecimalSeparator";s:1:",";s:12:"SymbolOnLeft";b:0;s:27:"SpaceBetweenAmountAndSymbol";b:1;s:19:"RoundingCoefficient";i:0;s:13:"DecimalDigits";i:2;}}}}');
        $this->quotes   =   FlightQuotes::create($response_obj);
    }

    public function testBasicDataAccess()
    {
        $this->assertNotEmpty($this->quotes->getOutboundDates());
        $this->assertNotEmpty($this->quotes->getInboundDates());
        $this->assertNotEmpty($this->quotes->getQuotes());
        $this->assertNotEmpty($this->quotes->getPlaces());
        $this->assertNotEmpty($this->quotes->getCarriers());
        $this->assertNotEmpty($this->quotes->getCurrencies());
    }

    public function testGetCarriersArray()
    {
        $byKeys =   $this->quotes->getCarriersArray();
        $this->assertCount(2, $byKeys);
        $this->assertContains('Wizz Air', $byKeys);
        $this->assertContains('Lufthansa', $byKeys);
    }

    public function testGetPlacesArray()
    {
        $byKeys =   $this->quotes->getPlacesArray();
        $this->assertCount(2, $byKeys);
        $this->assertEquals('BUD', $byKeys[43268]->IataCode);
        $this->assertEquals('SKG', $byKeys[81959]->IataCode);
    }

    public function testGetCarrierById()
    {
        $this->assertNotEmpty($this->quotes->getCarrierByID(1368)); // lufthansa
        $this->assertNotEmpty($this->quotes->getCarrierByID(1914)); // wizz air

        $this->assertEmpty($this->quotes->getCarrierByID(9999999));
    }

    public function testForeachCount()
    {
        $count = 0;
        foreach($this->quotes as $quote)
        {
            $count++;
        }

        $this->assertSame(3, $count);
    }

    public function testCount()
    {
        $this->assertSame(3, count($this->quotes));
    }

    public function testGetSingle()
    {
        $this->assertNotEmpty($this->quotes->getQuote(0));
        $this->assertNotEmpty($this->quotes->getQuote(1));
        $this->assertNotEmpty($this->quotes->getQuote(2));

        $this->assertEmpty($this->quotes->getQuote(3));

        $this->assertNotEmpty($this->quotes->getQuote(0)->getQuoteData());
        $this->assertNotEmpty($this->quotes->getQuote(1)->getQuoteData());
        $this->assertNotEmpty($this->quotes->getQuote(2)->getQuoteData());

        $this->assertSame($this->quotes, $this->quotes->getQuote(0));

        $this->assertSame(
            $this->quotes->getQuote(0),
            $this->quotes->getQuote(1)
        );

        $this->assertNotSame(
            $this->quotes->getQuote(0)->getQuoteData(),
            $this->quotes->getQuote(1)->getQuoteData()
        );

        $this->assertNotSame(
            $this->quotes->getQuote(1)->getQuoteData(),
            $this->quotes->getQuote(2)->getQuoteData()
        );

    }

    public function testGetQuoteID()
    {
        $values = [1, 2, 3];
        foreach($this->quotes as $index => $quote)
        {
            $this->assertSame($values[$index], $quote->getQuoteId());
        }
    }

    public function testGetMinPrice()
    {
        $values = [110, 426, 60];
        foreach($this->quotes as $index => $quote)
        {
            $this->assertEquals($values[$index], $quote->getMinPrice());
        }
    }

    public function testIsDirect()
    {
        $values = [true, false, true];
        foreach($this->quotes as $index => $quote)
        {
            $this->assertSame($values[$index], $quote->getIsDirect());
        }
    }

    public function testGetDateTime()
    {
        $values = ['2016-03-03T12:50:00', '2016-03-03T12:39:00', '2016-03-03T12:50:00'];
        foreach($this->quotes as $index => $quote)
        {
            $this->assertSame($values[$index], $quote->getQuoteDateTime());
        }
    }

    public function testHasOutbound()
    {
        $values = [true, true, false];
        foreach($this->quotes as $index => $quote)
        {
            $this->assertSame($values[$index], $quote->getHasOutbound());
        }
    }

    public function testHasInbound()
    {
        $values = [false, true, true];
        foreach($this->quotes as $index => $quote)
        {
            $this->assertSame($values[$index], $quote->getHasInbound());
        }
    }

    public function testOutboundDeparture()
    {
        $values = ['2016-03-05T00:00:00', '2016-03-05T00:00:00', NULL];
        foreach($this->quotes as $index => $quote)
        {
            $this->assertSame($values[$index], $quote->getOutboundDepartureDate());
        }
    }

    public function testInboundDeparture()
    {
        $values = [NULL, '2016-03-08T00:00:00', '2016-03-08T00:00:00'];
        foreach($this->quotes as $index => $quote)
        {
            $this->assertSame($values[$index], $quote->getInboundDepartureDate());
        }
    }

    public function testGetOutboundCarrierName()
    {
        $values = ['Wizz Air', 'Lufthansa', NULL];
        foreach($this->quotes as $index => $quote)
        {
            $this->assertSame($values[$index], $quote->getOutboundCarrierName());
        }
    }
    
    public function testGetInboundCarrierName()
    {
        $values = [NULL, 'Lufthansa', 'Wizz Air'];
        foreach($this->quotes as $index => $quote)
        {
            $this->assertSame($values[$index], $quote->getInboundCarrierName());
        }
    }

}
