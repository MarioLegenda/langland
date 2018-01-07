import React from 'react';
import {Link} from 'react-router-dom';

import {factory as repoFactory} from "./repository/factory.js";
import {
    store,
    fetchAllLanguagesInProgress,
    languagesFetched,
    registeringLanguage}
from "./events/events.js";
import {CenterLoading} from "./util.jsx";


class Item extends React.Component {
    constructor(props) {
        super(props);

        this.registerLanguage = this.registerLanguage.bind(this);
    }

    registerLanguage(e) {
        e.preventDefault();

        this.props.registerLanguage(this.props.language);

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
                    <p>{language.desc}</p>
                </div>

                <div className="button-wrapper">
                    {isInsideRegistration && <CenterLoading/>}
                    {!isInsideRegistration && <Link className="language-link" onClick={this.registerLanguage} to={""}>{alreadyLearningButtonText}</Link>}
                </div>
            </div>
    }
}

export class LanguageList extends React.Component{
    constructor(props) {
        super(props);

        this.languageRepository = repoFactory('language');
        this.learningUserRepository = repoFactory('learning-user');

        this.registerLanguage = this.registerLanguage.bind(this);

        this.state = {
            items: null,
            itemsData: null
        };
    }

    componentDidMount() {
        this._getLanguages();
    }

    registerLanguage(language) {
        const url = language.name + "/" + language.id;

        this._updateItems(language.id);
        this.learningUserRepository.registerLearningUser(language.id, $.proxy(function() {
            this.props.history.push(url);
        }, this));
    }

    _createItems(data) {
        this.setState((prevState) => {
            const languages = data.map((language, i) => {
                return <Item
                    key={i}
                    language={language}
                    history={this.props.history}
                    registerLanguage={this.registerLanguage}
                />;
            });

            store.dispatch(fetchAllLanguagesInProgress(false));
            store.dispatch(languagesFetched(data));

            prevState.items = languages;
            prevState.itemsData = data;
        });
    }

    _updateItems(languageId) {
        let data = this.state.itemsData;

        this.setState((prevState) => {
            const languages = data.map((language, i) => {
                if (language.id === languageId) {
                    language.alreadyLearning = true;

                    return <Item
                        key={i}
                        language={language}
                        isInsideRegistration={true}
                        history={this.props.history}
                        registerLanguage={this.registerLanguage}
                    />;
                }

                return <Item
                    key={i}
                    language={language}
                    isInsideRegistration={false}
                    history={this.props.history}
                    registerLanguage={this.registerLanguage}
                />;
            });

            store.dispatch(fetchAllLanguagesInProgress(false));
            store.dispatch(languagesFetched(data));

            prevState.items = languages;
            prevState.itemsData = data;
        });
    }

    _getLanguages() {
        store.dispatch(fetchAllLanguagesInProgress(true));

        this.languageRepository.getAllAlreadyLearning($.proxy(function(data) {
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

        return <div className="languages-wrapper">
                {items}
            </div>
    }
}