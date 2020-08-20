Extending website search
=========================

There is the possibility to find other documents than pages
through the website search, but that needs to be configured.
The SuluArticleBundle is probably one of the best use cases
for that, because you might want articles to appear in the
website search.

The configuration looks like the following:

.. code-block:: yaml

    # config/packages/sulu_search.yaml

    sulu_search:
        website:
            indexes:
                articles: article_published

You could also prevent pages from being found using the
search. This can be achieved using the following code:

.. code-block:: yaml

    # config/packages/sulu_search.yaml

    sulu_search:
        website:
            indexes:
                articles: article_published
                pages: null

Another use case would be custom entities. But don't forget,
that you have to handle indexing and deindexing by yourself.
