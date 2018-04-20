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

        this.storeUnsubscribe = null;
    }

    _appActions() {
        this.storeUnsubscribe = store.subscribe(() => {
            const appState = store.getState().app;

            if (appState.mainAppLoaded) {
                this.setState((prevState) => {
                    prevState.actions = appState;
                });
            }

            if (appState.menuHeight < 900) {
                $('.app-menu').height(900);

                return;
            }

            $('.app-menu').height(appState.menuHeight);
        });
    }

    componentWillUnmount() {
        store.dispatch(mainAppLoaded(false));

        if (this.storeUnsubscribe !== null) {
            this.storeUnsubscribe();
        }
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