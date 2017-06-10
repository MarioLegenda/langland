import React from 'react';

import {routes, RouteHelper} from './../../routes.js';

class ProgressContainer extends React.Component {
    constructor(props) {
        super(props);
    }

    render() {
        return (
            <div className="progress-sidebar sidebar">
                <h1>Your progress<i className="fa fa-arrows"></i></h1>
            </div>
        )
    }
}

export class SidebarHelperContainer extends React.Component {
    constructor(props) {
        super(props);
    }

    render() {
        return (
            <div className="animated fadeInDown sidebar-helper">
                <ProgressContainer/>
            </div>
        )
    }
}