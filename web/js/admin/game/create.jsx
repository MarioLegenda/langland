import React from 'react';
import ReactDOM from 'react-dom';

import {url} from './../url.js';

export class GameCreateInit extends React.Component {
    constructor(props) {
        super(props);
    }

    _fetchLessons() {
        this.props.dataSource.fetchLessons(url)
            .done(jQuery.proxy(function(data, content, response) {
                console.log('lesson fetch success', response.status);
            }, this));
    }
}