Content Type Reference
======================

As already described in :doc:`/book/templates`
a template consists of multiple content types, which enable the user to manage
content in a semantic way.

The simplest template possible looks something like the this:

.. code-block:: xml

    <?xml version="1.0" ?>
    <template xmlns="http://schemas.sulu.io/template/template"
              xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
              xsi:schemaLocation="http://schemas.sulu.io/template/template http://schemas.sulu.io/template/template-1.0.xsd">
        <key>default</key>

        <view>ClientWebsiteBundle:templates:default</view>
        <controller>Sulu\Bundle\WebsiteBundle\Controller\DefaultController::indexAction</controller>
        <cacheLifetime>2400</cacheLifetime>

        <meta>
            <title lang="en">Default</title>
        </meta>

        <properties>
            <property name="title" type="text_line" mandatory="true">
                <meta>
                    <title lang="en">Title</title>
                </meta>

                <tag name="sulu.rlp.part"/>
            </property>

            <property name="url" type="resource_locator" mandatory="true">
                <meta>
                    <title lang="en">Resourcelocator</title>
                </meta>

                <tag name="sulu.rlp"/>
            </property>
        </properties>
    </template>

This chapter will describe which types you can insert within the
``properties`` tag. Every content type in the documentation comes with an
example ``property`` tag to clarify the usage.

This documentation also specifies the available parameters and tags for each
content type:

.. toctree::
    :maxdepth: 1

    block
    contact_selection
    account_selection
    category_selection
    single_category_selection
    checkbox
    color
    contact_account_selection
    date
    email
    image_map
    location
    media_selection
    number
    page_selection
    phone
    resource_locator
    select
    single_select
    single_page_selection
    single_contact_selection
    single_account_selection
    single_media_selection
    smart_content
    snippet_selection
    tag_selection
    teaser_selection
    text_area
    text_editor
    text_line
    time
    url
