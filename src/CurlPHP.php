<?php
/**
 * cURL class
 * Send easily cURL requests with this class
 *
 * @package CurlPHP
 */
namespace CurlPHP;
 
class CurlPHP {

	/**
	 * cURL resource
	 * @var resource
	 */
	protected $resource;
	
	/**
	 * URL request
	 * @var String
	 */
	protected $url;
	
	/**
	 * Cookies path
	 * @var String
	 */
	private $cookie;
	
	/**
	 * Cookies sent in the header
	 * @var String
	 */
	protected $cookieHeader = '';
	
	/**
	 * User agent
	 * @var String
	 */
	private $userAgent = 'Mozilla/5.0 (X11; U; Linux i686; fr; rv:1.9.1.1) Gecko/20090715 Firefox/3.5.1';
	
	/**
	 * Timeout before stopping
	 * @var int
	 */
	protected $timeout = 20;
	
	/**
	 * Max redirections before stopping
	 * @var int
	 */
	protected $maxRedir = 5;
	
	/**
	 * Follow location
	 * @var boolean
	 */
	protected $follow = true;
	
	/**
	 * Include header or not in the return
	 * @var boolean
	 */
	protected $incHeader = false;
	
	/**
	 * Include body or not in the return
	 * @var boolean
	 */
	protected $nobody = false;
	
	/**
	 * Header fields
	 * @var String
	 */
	protected $headerFields = array();
	
	/**
	 * Passes if needed
	 * @var String
	 */
	protected $pass = '';
	
	/**
	 * More options
	 * @var array
	 */
	protected $options = array();
	
	/**
	 * POST params
	 * @var mixed
	 */
	protected $post = array();
	
	/**
	 * PUT params
	 * @var array
	 */
	protected $put = array();
	
	/**
	 * Custom request
	 * @var String
	 */
	protected $req = '';
	
	/**
	 * HTTP status when sent
	 * @var array
	 */
	protected $status = array();
	
	/**
	 * Return of the request
	 * @var String
	 */
	protected $return = '';
	
	/**
	 * Constructor
	 * @param String $url
	 * @param String $request
	 * @param int $timeout
	 * @param int $maxRedir
	 * @param bool $follow
	 * @param bool $incHeader
	 * @param bool $nobody
	 * @throws CurlException
	 */
	public function __construct(String $url, $request = 'GET', $timeout = 20, $maxRedir = 5, $follow = true, $incHeader = false, $nobody = false) {
		if(!function_exists('curl_init'))
			throw new CurlException('cURL need to be installed !');
			
		$this->resource = @curl_init();
	
		$this->url = $url;
		$this->req = $request;
		$this->timeout = $timeout;
		$this->maxRedir = $maxRedir;
		$this->follow = $follow;
		$this->incHeader = $incHeader;
		$this->nobody = $nobody;
		$this->cookie = __DIR__ . '/cookies.txt';
	}
	
	/**
	 * Destructor
	 */
	public function __destruct() {
		@curl_close($this->resource);
	}
	
	/**
	 * Add more options to cURL like
	 * 		- CURLOPT_UPLOAD
	 * 		- CURLOPT_PORT
	 *		- CURLOPT_SSL_VERIFYHOST
	 *		- CURLOPT_SSL_VERIFYPEER
	 * @see http://php.net/manual/en/function.curl-setopt.php for parameters and options of cURL
	 * @param array
	 * @return CurlPHP
	 */
	public function setopt_array(Array $options) : CurlPHP {
		foreach($options as $key => $val) {
			$this->options[$key] = $val;
		}
		return $this;
	}
	
	/**
	 * Set the URL of the request
	 * @param String
	 * @return CurlPHP
	 */
	public function setUrl(String $url) : CurlPHP {
		$this->url = $url;
		return $this;
	}
	
	/**
	 * Set the cookies, need a string of cookies and parameters
	 * Like : "fruit=apple; colour=red"
	 * @param String
	 * @return CurlPHP
	 */
	public function setCookie(String $cookie) : CurlPHP {
		$this->cookieHeader = $cookie;
		return $this;
	}
	
	/**
	 * Set the user agent
	 * @param String
	 * @return CurlPHP
	 */
	public function setUserAgent(String $userag) : CurlPHP {
		$this->userAgent = $userag;
		return $this;
	}
	
	/**
	 * Set the timeout in cURL before stopping
	 * @param int
	 * @return CurlPHP
	 */
	public function setTimeout(int $timeout) : CurlPHP {
		$this->timeout = $timeout;
		return $this;
	}
	
	/**
	 * Set the number of max redirections
	 * @param int
	 * @return CurlPHP
	 */
	public function setMaxRedirects(int $maxred) : CurlPHP {
		$this->maxRedir = $maxred;
		return $this;
	}
	
	/**
	 * Set if cURL must follow locations
	 * @param bool
	 * @return CurlPHP
	 */
	public function setFollow(bool $follow) : CurlPHP {
		$this->follow = $follow;
		return $this;
	}
	
	/**
	 * Set if header in return
	 * @param bool
	 * @return CurlPHP
	 */
	public function setIncludeHeader(bool $incHeader) : CurlPHP {
		$this->incHeader = $incHeader;
		return $this;
	}
	
	/**
	 * Set if body in return
	 * @param bolean
	 * @return CurlPHP
	 */
	public function setNoBody(bool $body) : CurlPHP {
		$this->nobody = $body;
		return $this;
	}
	
	/**
	 * Set the HTTP header fields
	 * @param array
	 * @return CurlPHP
	 */
	public function setHeaderFields(array $array) : CurlPHP {
		$this->headerFields = $array;
		return $this;
	}
	
	/**
	 * Set the pass formatted as "[username]:[password]"
	 * @param String
	 * @return CurlPHP
	 */
	public function setPass(String $pass) : CurlPHP {
		$this->pass = $pass;
		return $this;
	}
	
	/**
	 * Set the requet
	 * @param String
	 * @return CurlPHP
	 */
	public function setRequest(String $req) : CurlPHP {
		$this->req = $req;
		return $this;
	}
	
	/**
	 * Set POST parameters
	 * @param array|String
	 * @return CurlPHP
	 */
	public function setPost($postParam) : CurlPHP {
		$this->post = $postParam;
		return $this;
	}
	
	/**
	 * Set PUT parameters
	 * @param resource $file
	 * @param int $size
	 * @return CurlPHP
	 */
	public function setPut($file, int $size) : CurlPHP {
		$this->put['0'] = $file;
		$this->put['1'] = $size;
		return $this;
	}
	
	/**
	 * GETTERS
	 */

	/**
	 * Return the cookies in the file
	 * @param void
	 * @return String
	 */
	public function getCookieFile() : String {
		return @file_get_contents($this->cookie);
	}
	
	/**
	 * Return the URL
	 * @param void
	 * @return String
	 */
	public function getUrl() : String {
		return $this->url;
	}
	
	/**
	 * Return the cookies to send in the header
	 * @param void
	 * @return String
	 */
	public function getCookieHeader() : String {
		return $this->cookieHeader;
	}
	
	/**
	 * Return the user agent used
	 * @param void
	 * @return String
	 */
	public function getUserAgent() : String {
		return $this->userAgent;
	}
	
	/**
	 * Return timeout
	 * @param void
	 * @return int
	 */
	public function getTimeout() : int {
		return $this->timeout;
	}
	
	/**
	 * Return the number of max redirections declared
	 * @param void
	 * @return int
	 */
	public function getMaxRedir() : int {
		return $this->maxRedir;
	}
	
	/**
	 * Return Follow param
	 * @param void
	 * @return bool
	 */
	public function getFollow() : bool {
		return $this->follow;
	}
	
	/**
	 * Return includeHeader param
	 * @param void
	 * @return bool
	 */
	public function getIncludeHeader() : bool {
		return $this->incHeader;
	}
	
	/**
	 * Return noBody param
	 * @param void
	 * @return bool
	 */
	public function getNoBody() : bool {
		return $this->nobody;
	}
	
	/**
	 * Return pass
	 * @param void
	 * @return String
	 */
	public function getPass() : String {
		return $this->pass;
	}
	
	/**
	 * Return an array of options, and request parameters (POST/PUT)
	 * @param void
	 * @return array
	 */
	public function getOptions() : array {
		$return   = array();
		$return[] = $this->options;
		$return[] = $this->post;
		$return[] = $this->put;
		return $return;
	}
	
	/**
	 * Return the request that Curl will use
	 * @param void
	 * @return String
	 */
	public function getRequest() : String {
		return $this->req;
	}
	
	/**
	 * Return the status of the cURL request
	 * @param array
	 * @return String
	 */
	public function getStatus() : array {
		return $this->status;
	}
	
	/**
	 * Get the return of the Curl request
	 * @param void
	 * @return String
	 */
	public function __toString() : String {
		return $this->return;
	}
	
	/**
	 * Send request, run cURL
	 * @param void
	 * @return CurlPHP
	 * @throws CurlException
	 */
	public function run() : CurlPHP {
			
		$this->options[CURLOPT_URL] = $this->url;
		$this->options[CURLOPT_USERAGENT] = $this->userAgent;
		$this->options[CURLOPT_COOKIEJAR] = $this->cookie;
		$this->options[CURLOPT_COOKIEFILE] = $this->cookie;
		$this->options[CURLOPT_TIMEOUT] = $this->timeout;
		$this->options[CURLOPT_MAXREDIRS] = $this->maxRedir;
		$this->options[CURLOPT_FOLLOWLOCATION] = $this->follow;
		$this->options[CURLOPT_HEADER] = $this->incHeader;
		$this->options[CURLOPT_NOBODY] = $this->nobody;
		$this->options[CURLOPT_CUSTOMREQUEST] = $this->req;
		$this->options[CURLOPT_RETURNTRANSFER] = true;
		if(!empty($this->headerFields)) {
			$this->options[CURLOPT_HTTPHEADER] = $this->headerFields;
		}
		if($this->cookieHeader != ''){
			$this->options[CURLOPT_COOKIE] = $this->cookieHeader;
		}
		if(isset($this->pass)) {
			$this->options[CURLOPT_USERPWD] = $this->pass;
		}
		if(!empty($this->post))
			$this->options[CURLOPT_POSTFIELDS] = $this->post;
		if($this->options[CURLOPT_CUSTOMREQUEST] == 'POST')
			$this->options[CURLOPT_POST] = true;
		
		if(!empty($this->put)) {
			$this->options[CURLOPT_PUT] = true;
			$this->options[CURLOPT_INFILE] = $this->put['0'];
			$this->options[CURLOPT_INFILESIZE] = $this->put['1'];
		}
		@curl_setopt_array($this->resource, $this->options);
		$this->return = @curl_exec($this->resource);
		if($this->return === false) {
			throw new CurlException('Error #' . @curl_errno($curl) . ' : ' . @curl_error($curl));
			return $this;
		}
		$this->status = @curl_getinfo($curl);
		
		return $this;
	}
}
