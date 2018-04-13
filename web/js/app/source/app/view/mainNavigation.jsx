import React from "react";

import {
    store,
    gamesMenuClicked,
    lessonMenuClicked
} from "../../events/events";

export class MainNavigation extends React.Component {
    constructor(props) {
        super(props);
    }

    handleMenuChange(e, actionMethod) {
        e.preventDefault();

        store.dispatch(actionMethod(true));

        for (let method of this.props.actionMethods) {
            if (method != actionMethod) {
                store.dispatch(method(false));
            }
        }
    }

    render() {
        return <div className="animated fadeIn app-menu">
            <div className="menu-item">
                <button className="circle-wrapper" onClick={(e) => this.handleMenuChange(e, lessonMenuClicked)}>
                    <span className="circle-wrapper-text lesson-text-wrapper">Lessons</span>
                    <i className="menu-icon fa fa-mortar-board fa-lg"></i>
                </button>
            </div>

            <div className="menu-item">
                <button className="circle-wrapper" onClick={(e) => this.handleMenuChange(e, gamesMenuClicked)}>
                    <span className="circle-wrapper-text games-text-wrapper">Games</span>
                    <i className="menu-icon fa fa-gamepad fa-lg"></i>
                </button>
            </div>

            <div className="menu-item">
                <button className="circle-wrapper">
                    <span className="circle-wrapper-text trophies-text-wrapper">Trophies</span>
                    <i className="menu-icon fa fa-trophy fa-lg"></i>
                </button>
            </div>
        </div>
    }
}