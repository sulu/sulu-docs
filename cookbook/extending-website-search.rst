Extending website search
=========================

There is the possibility to find other documents than pages
through the website search. But that needs to be configured.
The SuluArticleBundle is probably one of the best use cases
for that, because you might want articles to appear in the
website search.

The configuration looks like the following:

.. code-block:: yaml

    # config/packages/sulu_search.yaml

    sulu_search:
        website:
            additional_indexes:
                - article_published

Another use case would be custom entities. But don't forget,
that you have to handle indexing and deindexing by yourself.
