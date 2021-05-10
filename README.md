# NGTS Test

## Install

To install this project just run `composer install`
Then run `php solution.php` to see the solution for the test.
This solution does a var dump with the results, casting them as string.

The libraries installed are just for testing and code linting. Psr-4 is handled by composer. All the productive code has been done by hand.

## Instructions

The instructions are in `INSTRUCTIONS.md`

## Data
The data used for the test is a data fixture in a json, but if you want you can replace it for a real database or another format. Just create another repository that implements the `App\Data\Repository\CityRepositoryInterface` and use that implementation.

## Tests

To run the tests just run `vendor/bin/phpunit`