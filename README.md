# Laravel MediaWiki API
Laravel Package to interact with a Mediawiki Installation through the API, authenticated via OAuth.

## Installation
Publish the configuration `php artisan vendor:publish`.  
Select `StarCitizenWiki\MediaWikiApi\ApiServiceProvider`

A registered OAuth application is required on the target wiki if pages should be editable through the api.

## Facade
Accessible through `MediaWikiApi`.

### Login
```php
$manager = app('mediawikiapi.manager');

$manager->setConsumerFromCredentials(
    'ConsumerToken',
    'ConsumerSecret'
);

$manager->setTokenFromCredentials(
    'AccessToken',
    'AccessSecret'
);
```

### BaseRequest
Each request object extend the BaseRequest class.  
The base request class contains the following methods:
```php
$request->queryParams(); // Returns the query params as an array
$request->json(); // Set the request format to 'json'
$request->withTimestamp(); // Set the 'curtimestamp' param to 1
$request->request(); // Send the request, returns a MediaWikiResponse
$request->request([ 
  'timeout' => 10,
]); // Send the request with additional GuzzleClient options
```

### Query
Methods
```php
$query = MediaWikiApi::query();

// Add meta params
$query->meta('tokens'); // Param meta is now 'tokens'
$query->meta('siteinfo')->meta('...'); // Param meta is now 'tokens|siteinfo|...'

// Add prop params
$query->prop('categories'); // Param prop is now 'categories'

// Set the pages to work on
$query->titles('Title1|Title2');
// Or
$query->titles('Title1')->titles('Title2');

// Set a category limit if prop('categories') was set
$query->cllimit(-1); // return 'max'
$query->cllimit(100); // return 100

// Force authentication
$query->withAuthentication();
```

Request a CSRF token:
```php
$response = MediaWikiApi::query()->meta('tokens')->request();

// True if request was not successful
$response->hasErrors(); // boolean
$response->getErrors(); // array

// True if request has warnings
$response->hasWarnings(); // boolean
$response->getWarnings(); // array

$token = $response->getQuery()['tokens'];
```

### Parse
Methods
```php
$parse = MediaWikiApi::parse();

// Set a page to parse
$parse->page('Example');

// Add prop params
$parse->prop('text'); // Param prop is now 'text'
```

### Edit
Methods
```php
$edit = MediaWikiApi::edit();

// Set the page title to edit
// Unsets a previously set page ID!
$edit->title('Example');

// Or use a page ID
// Unsets a previously set title!
$edit->pageId(1);

// Add text to the page
// Automatically sets the md5 hash
$edit->text('Content');

// Edit an existing section
$edit->section(1);

// Add a new section
$edit->section();

// Set the section title
$edit->sectionTitle('Title');

// Set an edit summary
$edit->summary('This was an API edit');

// Set mode to create only
$edit->createOnly();

// Set edit to minor
// Unsets not minor
$edit->minor();

// Set edit to notminor
// Unsets minor
$edit->notMinor();

// Set edit as bot edit
$edit->markBotEdit();

// Set the CSRF Token
$edit->csrfToken('token');
```

### Action
A generic class for requesting arbitrary API actions

```php
// Creates an action object with ?action=action-name, request method to 'GET' and auth to false
$action = MediaWikiApi::action('action-name');

// Creates an action object with ?action=foo, request method to 'POST' and auth to true
$actionPostAuth = MediaWikiApi::action('foo', 'POST', true);

// Creates an action object with ?action=bar
$actionBar = MediaWikiApi::action('bar');
$actionBar->withAuthentication(); // auth is now true

// Adding parameters
$action->addParam('name', 'value'); // &name=value

$action->addParam('delete', true); // &delete=true
```