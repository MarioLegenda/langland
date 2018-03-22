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
};

let languageModel = {
    language: {
        isFetchingAll: false,
        languages: [],
        isRegistering: false
    }
};

let appModel = {
    isMainAppLoaded: false,
    isLessonMenuClicked: false,
    isGamesMenuClicked: false,
};

export function mainAppLoaded(mainAppLoaded) {
    return {
        type: ViewActions.MAIN_APP_LOADED,
        isMainAppLoaded: mainAppLoaded
    }
}

export function lessonMenuClicked(lessonMenuClicked) {
    return {
        type: ViewActions.LESSON_MENU_CLICKED,
        isLessonMenuClicked: lessonMenuClicked
    }
}

export function gameMenuClicked(gamesMenuClicked) {
    return {
        type: ViewActions.GAMES_MENU_CLICKED,
        isGamesMenuClicked: gamesMenuClicked
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
                isMainAppLoaded: action.isMainAppLoaded
            });
        case ViewActions.LESSON_MENU_CLICKED:
            return Object.assign({}, state, {
                isLessonMenuClicked: action.isLessonMenuClicked
            });
        case ViewActions.GAMES_MENU_CLICKED:
            return Object.assign({}, state, {
                isGamesMenuClicked: action.isGamesMenuClicked
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