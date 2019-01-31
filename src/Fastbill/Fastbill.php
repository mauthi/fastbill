<?php
/* ******************************************** */
/*   Copyright: ZWEISCHNEIDER DIGITALSCHMIEDE   */
/*         http://www.zweischneider.de          */
/* ******************************************** */

namespace Fastbill;

use Fastbill\Api\Connection;
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
    private $_connection;

    public function __construct($_email, $_apiKey, $_apiUrl = FASTBILL_PLUS)
    {
        $this->_connection = new Connection(array( 'email' => $_email, 'apiKey' => $_apiKey, 'apiUrl' => $_apiUrl));

        $this->articles = new Articles($this->_connection);
        $this->expenses = new Expenses($this->_connection);
        $this->invoices = new Invoices($this->_connection);
        $this->recurringInvoices = new RecurringInvoices($this->_connection);
        $this->projects = new Projects($this->_connection);
        $this->customers = new Customers($this->_connection);
    }

    /**
     * @return Articles
     */
    public function getArticles()
    {
        return $this->articles;
    }

    /**
     * @return Expenses
     */
    public function getExpenses()
    {
        return $this->expenses;
    }

    /**
     * @return Invoices
     */
    public function getInvoices()
    {
        return $this->invoices;
    }

    /**
     * @return getRecurringInvoices
     */
    public function getRecurringInvoices()
    {
        return $this->recurringInvoices;
    }

    /**
     * @return Customers
     */
    public function getCustomers()
    {
        return $this->customers;
    }

    /**
     * @return Customers
     */
    public function getProjects()
    {
        return $this->projects;
    }
    
}
