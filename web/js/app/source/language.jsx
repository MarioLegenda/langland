import React from 'react';
import {Link} from 'react-router-dom';

import {factory as repoFactory} from "./repository/factory.js";
import {store, fetAllLanguagesCompleted, fetchAllLanguagesInProgress, languagesFetched} from "./events/events.js";


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
              alreadyLearningButtonText = (alreadyLearning) ? 'Continue': 'Start learning';

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
                    <Link className="language-link" onClick={this.registerLanguage} to={""}>{alreadyLearningButtonText}</Link>
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
            items: null
        };
    }

    componentDidMount() {
        this._getLanguages();
    }

    registerLanguage(language) {

        const url = language.name + "/" + language.id;

        this.learningUserRepository.registerLearningUser(language.id, $.proxy(function() {
            this.props.history.push(url);
        }, this));
    }

    _createItems(data) {
        const languages = data.map((language, i) => {
            return <Item
                key={i}
                language={language}
                history={this.props.history}
                registerLanguage={this.registerLanguage}
            />;
        });

        console.log(store);

        store.dispatch(fetAllLanguagesCompleted());
        store.dispatch(languagesFetched(data));

        return languages;
    }

    _getLanguages() {
        store.dispatch(fetchAllLanguagesInProgress());

        this.languageRepository.getAllAlreadyLearning($.proxy(function(data) {
            this.setState(function(prevState) {
                prevState.items = this._createItems(data);
            });
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