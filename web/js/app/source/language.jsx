import React from 'react';
import {factory as repoFactory} from "./repository/factory.js";
import {Link} from 'react-router-dom';

class Item extends React.Component {
    constructor(props) {
        super(props);

        this.registerLanguage = this.registerLanguage.bind(this);

        this.learningUserRepository = repoFactory('learning-user');
    }

    registerLanguage() {
        const language = this.props.language;

        this.learningUserRepository.registerLearningUser(language.id);
    }

    render() {
        const language = this.props.language,
              alreadyLearning = language.alreadyLearning,
              alreadyLearningClass = (alreadyLearning) ? 'already-learning': '',
              alreadyLearningButtonText = (alreadyLearning) ? 'Continue': 'Start learning',
              url = language.name + "/" + language.id;

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
                    <Link className="language-link" onClick={this.registerLanguage} to={url}>{alreadyLearningButtonText}</Link>
                </div>
            </div>
    }
}

export class LanguageList extends React.Component{
    constructor(props) {
        super(props);

        this.languageRepository = repoFactory('language');

        this.state = {
            items: null
        };
    }

    _processLanguageData(data) {
        let languages = [];

        for (let i = 0; i < data.length; i++) {
            const lang = data[i];
            const images = lang.images;
            const language = {
                id: parseInt(lang.id),
                name: lang.name,
                desc: lang.desc,
                images: {
                    cover: images.cover_image.relativePath + '/' + images.cover_image.originalName,
                    icon: images.icon.relativePath + '/' + images.icon.originalName
                },
                alreadyLearning: lang.alreadyLearning
            };

            languages.push(<Item key={i} language={language}/>)
        }

        return languages;
    }

    componentDidMount() {
        this.languageRepository.getAllAlreadyLearning($.proxy(function(data) {
            this.setState(function(prevState) {
                prevState.items = this._processLanguageData(data);
            });
        }, this), $.proxy(function(data) {
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