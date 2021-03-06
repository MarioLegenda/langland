import React from 'react';
import {envr} from './env.js';
import {routes, RouteCreator} from './routes.js';

class UserProfileBar extends React.Component {
    constructor(props) {
        super(props);
    }

    render() {
        const user = this.props.user;

        return (
            <div className="bar align-left relative user-bar">
                <button className="menu-button"><i className="fa fa-user fa-2x bar-icon"></i></button>

                <div className="bar-popup absolute user-bar-popup">
                    <h1 className="margin-bottom-20">Hello, {user.name}</h1>

                    <a href={routes.app_logout} className="logout margin-top-20 relative"><i className="fa fa-minus-square"></i>Logout</a>
                </div>
            </div>
        )
    }
}

export class UserProfileBarContainer extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            user: null
        }
    }

    _fetchLoggedInUser() {
        this.props.DataSource.fetchLoggedInUser()
            .done(jQuery.proxy(function(data, content, response) {
                if (response.status === 200) {
                    this.setState({
                        user: data
                    });
                }
            }, this));
    }

    componentDidMount() {
        this._fetchLoggedInUser();
    }

    render() {
        const user = this.state.user;

        if (user === null) {
            return null;
        }

        return <UserProfileBar user = {this.state.user} />
    }
}

class CourseBar extends React.Component {
    constructor(props) {
        super(props);
    }

    render() {
        const that = this;
        const items = this.props.items.map(function(item) {
            const currentLangClass = (item.id === that.props.currentItem.id) ? 'current-course' : '';
            const languageUrl = RouteCreator.create('app_page_course_dashboard', [item.name, item.id]);

            return <a href={languageUrl} key={item.id} className={"language-link margin-bottom-20 " + currentLangClass}>{item.name.toLowerCase()}</a>
        });

        return (
            <div className="bar align-left relative course-bar">
                <button className="menu-button"><i className="fa fa-bank fa-2x bar-icon"></i></button>

                <div className="bar-popup absolute course-bar-popup">
                    {
                        items.length > 0 &&
                        <h1 className="margin-bottom-20">Started languages</h1>
                    }

                    {items}

                    <a href={routes.app_dashboard} className="new-language margin-top-20"><i className="fa fa-plus"></i>Learn new language</a>
                </div>
            </div>
        )
    }
}

class CourseBarContainer extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            item: null
        }
    }

    _fetchStructuredLanguages() {
        this.props.DataSource.fetchStructuredLanguages()
            .done(jQuery.proxy(function(data, message, response) {
                if (response.status === 200) {
                    this.setState({
                        item: data
                    })
                }
            }, this));
    }

    componentDidMount() {
        this._fetchStructuredLanguages();
    }

    render() {
        let items = [];
        let currentItem = null;

        if (this.state.item  === null) {
            return null;
        }

        if (this.state.item.hasOwnProperty('currentLanguage')) {
            items = this.state.item.signedLanguages;
            currentItem = this.state.item.currentLanguage;
        }

        return (
            <CourseBar items={items} currentItem={currentItem}/>
        )
    }
}

function Header(props) {
    return (
        <header className="full-width app-header" id="react-header">
            <div className="full-width">
                <h1 className="align-left main-title">
                    <a href={routes.app_dashboard}>Langland</a>
                </h1>
            </div>

            <div className="align-right">
                <CourseBarContainer DataSource={props.DataSource}/>
                <UserProfileBarContainer DataSource={props.DataSource}/>
            </div>
        </header>
    )
}

export function HeaderContainer(props) {
    return <Header DataSource={props.DataSource}/>
}