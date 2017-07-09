import React from 'react';
import {envr} from './env.js';
import {routes, RouteCreator} from './routes.js';

class LanguageList extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            pendingTitle: null,
            itemId: null
        };

        this.isInUserCreation = false;

        this.createLearningUser = this.createLearningUser.bind(this);
    }

    createLearningUser(e) {
        e.preventDefault();

        if (this.isInUserCreation === true) {
            return;
        }

        if (this.isInUserCreation === false) {
            this.isInUserCreation = true;
        }

        const target = e.currentTarget;

        this.setState({
            pendingTitle: 'Setting up...',
            itemId: target.getAttribute('data-item-id')
        });

        this.props.DataSource.createLearningUser(target.getAttribute('data-item-id'))
            .done(function(data, content, response) {
                if (response.status === 201) {
                    window.location.href = response.getResponseHeader('Location');
                }

                if (response.status === 400) {
                    console.log('app_create_learning_user: ' + response.status);
                }

                if (response.status === 204) {
                    console.log('app_create_learning_user: ' + response.status);
                }
            });
    }

    render() {
        const that = this;

        const items = this.props.items.map(function(item) {
            let title = (item.isLearning === true) ? 'Continue' : 'Learn ' + item.name;
            let learningClass = '';

            if (item.id == that.state.itemId) {
                title = that.state.pendingTitle;
            }

            if (item.isLearning === true) {
                learningClass = 'item-started';
            }

            return (
                <div key={item.id} className="animated fadeInDown item">
                    <div className="title-holder margin-bottom-30">
                        <h2 className="item-name">{item.name}</h2>

                        {
                            item.image &&
                            <img src={item.image.fullPath} /> ||
                            !item.image &&
                            <img width="50" height="50" />
                        }
                    </div>

                    <p className="margin-bottom-30">{item.listDescription}</p>

                    <div className="start-link">
                        <a
                            className={learningClass}
                            onClick={that.createLearningUser}
                            data-item-id={item.id}
                            href={RouteCreator.create('app_page_course_dashboard', [item.name, item.id])}>
                            {title}
                        </a>
                    </div>
                </div>
            )
        });

        return (
            <div className="component list">
                {items}
            </div>
        )
    }
}

export class LanguageListContainer extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            items: []
        }
    }

    _fetchViewableLanguages() {
        this.props.DataSource.fetchViewableLanguages()
            .done(jQuery.proxy(function(data, content, response) {
                if (response.status === 200) {
                    this.setState({
                        items: data
                    });
                }
            }, this));
    }

    componentDidMount() {
        this._fetchViewableLanguages();
    }

    render() {
        const items = this.state.items;

        return (
            <LanguageList items = {items} DataSource={this.props.DataSource}/>
        )
    }
}