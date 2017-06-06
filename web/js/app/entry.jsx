import React from 'react';
import ReactDOM from 'react-dom';
import { BrowserRouter as Router, Route, Switch} from 'react-router-dom';

import {CourseRouteContainer} from './module/routeContainers.jsx';

import {HeaderContainer as Header} from "./module/header.jsx";
import {LanguageListContainer} from "./module/languages.jsx";
import {envr} from './module/env.js';
import {MethodAppRouteContainer} from './module/method/methodApp.jsx';

const NoMatch = () => <div>No match</div>

function App() {
    return (
        <Router>
            <div className="app">
                <Header/>

                <Switch>
                    <Route path={envr + "langland/:languageName/:languageId"} component={CourseRouteContainer}/>
                    <Route exact path={envr + "langland"} component = {LanguageListContainer} />

                    <Route component={NoMatch}/>
                </Switch>
            </div>
        </Router>
    );
}

ReactDOM.render(
    <App/>,
    document.getElementById('react-app')
);



