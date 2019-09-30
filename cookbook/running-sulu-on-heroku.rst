Running Sulu on Heroku
======================

`Heroku`_ enables programmers to run their applications in the cloud, and makes
it really easy to scale your application if it follows some rules. There is a 
`Sulu cloud edition`_, which follows these rules.

You will find a "Deploy to Heroku" button in the ``README.md`` file of the
repository of the previously mentioned `Sulu cloud edition`_. This button leads
to a Heroku page for deploying your very own Sulu installation.

Give the application a name and choose between `Europe` and `United States` as 
a region, whatever is closer to your actual destination. This choice will also
influence the performance of your website.

Unless you really want to, you should leave the ``SYMFONY_ENV`` environment
variable to ``prod``. Otherwise the build process will fail, because Heroku
installs the dependencies with ``composer install --no-dev``. If you change the
environment to ``dev`` or ``test`` Sulu will try to load some Symfony bundles,
which are not installed, and therefore lead to an error during the build
procedure.

The other environment variable you have to set is ``DOMAIN``. Set it to
whatever domain this installation should work on, e.g.
``sulu-cloud.herokuapp.com`` if you don't have your own DNS entry or to
something like ``sulu.io``.

For more details about working with Heroku you should check out the `Heroku Dev 
Center`_.

.. _Heroku: http://www.heroku.com
.. _Sulu cloud edition: https://github.com/sulu/sulu-heroku
.. _Heroku Dev Center: https://devcenter.heroku.com/
