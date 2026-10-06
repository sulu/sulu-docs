``sulu_sitemap_aliases``
========================

Returns the aliases of all registered :doc:`sitemap providers </cookbook/sitemap-provider>`, e.g.
``['pages', 'articles']``. Use it together with
:doc:`sulu_sitemap </reference/twig-extensions/functions/sulu_sitemap>` to render one section per content
type without hardcoding the aliases.

**Example**:

.. code-block:: twig

    {% for alias in sulu_sitemap_aliases() %}
        <h2>{{ ('sitemap.' ~ alias)|trans }}</h2>

        <ul>
            {% for entry in sulu_sitemap(alias: alias) %}
                <li>
                    <a href="{{ entry.loc }}">{{ entry.title|default(entry.loc) }}</a>
                </li>
            {% endfor %}
        </ul>
    {% endfor %}

**Arguments**:

None.

**Returns**: *string[]* - aliases of the registered sitemap providers
