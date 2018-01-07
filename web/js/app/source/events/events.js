import { createStore, combineReducers } from 'redux';

export const LanguageActions = {
    FETCH_ALL_IN_PROGRESS: 'FETCH_IN_PROGRESS',
    FETCH_ALL_COMPLETED: 'FETCH_COMPLETED',
    REGISTER_LANGUAGE_IN_PROGRESS: 'REGISTER_LANGUAGE_IN_PROGRESS',
    LANGUAGES_FETCHED: 'LANGUAGES_FETCHED',
    UPDATE_LANGUAGE: 'UPDATE_LANGUAGE'
};

let appModel = {
    language: {
        isFetchingAll: false,
        languages: []
    }
};

export function fetchAllLanguagesInProgress() {
    return {
        type: LanguageActions.FETCH_ALL_IN_PROGRESS,
        isFetchingAll: true
    }
}

export function fetAllLanguagesCompleted() {
    return {
        type: LanguageActions.FETCH_ALL_COMPLETED,
        isFetchingAll: false
    }
}

export function languagesFetched(languages) {
    return {
        type: LanguageActions.LANGUAGES_FETCHED,
        languages: languages
    }
}

function language(state = appModel, action) {
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
        case LanguageActions.UPDATE_LANGUAGE:

        default:
            return state;
    }
}

const reduxApp = combineReducers({
    language
});

export const store = createStore(reduxApp);