sulu-docs
=========

## Making the documentation:

You will need to install sphinx and generate the documentation.

First install the required sphinx extensions:

    $ git submodule update --init

Then you can build the documentation with:

    $ make html

## Testing

This Sulu documentation is tested with Behat by embedding behat statements
within the documentation.

This enables us to test every step of the documentation in a statefull way.

To run the tests install the dependencies:

    $ composer install

Build the Behat fixture files:

    $ make behat

And run the tests:

    $ ./vendor/bin/behat
