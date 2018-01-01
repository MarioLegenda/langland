import {user, env} from "../../global/constants.js";
import React from 'react';

export class Header extends React.Component {
    constructor(props) {
        super(props);

        this.logout = this.logout.bind(this);
    }

    logout(e) {
        e.preventDefault();

        location.href = env.current + 'langland/logout';

        return false;}

    render() {
        return <header className="full-width align-left">
            <div className="title-wrapper">
                <h1>Langland</h1>
            </div>

            <div className="profile-wrapper">
                <div className="fa fa-user-o fa-lg hoverable"></div>

                <div className="profile-pop">
                    <h3>{user.current.name} {user.current.lastname}</h3>

                    <a href="">Progress <i className="fa fa-mortar-board"></i></a>
                    <a href="" onClick={this.logout}>Logout <i className="fa fa-hand-o-left"></i></a>
                </div>
            </div>
        </header>
    }
}