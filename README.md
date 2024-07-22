# Laravel Nafath Integration Package

This Laravel package provides an integration with the Saudi Nafath authentication system. It allows you to easily manage user authentication via Nafath and handle verification callbacks.

## Features

- Send authentication requests to Nafath.
- Handle verification callbacks and update user statuses.
- Dispatch events for custom handling of verification success and rejection.

## Installation

### 1. Install the Package

You can install the package via Composer. Run the following command in your Laravel project:

```bash
composer require mohamad-zatar/nafath
```
### 2. Publish the Configuration
Publish the package configuration file to customize it according to your needs:

```bash
php artisan vendor:publish --provider="MohamadZatar\Nafath\NafathServiceProvider"
```

This will create a nafath.php configuration file in your config directory.

## Configuration

### 1. Add Configuration
In the config/nafath.php file, set your Nafath API URL base and API key:

```php
return [
    'NAFATH_URL_BASE' => env('NAFATH_URL_BASE', 'your-subdomain'),
    'NAFATH_API_KEY' => env('NAFATH_API_KEY', 'your-api-key'),
];
```
Add these values to your .env file:

```php
NAFATH_URL_BASE=your-subdomain
NAFATH_API_KEY=your-api-key
```

### 2. Migration
Publish the migration for the nafath_logins table:

```bash
php artisan migrate
```
## Usage

### 1. Sending Login Requests
To send a login request to Nafath, use the NafathController provided by the package. You can use this controller to handle authentication requests.

### 2. Handling Callbacks
Handle the callback from Nafath by defining the callback URL in your Nafath dashboard and configuring it to point to your application endpoint. The endpoint should be handled by the nafathPostCallback method in NafathController.

### 3. Event Handling
The package dispatches two events that you can listen to in your application:

- MohamadZatar\Nafath\Events\VerificationVerified
- MohamadZatar\Nafath\Events\VerificationRejected

#### Define Listeners
Create listeners for these events in your Laravel application:
```php
// app/Listeners/HandleVerificationVerified.php
namespace App\Listeners;

use MohamadZatar\Nafath\Events\VerificationVerified;

class HandleVerificationVerified
{
    public function handle(VerificationVerified $event)
    {
        // Custom logic for verification success
    }
}

// app/Listeners/HandleVerificationRejected.php
namespace App\Listeners;

use MohamadZatar\Nafath\Events\VerificationRejected;

class HandleVerificationRejected
{
    public function handle(VerificationRejected $event)
    {
        // Custom logic for verification rejection
    }
}
```

#### Register Event Listeners

Add your listeners to the EventServiceProvider:



```php
// app/Providers/EventServiceProvider.php
protected $listen = [
    VerificationVerified::class => [
        HandleVerificationVerified::class,
    ],
    VerificationRejected::class => [
        HandleVerificationRejected::class,
    ],
];
```
## Contributing
Contributions are welcome! Please submit a pull request or create an issue for any bugs or feature requests.

## License

[MIT](https://choosealicense.com/licenses/mit/)
## Contact
For any questions or support, please contact Mohamad Zatar at lokasees@gmail.com.
