<?php

/*
 * This file is part of the sfWebBrowserPlugin package.
 * (c) 2004-2006 Francois Zaninotto <francois.zaninotto@symfony-project.com>
 * (c) 2004-2006 Fabien Potencier <fabien.potencier@symfony-project.com> for the click-related functions
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfWebBrowser provides a basic HTTP client.
 *
 * @package    sfWebBrowserPlugin
 * @author     Francois Zaninotto <francois.zaninotto@symfony-project.com>
 * @author     Ben Meynell <bmeynell@colorado.edu>
 * @version    0.9
 */

class sfCurlAdapter
{

  protected
    $options = array(),
    $curl    = null,
    $headers = array();

  public function __construct($options = array())
  {
    if (!extension_loaded('curl'))
    {
      throw new Exception('Curl extension not loaded');
    }

    $this->options = $options;
    $this->curl = curl_init();

    // cookies
    if (isset($this->options['cookies']))
    {
      $cookie_file = isset($this->options['cookies_file']) ? $this->options['cookies_file'] : sfConfig::get('sf_data_dir').'/sfWebBrowserPlugin/sfCurlAdapter/cookies.txt';
      $cookie_dir = isset($this->options['cookies_dir']) ? $this->options['cookies_dir'] : sfConfig::get('sf_data_dir').'/sfWebBrowserPlugin/sfCurlAdapter';

      if (!is_dir($cookie_dir))
      {
        if (!mkdir($cookie_dir, 0777, true))
        {
          throw new Exception(sprintf('Could not create directory "%s"', $cookie_dir));
        }
      }

      curl_setopt($this->curl, CURLOPT_COOKIESESSION, false);
      curl_setopt($this->curl, CURLOPT_COOKIEJAR, $cookie_file);
      curl_setopt($this->curl, CURLOPT_COOKIEFILE, $cookie_file);
    }

    // default settings
    curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($this->curl, CURLOPT_AUTOREFERER, true);
    curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, false);
    curl_setopt($this->curl, CURLOPT_FRESH_CONNECT, true);
    
    if(isset($this->options['follow_location']))
    {
      curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, (bool) $this->options['follow_location']);
    }
    
    // activate ssl certificate verification?
    
    if (isset($this->options['ssl_verify_host']))
    {
      curl_setopt($this->curl, CURLOPT_SSL_VERIFYHOST, (bool) $this->options['ssl_verify_host']);
    }
    if (isset($this->options['ssl_verify']))
    {
      curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, (bool) $this->options['ssl_verify']);
    }

    if (isset($this->options['verbose']))
    {
      curl_setopt($this->curl, CURLOPT_NOPROGRESS, false);
      curl_setopt($this->curl, CURLOPT_VERBOSE, true);
    }

    if(isset($this->options['verbose_log']))
    {
      $log_file = sfConfig::get('sf_log_dir').'/sfCurlAdapter_verbose.log';
      curl_setopt($this->curl, CURLOPT_VERBOSE, true);
      $this->fh = fopen($log_file, 'a+b');
      curl_setopt($this->curl, CURLOPT_STDERR, $this->fh);
    }

    // response header storage - uses callback function
    curl_setopt($this->curl, CURLOPT_HEADERFUNCTION, array($this, 'read_header'));
  }

  /**
   * Submits a request
   *
   * @param string  The request uri
   * @param string  The request method
   * @param array   The request parameters (associative array)
   * @param array   The request headers (associative array)
   *
   * @return sfWebBrowser The current browser object
   */
  public function call($browser, $uri, $method = 'GET', $parameters = array(), $headers = array())
  {
    // uri
    curl_setopt($this->curl, CURLOPT_URL, $uri);

    // request headers
    $m_headers = array_merge($browser->getDefaultRequestHeaders(), $browser->initializeRequestHeaders($headers));
    $request_headers = explode("\r\n", $browser->prepareHeaders($m_headers));
    curl_setopt($this->curl, CURLOPT_HTTPHEADER, $request_headers);
   
    // encoding support
    if(isset($headers['Accept-Encoding']))
    {
      curl_setopt($this->curl, CURLOPT_ENCODING, $headers['Accept-Encoding']);
    }
    
    // timeout support
    if(isset($this->options['Timeout']))
    {
      curl_setopt($this->curl, CURLOPT_TIMEOUT, $this->options['Timeout']);
    }
    
    if (!empty($parameters))
    {
      if (!is_array($parameters))
      {
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $parameters);
      }
      else
      {
        // multipart posts (file upload support)
        $has_files = false;
        foreach ($parameters as $name => $value)
        {
          if (is_array($value)) {
            continue;
          }
          if (is_file($value))
          {
            $has_files = true;
            $parameters[$name] = '@'.realpath($value);
          }
        }
        if($has_files)
        {
          curl_setopt($this->curl, CURLOPT_POSTFIELDS, $parameters);
        }
        else
        {
          curl_setopt($this->curl, CURLOPT_POSTFIELDS, http_build_query($parameters, '', '&'));
        }
      }
    }

    // handle any request method
    curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, $method);

    $response = curl_exec($this->curl);

    if (curl_errno($this->curl))
    {
      throw new Exception(curl_error($this->curl));
    }

    $requestInfo = curl_getinfo($this->curl);

    $browser->setResponseCode($requestInfo['http_code']);
    $browser->setResponseHeaders($this->headers);
    $browser->setResponseText($response);

    // clear response headers
    $this->headers = array();

    return $browser;
  }

  public function __destroy()
  {
    curl_close($this->curl);
  }

  protected function read_header($curl, $headers)
  {
    $this->headers[] = $headers;
    return strlen($headers);
  }

}
