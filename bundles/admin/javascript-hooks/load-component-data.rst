Load-component-data
===================

As a front-end developer you'll often find yourself loading data at the beginning
of your component startup and continuing only after the data has been loaded.

The load-component-data-hook simplifies this task. Within the return-object of a
javascript-component, you can specify a ``loadComponentData`` method where you load your data. This method must
return a promise or your desired data straight away. If such a method is specified
the AdminBundle delays the startup of your component and sets ``this.data`` with your laoded data
(where ``this`` is the context of your component). So when your components ``initialize`` method
gets called you can conveniently access your data via ``this.data`` and don't have to worry
about asynchronicity.

So the ``loadComponentData`` and ``initialize`` method of your component would look somthing like:

.. code-block:: javascript

    /**
     * This method gets called by the AdminBundle
     */
    loadComponentData: function() {
        var promise = $.Deffered();

        $.ajax({
            url: '/url-to-your-data',
        }).done(function(data) {
            //resolve promise. So your component can continue with the startup
            promise.resolve(data);
        });

        return promise;
    },

    /**
     * When this method gets called this.data is already set with
     * your loaded data
     */
    initialize: function() {
        // this.data is set with your data and can be used for whatever you want
        this.render(this.data);
    }
