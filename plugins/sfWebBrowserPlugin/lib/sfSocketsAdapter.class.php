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
 * @author     Benjamin Meynell <bmeynell@colorado.edu>
 * @version    0.9
 */
class sfSocketsAdapter
{
  protected
    $options             = array(),
    $adapterErrorMessage = null,
    $browser             = null;

  public function __construct($options = array())
  {
    $this->options = $options;
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
    
    $m_headers = array_merge($browser->getDefaultRequestHeaders(), $browser->initializeRequestHeaders($headers));
    $url_info  = parse_url($uri);

    if ($method == 'POST' && !array_key_exists('Content-Type', $m_headers))
    {
      $m_headers['Content-Type'] = "application/x-www-form-urlencoded";
    }

    // initialize default values
    isset($url_info['path']) ? $path = $url_info['path'] : $path = '/';
    isset($url_info['query']) ? $qstring = '?'.$url_info['query'] : $qstring = null;
    isset($url_info['port']) ? null : $url_info['port'] = 80;
    $body = is_array($parameters) ? http_build_query($parameters, '', '&') : $parameters;

    if (!$socket = @fsockopen($url_info['host'], $url_info['port'], $errno, $errstr, 15))
    {
      throw new Exception("Could not connect ($errno): $errstr");
    }
    
    // build request
    
    if(!isset($m_headers['Host']))
    {
      $m_headers["Host"] = $url_info['host'];
    }
    
    if(!isset($m_headers['Content-Length']))
    {
      $m_headers["Content-Length"] = strlen($body);
    }
    
    if(!isset($m_headers['Connection']))
    {
      $m_headers["Connection"] = "Close";
    }
    
    
    $request_headers = $browser->prepareHeaders($m_headers);
    
    $request = "$method $path$qstring HTTP/1.1\r\n";
    
    $request .= $request_headers;
    
    if ($body)
    {
      $request .= "\r\n";
      $request .= $body;
    }
    $request .= "\r\n";
    
    fwrite($socket, $request);
   
    $response = '';
    $response_body = '';
    while (!feof($socket))
    {
      $response .= fgets($socket, 1024);
    }
    fclose($socket);

    // parse response components: status line, headers and body
    $response_lines = explode("\r\n", $response);
    
    // http status line (ie "HTTP 1.1 200 OK")
    $status_line = array_shift($response_lines);

    $start_body = false;
    for($i=0; $i<count($response_lines); $i++)
    {
      // grab body
      if ($start_body == true)
      {
        // ignore chunked encoding size
        if (!preg_match('@^[0-9A-Fa-f]+\s*$@', $response_lines[$i]))
        {
          $response_body .= $response_lines[$i];
        }
      }

      // body starts after first blank line
      else if ($start_body == false && $response_lines[$i] == '')
      {
        $start_body = true;
      }

      // grab headers
      else
      {
        $response_headers[] = $response_lines[$i];
      }
    }

    // grab status code
    preg_match('@(\d{3})@', $status_line, $status_code);

    $browser->setResponseHeaders($response_headers);
    $browser->setResponseCode($status_code[1]);
    $browser->setResponseText(trim($response_body));

    return $browser;
  }

}
