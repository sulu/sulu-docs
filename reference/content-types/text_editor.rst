Text editor
===========

Description
-----------

Shows a rich text editor, capable of formatting text as well. The output of the
editor will be stored as HTML in a string field.

Example
-------

.. code-block:: xml

    <property name="description" type="text_editor">
        <meta>
            <title lang="en">Description</title>
        </meta>
    </property>

Twig
-----

When outputting the text editor field in twig the `raw filter`_ need to be used:

.. code-block:: twig

    {{ content.description|raw }}

.. _raw filter: https://twig.symfony.com/doc/3.x/filters/raw.html

Language of text parts
----------------------

The toolbar contains a language dropdown, which lets editors mark a part of the
text with its language. The marked text is wrapped in a ``span`` with ``lang`` and
``dir`` attributes, so screen readers pronounce foreign-language passages
correctly, as required by the WCAG "language of parts" success criterion:

.. code-block:: html

    <p>The motto was <span lang="de" dir="ltr">Vorsprung durch Technik</span>.</p>

By default the dropdown offers the languages of all webspace localizations,
without their country variants, so the localizations ``de_at`` and ``de_ch``
both result in ``de``. A different list of languages can be configured in
``config/packages/sulu_admin.yaml``:

.. code-block:: yaml

    sulu_admin:
        ckeditor:
            text_part_languages: ['en', 'de', 'ar']

Only language codes without a country are accepted, so ``de`` is valid while
``de_at`` or ``de-AT`` cause a configuration error.

What about images in text editor?
---------------------------------

A very common question is how to handle images in a text editor property.
The answer is you don't handle them inside the text editor.

In Sulu the text editor is only meant for text formatting. So its not like other
CMS systems where all content live inside one big WYSIWYG editor.

Sulu follows the principle of separating content and presentation. Therefore, images should
be handled as separate media properties. This approach gives developers and designers full
control over how images are presented on the website. It makes it easier to provide content in
different formats through headless APIs to apps and other services. It also simplifies website
redesigns, as content outlives any design.

A typical Sulu page would use the :doc:`block type <block>`  to allow editors
to create flexible pages. See the :doc:`block type documentation <block>` for more
information.
