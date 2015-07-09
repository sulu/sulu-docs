Integrating custom filters
==========================

When you want to enable custom filters for your bundle you have to follow the 
following steps. It's important to know that the filter component works with 
contexts. This means for example that the lists of contacts has the 
`contacts` context and everything concering the filters for the list will 
need this context. It should therefore be unique.

Add the missing data types to the field descriptors
---------------------------------------------------

The custom filter feature uses the field descriptors you've already defined for 
your lists. To work as expected you should define the type of each column. If
not defined the filter component will asume it's a string. The available data 
types are:

- `string`
- `number` / `integer` / `float`
- `date` / `datetime`
- `boolean`

Add a context and configuration for new filters 
-----------------------------------------------

Add the filter configuration to e.g. app/config/admin/config.yml. The first
parameter below contexts is the context mentioned above. The fields parameter 
defines the url where the fields api can be found. In the features the filter
has to be enabled.

.. code-block:: yaml

	sulu_resource:
	    contexts:
	        contact:
	            fields: "/admin/api/contacts/fields"
	            features:
	                - "filters"

Extend the js configuration in your bundle
------------------------------------------

Extend the js configuration in your bundle with a config value for the bread-
crumb, an url for the fields api and the route back to the list. The last 
part of the setting key is the context the filters component will use.

.. code-block:: js

    Config.set('suluresource.filters.type.contacts', {
        breadCrumb: [
            {title: 'navigation.contacts'},
            {title: 'contact.contacts.title', link: 'contacts/contacts'}
        ],
        fields: 'admin/api/contacts/fields',
        routeToList: 'contacts/contacts'
    });

Extend the list and toolbar initialization for the lists
--------------------------------------------------------

The toolbar should have at least two groups and one of them should have the id 
2 because the filter button will be added in the one with id 2. Two additional
parameters have to added at the end. The first one is the instance name of the
datagrid and the second one is the selector for the container where the result
of the filter (x entries match filter y) will be displayed. Therefore a div
above the filter div should be added in the html.

.. code-block:: js

	this.sandbox.sulu.initListToolbarAndList.call(this, 'contacts', '/admin/api/contacts/fields',
        {
            el: this.$find('#list-toolbar-container'),
            instanceName: 'contacts',
            inHeader: true,
            groups: [
                {
                    id: 1,
                    align: 'left'
                },
                {
                    id: 2,
                    align: 'right'
                }
            ]
        },
        {
            el: this.sandbox.dom.find('#people-list', this.$el),
            url: '/admin/api/contacts?flat=true',
            searchInstanceName: 'contacts',
            searchFields: ['fullName'],
            resultKey: 'contacts',
            instanceName: 'contacts',
            viewOptions: {
                table: {
                    icons: [
                        {
                            icon: 'pencil',
                            column: 'firstName',
                            align: 'left',
                            callback: function(id) {
                                this.sandbox.emit('sulu.contacts.contacts.load', id);
                            }.bind(this)
                        }
                    ],
                    highlightSelected: true,
                    fullWidth: true
                }
            }
        },
        'contacts',
        '#people-list-info'
    );

.. code-block:: html

    <div id="people-list-info"></div>
    <div id="people-list"></div>