import { createStore, combineReducers } from 'redux';

export const LanguageActions = {
    FETCH_ALL_IN_PROGRESS: 'FETCH_IN_PROGRESS',
    REGISTER_LANGUAGE_IN_PROGRESS: 'REGISTER_LANGUAGE_IN_PROGRESS',
    LANGUAGES_FETCHED: 'LANGUAGES_FETCHED',
    UPDATE_LANGUAGE: 'UPDATE_LANGUAGE'
};

export const ViewActions = {
    MAIN_APP_LOADED: 'MAIN_APP_LOADED',
    LESSON_MENU_CLICKED: 'LESSON_MENU_CLICKED',
    GAMES_MENU_CLICKED: 'GAMES_MENU_CLICKED',
    LESSON_STARTED: 'LESSON_STARTED',
    MENU_HEIGHT: 'MENU_HEIGHT',
};

let languageModel = {
    language: {
        isFetchingAll: false,
        languages: [],
        isRegistering: false
    }
};

let appModel = {
    mainAppLoaded: false,
    lessonMenuClicked: false,
    gamesMenuClicked: false,
    lessonStarted: false,
    menuHeight: 900
};

export function mainAppLoaded(mainAppLoaded) {
    return {
        type: ViewActions.MAIN_APP_LOADED,
        mainAppLoaded: mainAppLoaded
    }
}

export function handleMenuHeight(height) {
    return {
        type: ViewActions.MENU_HEIGHT,
        menuHeight: height
    }
}

export function lessonMenuClicked(lessonMenuClicked) {
    return {
        type: ViewActions.LESSON_MENU_CLICKED,
        lessonMenuClicked: lessonMenuClicked
    }
}

export function gamesMenuClicked(gamesMenuClicked) {
    return {
        type: ViewActions.GAMES_MENU_CLICKED,
        gamesMenuClicked: gamesMenuClicked
    }
}

export function lessonStarted(lessonStarted) {
    return {
        type: ViewActions.LESSON_STARTED,
        lessonStarted: lessonStarted
    }
}

export function fetchAllLanguagesInProgress(isFetchingAll) {
    return {
        type: LanguageActions.FETCH_ALL_IN_PROGRESS,
        isFetchingAll: isFetchingAll
    }
}

export function languagesFetched(languages) {
    return {
        type: LanguageActions.LANGUAGES_FETCHED,
        languages: languages
    }
}

export function registeringLanguage(isRegistering) {
    return {
        type: LanguageActions.REGISTER_LANGUAGE_IN_PROGRESS,
        isRegistering: isRegistering
    }
}

function language(state = languageModel, action) {
    switch (action.type) {
        case LanguageActions.FETCH_ALL_IN_PROGRESS:
            return Object.assign({}, state.language, {
                isFetchingAll: true
            });
        case LanguageActions.FETCH_ALL_COMPLETED:
            return Object.assign({}, state.language, {
                isFetchingAll: false
            });
        case LanguageActions.LANGUAGES_FETCHED:
            return Object.assign({}, state.language, {
                languages: action.languages
            });
        case LanguageActions.REGISTER_LANGUAGE_IN_PROGRESS:
            return Object.assign({}, state.language, {
                isRegistering: action.isRegistering
            });
        default:
            return state;
    }
}

function app(state = appModel, action) {
    switch (action.type) {
        case ViewActions.MAIN_APP_LOADED:
            return Object.assign({}, state, {
                mainAppLoaded: action.mainAppLoaded
            });
        case ViewActions.LESSON_MENU_CLICKED:
            return Object.assign({}, state, {
                lessonMenuClicked: action.lessonMenuClicked
            });
        case ViewActions.GAMES_MENU_CLICKED:
            return Object.assign({}, state, {
                gamesMenuClicked: action.gamesMenuClicked
            });
        case ViewActions.LESSON_STARTED:
            return Object.assign({}, state, {
                lessonStarted: action.lessonStarted
            });
        case ViewActions.MENU_HEIGHT:
            return Object.assign({}, state, {
                menuHeight: action.menuHeight
            });
        default:
            return state;
    }
}

const reduxApp = combineReducers({
    language,
    app
});

export const store = createStore(reduxApp);