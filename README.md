# Triggerise BE Challenge  - Aline Souza

## Installation instructions
1. In the root folder, copy env-example to .env
2. Insert your database definitions (for sqlite, use DB_CONNECTION=sqlite and comment the other DB_* constants)
3. If you chose sqlite, create a database.sqlite file inside database folder
4. Enter the docker folder and run `docker-compose up -d nginx` to start the server
5. Run `docker-compose exec workspace bash` in order to run `php artisan` and `phpunit` commands

## Usage
Is possible to test the service in Tinker, the Laravel interative console.

Before use the service, you need to create and populate the products table (see Useful commands section below).

**Example** (enter each line in the Tinker terminal and press enter):

``` 
use App\Services\Checkout;
$pricingRules['HOODIE'] = ['rule' => ['name' => 'bulk', 'args' => ['minOrder' => 3, 'discount' => 1]]];
$pricingRules['TICKET'] = ['rule' => [ 'name' => 'twoForOne']];
$co = new Checkout($pricingRules);
$co->scan("TICKET");
$co->scan("HOODIE");
$co->scan("HAT");
$co->total();
$co->clear(); //clear the cart 
```
   
## Useful commands
* `php artisan migrate --seed` - Create and populate the products table
* `php artisan tinker` - Initiate the tinker console
* `phpunit` - Run the tests

## Comments
* The controller app/Http/Controllers/CheckoutController was created to ilustrate the use of the service.
* The request app/Http/Requests/CheckoutRequest was created to ilustrate the validation of the data provided to the service.
* The service throws exceptions that need to be handled by the method calling the service.
* The tests are inside the folder tests/Unit.
* The service files are inside the folder app/Services

