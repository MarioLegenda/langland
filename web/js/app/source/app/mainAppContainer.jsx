import {MainNavigation} from "./view/mainNavigation.jsx";
import {LessonPresentationContainer} from "./lessonPresentationContainer.jsx";
import {GamesPresentationContainer} from "./gamesPresentationContainer.jsx";

import {
    store,
    mainAppLoaded, lessonMenuClicked, gamesMenuClicked
} from "../events/events";

import React from "react";

export class MainAppContainer extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            actions: {
                mainAppLoaded: false,
                lessonMenuClicked: false,
                gamesMenuClicked: false,
            }
        };

        this.data = {
            actionMethods: [lessonMenuClicked, gamesMenuClicked]
        };

        this._appActions();
    }

    _appActions() {
        store.subscribe(() => {
            const appState = store.getState().app;

            console.log(appState);

            if (appState.mainAppLoaded) {
                this.setState((prevState) => {
                    prevState.actions = appState;
                });
            }
        });
    }

    componentWillUnmount() {
        store.dispatch(mainAppLoaded(false));
    }

    componentDidMount() {
        store.dispatch(mainAppLoaded(true));
        store.dispatch(lessonMenuClicked(true));
        store.dispatch(gamesMenuClicked(false));
    }

    render() {
        const lessonMenu = this.state.actions.lessonMenuClicked,
              gamesMenu = this.state.actions.gamesMenuClicked;

        return <div className="app-console-wrapper">
            <MainNavigation actionMethods={this.data.actionMethods}/>

            {lessonMenu === true && <LessonPresentationContainer/>}
            {gamesMenu === true && <GamesPresentationContainer/>}
        </div>
    }
}