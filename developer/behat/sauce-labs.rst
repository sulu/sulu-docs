Sauce Labs
==========

`Sauce Labs`_ is a service which allows you to run browser tests
remotely. Like `Travis CI`_ it is free for open source projects such
as SuluCMF.

It functions in a similar way to the :doc:`getting-started` tests which you run
locally, but instead of connecting to a local Selenium server you
connect to the `Sauce Labs`_ server.

It works briefly as follows, you:

- Running your local web server
- You download and run the Sauce Labs connection tunnel
- You run your tests
- Your test runner talks to the Sauce Labs server
- The Sauce Labs server connects to *your* webserver through the tunnel
- The tests are rendered in the browser on the Sauce Labs server
- You watch the tests in real-time on the Sauce Labs web page.

Prerequisites
-------------

- You will need a `username` and `access-key` on Sauce Labs before continuing.
  `Sign-up here`_!

Downloading and Running the Connect Daemon
------------------------------------------

To run the tests remotely you will need to install and run the connect daemon.
This application will establish an SSH tunnel to the saucelabs server through
which HTTP requests will be sent to your webserver.

Download and unpack the ``Sauce-Connect`` file, for example:

.. code-block:: bash

    $ cd ~
    $ mkdir jars
    $ cd jars
    $ curl http://saucelabs.com/downloads/Sauce-Connect-latest.zip > SauceConnect.zip
    $ unzip SauceConnect.zip

Run it, replacing ``<username>`` and ``<access-key>`` with your credentials:

.. code-block:: bash

    $ java -jar Sauce-Connect.jar <username> <access-key>

Wait for the message:

.. code-block:: bash

    Connected! You may start your tests

Running the tests
-----------------

Great! Everything is almost ready, but now you need to set a couple of
environment variables before we run the tests.

.. code-block:: bash

    $ export SAUCE_USERNAME=<username>
    $ export SAUCE_ACCESS_KEY=<access-key>

Now you can finally launch behat using the ``sauce_labs`` profile:

.. code-block:: bash

    $ ./vendor/behat/behat/bin/behat -p sauce_labs

Visit https://saucelabs.com/tests to view the results.

.. note::

    You can easily specify only a specific or a subset of tests to run, see
    the :doc:`getting-started` guide.

.. _Sauce Labs: https://saucelabs.com/account
.. _tunnel: https://en.wikipedia.org/wiki/Tunneling_protocol
.. _Travis CI: http://travis-ci.org
.. _Sign-up here: https://saucelabs.com/signup/trial
