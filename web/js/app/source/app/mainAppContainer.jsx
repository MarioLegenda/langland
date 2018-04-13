import {MainNavigation} from "./view/mainNavigation.jsx";
import {LessonPresentationContainer} from "./lessonPresentationContainer.jsx";
import {GamesPresentationContainer} from "./gamesPresentationContainer.jsx";

import {
    store,
    mainAppLoaded, lessonMenuClicked, gamesMenuClicked, handleMenuHeight
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

            $('.app-menu').height(appState.menuHeight);
        });
    }

    componentWillUnmount() {
        store.dispatch(mainAppLoaded(false));
    }

    componentDidMount() {
        this._appActions();

        store.dispatch(mainAppLoaded(true));
        store.dispatch(lessonMenuClicked(true));
        store.dispatch(gamesMenuClicked(false));
        store.dispatch(handleMenuHeight(800));
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