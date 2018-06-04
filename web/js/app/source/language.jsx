import React from 'react';
import {Link} from 'react-router-dom';
import {UrlRecorder} from "./tool/util.js";

import {factory as repoFactory} from "./repository/factory.js";
import {
    store,
    fetchAllLanguagesInProgress,
    languagesFetched
}
from "./events/events.js";

import {CenterLoading} from "./util.jsx";

class Item extends React.Component {
    constructor(props) {
        super(props);

        this.registerLanguageSession = this.registerLanguageSession.bind(this);
    }

    registerLanguageSession(e) {
        e.preventDefault();

        this.props.registerLanguageSession(this.props.language);

        return false;
    }

    render() {
        const language = this.props.language,
              alreadyLearning = language.alreadyLearning,
              alreadyLearningClass = (alreadyLearning) ? 'already-learning': '',
              alreadyLearningButtonText = (alreadyLearning) ? 'Continue': 'Start learning',
              isInsideRegistration = this.props.isInsideRegistration;

        return <div className="language">
                <div className={"title-wrapper " + alreadyLearningClass}>
                    <h1>{language.name}</h1>
                    {alreadyLearning &&
                        <i className="fa fa-check"></i>
                    }
                </div>

                <div className="image-wrapper">
                    <img src={language.images.cover} />
                </div>

                <div className="description-wrapper">
                    <p>{language.description}</p>
                </div>

                <div className="button-wrapper">
                    {isInsideRegistration && <CenterLoading/>}
                    {!isInsideRegistration && <Link className="language-link" onClick={this.registerLanguageSession} to={""}>{alreadyLearningButtonText}</Link>}
                </div>
            </div>
    }
}

export class LanguageList extends React.Component{
    constructor(props) {
        super(props);

        this.languageRepository = repoFactory('language');
        this.languageSessionRepository = repoFactory('language-session');

        this.registerLanguageSession = this.registerLanguageSession.bind(this);

        this.state = {
            items: null,
            itemsData: null
        };
    }

    componentDidMount() {
        this._getLanguages();
    }

    registerLanguageSession(language) {
        this._updateItems(language.id);
        this.languageSessionRepository.registerLanguageSession(language.id, $.proxy(function() {
            UrlRecorder.record('language-list', language.urls.frontend_url);

            this.props.match.history.push(language.urls.frontend_url);
        }, this));
    }

    _createItems(data) {
        this.setState(() => {
            const languages = data.map((language, i) => {
                return <Item
                    key={i}
                    language={language}
                    history={this.props.match.history}
                    registerLanguageSession={this.registerLanguageSession}
                />;
            });

            store.dispatch(fetchAllLanguagesInProgress(false));
            store.dispatch(languagesFetched(data));

            return {
                items: languages,
                itemsData: data,
            };
        });
    }

    _updateItems(languageId) {
        let data = this.state.itemsData;

        this.setState(() => {
            const languages = data.map((language, i) => {
                if (language.id === languageId) {
                    language.alreadyLearning = true;

                    return <Item
                        key={i}
                        language={language}
                        isInsideRegistration={true}
                        history={this.props.match.history}
                        registerLanguageSession={this.registerLanguageSession}
                    />;
                }

                return <Item
                    key={i}
                    language={language}
                    isInsideRegistration={false}
                    history={this.props.match.history}
                    registerLanguageSession={this.registerLanguageSession}
                />;
            });

            store.dispatch(fetchAllLanguagesInProgress(false));
            store.dispatch(languagesFetched(data));

            return {
                items: languages,
                itemsData: data,
            };
        });
    }

    _getLanguages() {
        store.dispatch(fetchAllLanguagesInProgress(true));

        this.languageRepository.getAllShowableLanguges($.proxy(function(data) {
            this._createItems(data.collection.data);
        }, this), $.proxy(function(data) {
            // TODO: error handling, POPUP?
        }, this));
    }

    render() {
        const items = this.state.items;

        if (items === null) {
            return null;
        }

        return <div className="animated fadeIn languages-wrapper">
                {items}
            </div>
    }
}