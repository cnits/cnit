<?php

/*
This sample demonstrates creating, modifying and manipulating Account and Customer objects through the Parature API.  This sample uses the cURL library to initiated HTTP requests.

Code outline:
  1. List all the accounts
  2. Create a new account
  3. Create a new customer with that account
  4. Update that customer
  5. List all customers and extract all their details
*/

// You must supply your own values for the next five variables in order to run the demo locally.
// See http://supportcenteronline.com/UserGuide/API/Parature_API_Specification.htm#Obtaining_Account_and_Department
// and  http://supportcenteronline.com/UserGuide/API/Parature_API_Specification.htm#Live_Production
$hostname = 'my-servicedesk.parature.com';
$clientID = '6057';
$deptID   = '2311';
$token    = 'LUwTqpkmSr04vtF0G/hWfxQD27/tHalmI7ZQBvyX/S365L1HBy/20G7HIhI0DclqQU48PJ3RuifjsuejF5S4oQ==';

$slaID    = '4911'; // The ID value for a prexisting Service Level Agreement object

// Customer status constants
$statusPending = 1;
$statusRegistered = 2;

// From the Parature API: Throttling: If a client exceeds 120 API requests per minute for two minutes, the client 
// will be throttled down....
//
// See API Throttling: http://supportcenteronline.com/UserGuide/API/Parature_API_Specification.htm#API_Throttling
$requestsPerMinute = 120;

// This is the portion of the URL path that will be used by all the subsequent HTTP requests
$baseURL = 'https://'.$hostname.'/api/v1/'.$clientID.'/'.$deptID.'/';



// Helper method that formulates a request URL, creates an HTTP request, and writes the xml body to the request if 
// necessary for the HTTP method desired. It returns the body of the HTTP response as a SimpleXML data structure.
//    baseURL: The portion of the URL path that will be used by all the subsequent HTTP requests.
//    path:    The path specific to the request, containing any parameters need for the request. This must have either 
//             an ampersand (&) if you have one or more paramters, or a question mark (?) if you have no parameters, at the end of the string in order 
//             for the token parameter to be correctly appended.
//             For Example: "Account?" or "Ticket?_output_=rss&Date_Created_min_=2007-08-05&"
//    token:   The authentication token, see http://supportcenteronline.com/UserGuide/API/Parature_API_Specification.htm#Token
//    method:  The HTTP Method: GET, POST, PUT, DELETE, etc.
//    xmlBody: The xml content to send for a PUT or POST request or NULL for a GET or DELETE request
function makeHttpRequest($baseURL, $path, $token, $method, $xmlBody) {

  // Construct the request URL by concatenating the base portion with the request params
  // and the authentication token. 
  // The full request URL could look like:
  // https://demo.parature.com/api/v1/6924/6939/Account?_token_=LUwTq...
  // or:
  // https://my_sandbox.parature.com/api/v1/33/420/Ticket?_output_=rss&Date_Created_min_=2007-08-05&_token_=ABC123XYZ...
  $url = $baseURL.$path.'_token_='.$token;
  
  // Create a HTTP request object via cURL
  $request = curl_init($url);

  // We now set several parameters on the HTTP request.
  curl_setopt($request, CURLOPT_RETURNTRANSFER, TRUE);  // Requests that the response body be sent back (otherwise cURL will just return TRUE/FALSE)
  curl_setopt($request, CURLOPT_SSL_VERIFYHOST, FALSE); // Sets up the request to use SSL, but not to verify either party.
  curl_setopt($request, CURLOPT_SSL_VERIFYPEER, FALSE); // -
  curl_setopt($request, CURLOPT_TIMEOUT, 10);           // Request timeout.
  
  // Set the HTTP method type
  curl_setopt($request, CURLOPT_CUSTOMREQUEST, $method);
  
  // If the xmlBody isn't null or empty, add it to the body of the request
  if(!$xmlBody == null || $xmlBody != '') {
    // Set the content length in the header
    curl_setopt($request, CURLOPT_HTTPHEADER, array('Content-Length: '.strlen($xmlBody)));
    curl_setopt($request, CURLOPT_POSTFIELDS, $xmlBody);
  }
  
  // Make the request, capturing the response and printing an error (if any occurred).
  $response = curl_exec($request);
  
  // curl_exec will return false on failure
  if(!$response) {
    print curl_error($request);
  }
  curl_close($request);

  // Convert the raw XML response to a SimpleXML datastructure.
  $xml = simplexml_load_string($response);

  return $xml;
}


// Helper function that self throttles HTTP requests to prevent your application from
// triggering Parature's throttling logic.
function throttleRequest ($lastRequest, $rpm) {

  // Calculates the spacing (in microseconds) required between each request
  $requestSpacing = (1000*1000)/($rpm/60); 
  
  // If the time difference between the last request is less than the required spacing,
  // sleep for the remaining difference.
  $diff = microtime() - $lastRequest;
  if ($diff < $requestSpacing) {
    $sleepTime = $requestSpacing - $diff;
    usleep($sleepTime);
  }
}


// List all the accounts. (Included here for illustration purposes, you might do processing on the list in your application.)
$xml = makeHttpRequest($baseURL, 'Account?', $token, 'GET', NULL);

// Using SimpleXML create a XML document for a new account.
$root = new SimpleXMLElement ('<Account></Account>');

// Append the current timestamp to the account name in order to make it unique (for convenience).
$accountName = 'Demo Account '.date('y m d h: s: m');
$root->addChild('Account_Name', $accountName); 
$rootSla = $root->addChild('Sla');
$rootSla->addChild('Sla')->addAttribute('id', $slaID);

// Make a POST request to create a new account.
print "Creating new account: $accountName \n";
$xml = makeHttpRequest($baseURL, 'Account?', $token, 'POST', $root->asXML());


// Extract the new account id from the returned xml acknowledgment.
$newAccountID = $xml['id'];


// Use SimpleXML to create a XML document for a new customer.

$root = new SimpleXMLElement ('<Customer></Customer>');
$account = $root->addChild('Account');

// Bind the customer to the previously created account using the account id.
$account->addChild('Account')->addAttribute('id', $newAccountID);

// The email must be unique. We use a random number here for convenience.
$email   = 'test'.rand(1, 1000).'@example.com';
$account = $root->addChild('Email',             $email);
$account = $root->addChild('First_Name',        'James');
$account = $root->addChild('Last_Name',         'Mason');
$account = $root->addChild('Password',          'mr._mason2U!');
$account = $root->addChild('Password_Confirm',  'mr._mason2U!');
$rootSla = $root->addChild('Sla');
$rootSla->addChild('Sla')->addAttribute('id', $slaID);
$rootStatus = $root->addChild('Status');

// Create customer with REGISTERED status
$rootStatus->addChild('Status')->addAttribute('id', $statusRegistered);

// Make a POST request to create a new customer.
$xml = makeHttpRequest($baseURL, 'Customer?', $token, 'POST', $root->asXML());
print "Creating new customer: James Mason (email: $email).\n";

// Note, you can also update a customer using a PUT.
$custID = $xml['id'];
$root->First_Name = 'Joshua';
print "Updating customer.\n";
$xml = makeHttpRequest($baseURL, "Customer/$custID?", $token, 'PUT', $root->asXML());


// Do error checking. If the returned xml string contains 'Error' then drill down. You can
// extract the error code and analyze it. (For example, 401 means you are unauthorized, 500 
// means internal server error, etc.)
if (strpos($xml->asXML(), 'Error') != FALSE) {
  $code = $xml['code'];
  if ($code == 400) {
    print "Either your request URL or your request body is malformed. Remember that two customers cannot have the same email address.\n";
    print $xml->asXML();
  }
}

// List all the customers.
print "List of all customers:\n";
$xml = makeHttpRequest($baseURL, 'Customer?', $token, 'GET', NULL);

// Iterate through the list and retrieve each individual customer by ID. Since there are potentially
// many customers we use the self throttling logic to prevent too many requests from hitting the 
// service desk and triggering the throttling mechanism.

$lastRequestTime = 0;
foreach ($xml->Customer as $customer) {
  $customerID  = $customer['id'];
  $lastRequestTime = microtime();
  $customerXML = makeHttpRequest($baseURL, "Customer/$customerID?", $token, 'GET', NULL);
  throttleRequest ($lastRequestTime, $requestsPerMinute);
  print "\n    ".$customerXML->asXML()."\n";
}
?>