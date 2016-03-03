<?php
/**
 * Project: skyscanner-php-sdk
 *
 * @author Amado Martinez <amado@projectivemotion.com>
 */

namespace Skyscanner\Response;


class FlightQuotes implements \Iterator
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
        
        return $this->getQuoteData()->$key;
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
    
    public function getMinPrice()
    {
        return $this->getQuoteData('MinPrice');
    }

    public function getQuoteId()
    {
        return $this->getQuoteData('QuoteId');
    }

    public function getQuoteDateTime()
    {
        return $this->getQuoteData('QuoteDateTime');
    }

    public function getOutboundLegData($key = '')
    {
        if(empty($key))
            return $this->getQuoteData('OutboundLeg');

        return $this->getOutboundLegData()->$key;
    }

    public function getOutboundCarrierName($carrier_index = 0)
    {
        $carrier_id    =   $this->getOutboundLegData('CarrierIds')[$carrier_index];
        return $this->getCarrierByID($carrier_id)->Name;
    }

    public function getOutboundDepartureDate()
    {
        return $this->getOutboundLegData('DepartureDate');
    }
    
    public function isDirect()
    {
        return $this->getQuoteData('Direct') == true; 
    }

    //
    // Instantiation Functions
    //

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

    //
    // Iterator Functions
    //

    public function current()
    {
        $this->position = $this->position%count($this->_Quotes);
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