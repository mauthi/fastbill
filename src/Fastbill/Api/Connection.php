<?php
namespace Fastbill\Api;

use GuzzleHttp\HandlerStack;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleRetry\GuzzleRetryMiddleware;
use Exception, InvalidArgumentException;
use GuzzleHttp\Exception\ClientException;
use Fastbill\Exceptions\FastbillException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Class Connection
 *
 * @namespace    Fastbill\Api
 * @author     Mauthi <mauthi@gmail.com>
 */

class Connection
{
    /**
     * Harvest options.
     *
     * @var array
     */
    protected $_options = [
        'email' => '',
        'apiKey' => '',
        'apiUrl' => '',
        'debug' => false,
    ];

    /**
     * The HTTP client to use for the requests.
     *
     * @var GuzzleClient
     */
    private $httpClient;

    /**
     * @param array $options
     */
    function __construct($options = [])
    {
        $this->setOptions($options);
    }

    /**
     * Set the http client.
     *
     * @param GuzzleClient $client
     */
    public function setHttpClient(GuzzleClient $client)
    {
        $this->httpClient = $client;
    }

    /**
     * Get a fresh instance of the http client.
     *
     * @return \GuzzleHttp\Client
     */
    public function getHttpClient()
    {
        if (is_null($this->httpClient))
        {
            $stack = HandlerStack::create();
            $stack->push(GuzzleRetryMiddleware::factory());
            $this->httpClient = new GuzzleClient([
                'handler' => $stack,
                'base_uri' => $this->getOption("apiUrl"),
                'on_retry_callback' => function($attemptNumber, $delay, $request, $options, $response) {
    
                    if ($this->getOption("debug")) {
                        echo sprintf(
                            "Retrying request to %s.  Server responded with %s.  Will wait %s seconds.  This is attempt #%s\n",
                            $request->getUri()->getPath(),
                            $response->getStatusCode(),
                            number_format($delay, 2),
                            $attemptNumber
                        );
                    }
                },
            ]);
        }

        return clone $this->httpClient;
    }

    /**
     * Builds and performs a request.
     *
     * @param  array $body
     * @param  array $options
     * @return array
     *
     * TODO: Should allow the user to recieve XML data also if they wish to.
     */
    public function request(array $body = [], array $options = [])
    {
        $client = $this->getHttpClient();
        // Set headers to accept only json data.
        $options['headers']['Content-Type'] = 'application/json';
        $options['headers']['Accept'] = 'application/json';
        $options['auth'] = [ $this->getOption("email"), $this->getOption("apiKey") ];
        $options['json'] = $body;
        // print_r($options);
        $response = $client->request("POST", '', $options);

        switch ($response->getStatusCode()) {
            case 200:
                // everything ok
                $result = json_decode($response->getBody(),true);
                // print_r(array_keys($result));
                return $result;

            default:
                // all other cases
                throw new FastbillException("Status Code of Response = ".$response->getStatusCode()."\nBody: ".print_r($body,true));
        }

        
    }

    /**
     * Set the options.
     *
     * @param array $options
     */
    public function setOptions($options)
    {
        $this->_options = array_merge($this->_options, $options);
    }

    /**
     * Get a single option value.
     *
     * @param  ar $option
     * @throws Exception
     * @return string
     */
    public function getOption($option)
    {
        if ( !array_key_exists($option, $this->_options)) {
            throw new Exception("The requested option [$option] has not been set or is not a valid option key.");
        }

        return $this->_options[$option];
    }
}