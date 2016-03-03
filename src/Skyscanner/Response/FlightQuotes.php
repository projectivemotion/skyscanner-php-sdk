<?php
/**
 * Project: skyscanner-php-sdk
 *
 * @author Amado Martinez <amado@projectivemotion.com>
 */

namespace Skyscanner\Response;


class FlightQuotes implements \Iterator, \Countable
{
    protected $position =   0;

    protected $_Dates;
    protected $_Quotes;
    protected $_Places;
    protected $_Carriers;
    protected $_Currencies;

    public function getQuoteData($key = '')
    {
        if(empty($key))
            return $this->_Quotes[$this->position];
        
        return isset($this->getQuoteData()->$key) ? $this->getQuoteData()->$key : NULL;
    }

    public function getCarrierByID($CarrierId)
    {
        foreach($this->_Carriers as $carrier)
        {
            if($carrier->CarrierId == $CarrierId)
                return $carrier;
        }

        return null;
    }

    //
    // Basic Methods for First Level Objects
    //

    public function getMinPrice()
    {
        return (float)$this->getQuoteData('MinPrice');
    }

    public function getQuoteId()
    {
        return $this->getQuoteData('QuoteId');
    }

    public function getQuoteDateTime()
    {
        return $this->getQuoteData('QuoteDateTime');
    }
    
    public function getIsDirect()
    {
        return $this->getQuoteData('Direct') == true; 
    }

    public function getHasOutbound()
    {
        return isset($this->getQuoteData()->OutboundLeg);
    }

    public function getHasInbound()
    {
        return isset($this->getQuoteData()->InboundLeg);
    }

    //
    // Second level property methods.
    // These methods require that you first check if getHasInbound() or getHasOutbound() is true.
    //

    public function getOutboundDepartureDate()
    {
        return $this->getOutboundLegData('DepartureDate');
    }

    public function getOutboundCarrierName($carrier_index = 0)
    {
        // If there is no outbound data, carrier_id will be null
        $carrier_id    =   $this->getOutboundLegData('CarrierIds')[$carrier_index];
        return $carrier_id ? $this->getCarrierByID($carrier_id)->Name : NULL;
    }

    public function getInboundDepartureDate()
    {
        return $this->getInboundLegData('DepartureDate');
    }

    public function getInboundCarrierName($carrier_index = 0)
    {
        // If there is no outbound data, carrier_id will be null
        $carrier_id    =   $this->getInboundLegData('CarrierIds')[$carrier_index];
        return $carrier_id ? $this->getCarrierByID($carrier_id)->Name : NULL;
    }

    public function getOutboundLegData($key = '')
    {
        if(empty($key))
            return $this->getQuoteData('OutboundLeg');

        $outboundleg    =   $this->getOutboundLegData();
        if(!$outboundleg)
            return NULL;

        return isset($outboundleg->$key) ? $outboundleg->$key : NULL;
    }

    public function getInboundLegData($key = '')
    {
        if(empty($key))
            return $this->getQuoteData('InboundLeg');

        $inboundleg    =   $this->getInboundLegData();
        if(!$inboundleg)
            return NULL;

        return isset($inboundleg->$key) ? $inboundleg->$key : NULL;
    }

    //
    // Instantiation Functions
    //

    /**
     * 1. $quotes = FlightQuotes::create($response)
     * 2. foreach($quotes as $quote){
     * 3.   $quote->getMinPrice();
     * 4.   $quote->getHasOutbound();
     * 5.   $quote->getHasInbound();
     * 6. }
     * 
     *
     * @param $response_obj
     * @return static[]
     */
    public static function create($response_obj)
    {
        return new static($response_obj);
    }

    protected function __construct($response_obj)
    {
        $this->_Dates   =   $response_obj->parsed->Dates;
        $this->_Quotes  =   $response_obj->parsed->Quotes;
        $this->_Places  =   $response_obj->parsed->Places;
        $this->_Carriers    =   $response_obj->parsed->Carriers;
        $this->_Currencies  =   $response_obj->parsed->Currencies;
    }

    public function getQuote($position)
    {
        $this->position = $position;
        return $this->valid() ? $this : NULL;
    }

    public function count()
    {
        return count($this->_Quotes);
    }


    //
    // Iterator Functions
    //

    public function current()
    {
        $this->position = $this->position%$this->count();
        return $this;
    }

    public function next()
    {
        ++$this->position;
    }

    public function key()
    {
        return $this->position;
    }

    public function valid()
    {
        return isset($this->_Quotes[$this->position]);
    }

    public function rewind()
    {
        $this->position = 0;
    }
}