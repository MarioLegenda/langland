import React from 'react';
import {Link} from 'react-router-dom';

import {user, env} from "../../global/constants.js";
import {CenterLoading} from "./util.jsx";
import {store} from "./events/events.js";


export class Header extends React.Component {
    constructor(props) {
        super(props);

        this.logout = this.logout.bind(this);

        this.state = {
            areLanguagesFetched: false
        };

        store.subscribe(() => {
            const isFetchingAllLanguages = store.getState().language.isFetchingAll;

            this.setState((prevState) => {
                prevState.areLanguagesFetched = isFetchingAllLanguages;
            });
        });
    }

    logout(e) {
        e.preventDefault();

        location.href = env.current + 'langland/logout';

        return false;
    }

    render() {
        const areLanguageFetched = this.state.areLanguagesFetched;

        return <header className="full-width align-left">
            <div className="title-wrapper">
                <Link to={ env.current + 'langland' }>Langland</Link>

                {areLanguageFetched && <CenterLoading/>}
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