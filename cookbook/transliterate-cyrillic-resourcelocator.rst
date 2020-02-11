How to transliterate cyrillic ResourceLocators into latin?
==========================================================

If you intend to use cyrillic for page names/titles - you will end up with empty resource locators.

To overcome this - you can use  TreeTransliterateGeneratorDecorator - it will transliterate generated routes to latin
- so they won't be wiped off.

Example transliteration: title `Какво става маниаци?` will become `/kakvo-stava-maniatsi`

Usage
-------
Register these services:

.. code-block:: yaml

    transliterator:
        class: Transliterator
        factory: ['Transliterator', 'create']
        arguments: ['Russian-Latin/BGN']

    Sulu\Component\Content\Types\ResourceLocator\Strategy\TreeTransliteratorGeneratorDecorator:
        decorates: sulu.content.resource_locator.strategy.tree_generator
        arguments:
            - '@Sulu\Component\Content\Types\ResourceLocator\Strategy\TreeTransliteratorGeneratorDecorator.inner'
            - '@transliterator'
