(function ($) {
    'use strict';

    $('html').classList.remove('no-js');

    // React binding
    var React = require('react'),
        SampleList = React.createFactory(require('./jsx/sampleList')),

        app = React.render(
            SampleList(),
            document.getElementById('content')
        );
})
(document.querySelector.bind(document));