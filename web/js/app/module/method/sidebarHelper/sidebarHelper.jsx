import React from 'react';

import {routes, RouteHelper} from './../../routes.js';

class ProgressItem extends React.Component {
    constructor(props) {
        super(props);
    }

    render() {
        const text = this.props.item.text;

        return (
            <div className="animated fadeIn full-width align-left sidebar-item margin-top-10 margin-bottom-10">
                <p><i className="fa fa-angle-right"></i>{text}</p>
            </div>
        )
    }
}

class ProgressContainer extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            items: []
        };

        this.onSocketUpdate();
    }

    onSocketUpdate() {
/*        this.props.io.on('server.update_progress', jQuery.proxy(function(data) {
            this.setState({
                items: data
            });
        }, this));*/
    }

    render() {
        const items = this.state.items.map((item, index) => <ProgressItem item={item} key={index}/>);

        return (
            <div className="progress-sidebar sidebar">
                <h1>Your progress<i className="fa fa-arrows"></i></h1>

                {items}
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
                <ProgressContainer io={this.props.io}/>
            </div>
        )
    }
}