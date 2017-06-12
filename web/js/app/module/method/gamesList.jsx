import React from 'react';
import {RouteCreator} from './../routes.js';

export class GameListContainer extends React.Component {
    constructor(props) {
        super(props);
    }

    _fetchGamesList() {
        jQuery.ajax({
            url: RouteCreator.create('app_find_available_games', [this.props.learningUserCourseId]),
            method: 'GET'
        }).done(jQuery.proxy(function(data) {
            console.log(data);
        }, this));
    }

    componentDidMount() {
        this._fetchGamesList();
    }

    render() {
        return <div></div>
    }
}