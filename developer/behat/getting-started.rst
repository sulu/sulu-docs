Getting started
===============

Choosing Driver
---------------

Selenium2 (with a browser)
~~~~~~~~~~~~~~~~~~~~~~~~~~

It is recommended to run tests locally with the `Selenium`_ driver. Using the
Selenium driver on a real browser allows you to see the tests running (as
opposed to using a headless browser as detailed in the following sections).

You can `download Selenium server from here`_, or install it as follows:

.. code-block:: bash

    $ mkdir ~/jars
    $ cd jars
    $ wget http://selenium-release.storage.googleapis.com/2.44/selenium-server-standalone-2.44.0.jar

You can then run it:

.. code-block:: bash

    $ java -jar selenium-server-standalone-2.44.0.jar -browserSessionReuse -singleWindow

The ``-browserSessionReuse`` tells selenium NOT to close the window after
every test and ``-singleWindow`` will cause Selenium not to use more than one
window.

.. note::

    You will need to install the Java Runtime Environment. On a debian based
    system you could do ``apt-get install default-jre``

Selenium2 (headless, with PhantomJs)
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

.. note::

    It is not currently recommended to run tests on PhantomJS - the results
    may be unpredictable.

PhantomJS can be installed with ``npm``:

.. code-block:: bash

    $ npm install phantomjs

You can then run it as follows:

.. code-block:: bash

    $ phantomjs --webdriver=8643

Then you will need to copy the default ``behat.yml.dist`` to ``behat.yml`` and
add the ``wd_host`` option as follows:

.. code-block:: yaml

    defaults:
        # ...
        extensions:
            # ...
            Behat\MinkExtension:
                sessions:
                    default:
                        selenium2:
                            wd_host: "http://localhost:8643/wd/hub"

Sauce Labs
~~~~~~~~~~

Our continuous integration system will run the tests on the Sauce Labs
service. See :doc:`sauce-labs` to find out how to run them yourself or for
more information.

Running the tests
-----------------

You can run all the tests as follows:

.. code-block:: bash

    $ ./vendor/behat/behat/bin/behat -p <profile>

Where ``<profile>`` is one of:

- **selenium**: Tests on your local machine via Selenium using a real browser
  above)
- **sauce_labs**: Run the tests on Sauce Labs (see :doc:`sauce-labs`).

The tests are split up into a number of *suites*. There is one suite for each
bundle, named after the bundle in lowercase, for example ``SuluContactBundle``
has the suite named ``contact``.

Run specific suites as follows:

.. code-block:: bash

    $ ./vendor/behat/behat/bin/behat --suite=contact

Further more you can filter for specific tests using the ``name`` option:

.. code-block:: bash

    $ ./vendor/behat/behat/bin/behat --suite=contact --name="Create"

The above will run all scenarios in the ``contact`` suite which contain the word
``Create``.

.. _Selenium: htto://www.seleniumhq.org
.. _download selenium server from here: http://www.seleniumhq.org/download/
