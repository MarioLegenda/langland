import React from 'react';

import {routes, RouteHelper} from './../../routes.js';

class ProgressContainer extends React.Component {
    constructor(props) {
        super(props);
    }

    render() {
        return (
            <div className="progress-sidebar sidebar">
                <h1>Your progress</h1>
            </div>
        )
    }
}

class NotificationContainer extends React.Component {
    constructor(props) {
        super(props);
    }

    render() {
        return (
            <div className="notification-sidebar sidebar">
                <h1>Notifications</h1>
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
            <div>
                <NotificationContainer/>
                <ProgressContainer/>
            </div>
        )
    }
}