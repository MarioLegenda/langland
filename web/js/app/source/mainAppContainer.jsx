import {factory} from "./repository/factory";
import React from "react";

export class MainAppContainer extends React.Component {
    constructor(props) {
        super(props);
    }

    componentDidMount() {

    }

    render() {
        return <div className="app-console-wrapper">
            <div className="app-menu">
                <div className="menu-item">
                    <button className="circle-wrapper">
                        <span className="circle-wrapper-text lesson-text-wrapper">Lessons</span>
                        <i className="menu-icon fa fa-mortar-board fa-lg"></i>
                    </button>
                </div>

                <div className="menu-item">
                    <button className="circle-wrapper">
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
        </div>
    }
}