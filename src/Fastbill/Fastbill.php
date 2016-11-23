<?php
/* ******************************************** */
/*   Copyright: ZWEISCHNEIDER DIGITALSCHMIEDE   */
/*         http://www.zweischneider.de          */
/* ******************************************** */

namespace Fastbill;

use Fastbill\Resources\Articles;
use Fastbill\Resources\Expenses;
use Fastbill\Resources\Invoices;
use Fastbill\Resources\Projects;
use Fastbill\Resources\Customers;
use Fastbill\Resources\RecurringInvoices;

define('FASTBILL_PLUS',         'https://my.fastbill.com/api/1.0/api.php');
define('FASTBILL_AUTOMATIC',    'https://automatic.fastbill.com/api/1.0/api.php');


class Fastbill
{
    private $email = '';
    private $apiKey = '';
    private $apiUrl = '';
    private $debug = false;
    private $convert_to_utf8 = false;

    public function __construct($_email, $_apiKey, $_apiUrl = FASTBILL_PLUS)
    {
        if($_email != '' && $_apiKey != '')
        {
            $this->email = $_email;
            $this->apiKey = $_apiKey;
            $this->apiUrl = $_apiUrl; 
        }
        else
        {
            return false;
        }
    }

    /**
     * @return Articles
     */
    public function getArticles()
    {
        return new Articles($this);
    }

    /**
     * @return Expenses
     */
    public function getExpenses()
    {
        return new Expenses($this);
    }

    /**
     * @return Invoices
     */
    public function getInvoices()
    {
        return new Invoices($this);
    }

    /**
     * @return getRecurringInvoices
     */
    public function getRecurringInvoices()
    {
        return new RecurringInvoices($this);
    }

    /**
     * @return Customers
     */
    public function getCustomers()
    {
        return new Customers($this);
    }

    /**
     * @return Customers
     */
    public function getProjects()
    {
        return new Projects($this);
    }

    public function setDebug($_bool = false)
    {
        if($_bool != '')
        {
            $this->debug = $_bool;
        }
        else
        {
            if($this->debug == true) { return array("RESPONSE" => array("ERROR" => array("Übergabeparameter 1 ist leer!"))); }
            else { return false; }
        }
    }

    public function checkAPICredentials()
    {
        $ret = $this->request(array('SERVICE' => 'customer.get'));

        if(isset($ret['RESPONSE']['ERRORS'])) { return false; }else{ return true; }
    }

    public function setConvertToUTF8($_convert_to_utf8 = false)
    {
        if($_convert_to_utf8 != '')
        {
            $this->convert_to_utf8 = $_convert_to_utf8;
        }
        else
        {
            if($this->debug == true) { return array("RESPONSE" => array("ERROR" => array("Übergabeparameter 1 ist leer!"))); }
            else { return false; }
        }
    }

    private function convertToUTF8($_array)
    {
        foreach($_array AS $key => $val)
        {
            if(is_array($val))
            {
                $val = $this->convertToUTF8($val);
            }
            else
            {
                $val = utf8_encode($val);
            }
            $_array[$key] = $val;
        }

        return $_array;
    }

    public function request($_data, $_file = NULL)
    {
        if($_data)
        {
            if($this->email != '' && $this->apiKey != '' && $this->apiUrl != '')
            {
                if($this->convert_to_utf8) { $_data = $this->convertToUTF8($_data); }

                $ch = curl_init();

                $data_string = json_encode($_data);

                if($_file != NULL) {
                    if (class_exists('CURLFile')) {
                        $_finfo = finfo_open(FILEINFO_MIME_TYPE);
                        $_curl_file = new CURLFile($_file, finfo_file($_finfo, $_file), substr(strrchr($_file, '/'), 1));
                    }
                    else {$_curl_file = "@".$_file;}
                    $bodyStr = array("document" => $_curl_file, "httpbody" => $data_string);
                }
                else { $bodyStr = array("httpbody" => $data_string); }

                curl_setopt($ch, CURLOPT_URL, $this->apiUrl);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('header' => 'Authorization: Basic ' . base64_encode($this->email.':'.$this->apiKey)));
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $bodyStr);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
                curl_setopt($ch, CURLOPT_VERBOSE, ($this->debug ? 1 : 0));

                $exec = curl_exec($ch);

                $result = json_decode($exec,true);

                curl_close($ch);

                return $result;
            }
            else
            {
                if($this->debug == true) { return array("RESPONSE" => array("ERROR" => array("Email und/oder APIKey und/oder APIURL Fehlen!"))); }
                else { return false; }
            }
        }
        else
        {
            if($this->debug == true) { return array("RESPONSE" => array("ERROR" => array("Übergabeparameter 1 ist leer!"))); }
            else { return false; }
        }
    }
}
