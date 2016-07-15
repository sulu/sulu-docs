Adding translations
===================

For many developers adding translations is the first step in becoming an open
source contributor. Apart from that, it also adds value to the project, so we
are explaining the steps here, to make the work for translators as easy as
possible.

Understanding the translation process
-------------------------------------

First of all it is important to actually understand the translation process of
Sulu.

Every bundle requiring translations stores them in their
`Resources/translations/sulu` folder using the xliff_ format. These files are
named using the scheme ``backend.[language].xlf`` and look something like this:

.. code-block:: xml

    <?xml version="1.0" encoding="utf-8"?>
    <xliff xmlns="urn:oasis:names:tc:xliff:document:1.2" version="1.2">
        <file source-language="en" datatype="plaintext" original="file.ext">
            <body>
                <trans-unit id="a50af522ac181350142c0b4720e29a3f" resname="translation.code">
                    <source>translation.code</source>
                    <target>Some translation</target>
                </trans-unit>
            </body>
        </file>
    </xliff>

If the ``SuluTranslateBundle`` is registered in the Kernel, there are two
commands available to manage Sulu's translations. ``sulu:translate:import``
takes a language code (e.g. ``en`` or ``de``) and imports all the translation
files in the given language into the system. Corresponding to that, the command
``sulu:translate:export`` also takes a language code, and exports the
translations from all bundles into a single JSON file located in
`web/admin/translations`. From there the Sulu-Admin loads the translations
for the required localization.

Add a new translation
---------------------

The following guide explains how to add a complete new translation for the
Sulu-Admin.

These steps have to be executed for every single bundle:

#. Copy the file `Resources/translations/sulu/backend.en.xlf` (or any other
   language you might be more comfortable with) into
   `Resources/translations/sulu/backend.[language].xlf` (replace
   ``[language]`` with the `two-letter ISO code`_ of the new language).

#. Replace the translations in all ``target`` xml tags with the translation for
   the new language.

Afterwards the next steps have to be executed once for introducing the language
in the standard edition of Sulu:

#. Add the language in the file `app/config/sulu.yml` to
   ``sulu_core.locales``, with the 2-letter code as the key, and the name of
   the new language (in this language) as the value.

#. Add the 2-letter code to the array ``sulu_core.translations``

#. Build the new language using
   ``app/console sulu:translate:export [language]``. If you updated
   multiple languages at once, you can leave off the `language` part.

Afterwards commit all the changes and create Pull Requests as described in
:doc:`pull-requests`.

.. _xliff: https://en.wikipedia.org/wiki/XLIFF
.. _two-letter ISO code: https://en.wikipedia.org/wiki/List_of_ISO_639-1_codes
