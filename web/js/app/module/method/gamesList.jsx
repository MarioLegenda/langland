import React from 'react';
import { Link } from 'react-router-dom';

import {RouteCreator} from './../routes.js';
import {Item} from './listingItem.jsx';

class GamesList extends React.Component {
    constructor(props) {
        super(props);

        this.showItem = this.showItem.bind(this);
    }

    showItem(e){
        e.preventDefault();

        const itemIndex = e.currentTarget.getAttribute('data-item-index');
        let item = this.props.items[itemIndex];

        if (typeof item === 'undefined') {
            item = null;
        }

        this.props.showItem(item);
    }

    render() {
        const items = this.props.items.map((item, index) => {
            const passedClass = (item.hasPassed === true) ? 'passed-item' : '';

            return (
				<div key={index}>
                	<Item
                    	chooseItem={this.showItem}
                    	index={index}
                    	className={passedClass}
                    	title={item.game.name.toUpperCase()}
                    	hasPassed={item.hasPassed}
                	/>
            	</div>
			);
        });

        return (
            <div>
                {items}
            </div>
        )
    }
}

class GameStart extends React.Component {
    constructor(props) {
        super(props);
    }

    render() {
        const item = this.props.item;

        if (item === null) {
            return null;
        }

        const
            courseName = this.props.courseName,
            learningUserCourseId = this.props.learningUserCourseId,
            gameUrl = item.game.url,
            gameId = item.game.id,
            buttonText = (item.hasPassed === true) ? 'Do again' : 'Start';

        return (
            <div>
                <div className="animated fadeInDown item-start-item margin-top-30">
                    <h1 className="full-width align-left margin-bottom-30">{item.game.name}</h1>

                    <p className="margin-bottom-30">{item.game.description}</p>

                    <div className="start-link margin-bottom-30">
                        <Link to={RouteCreator.create('app_initialize_selected_game', [courseName, learningUserCourseId, gameUrl, gameId])}>{buttonText}</Link>
                    </div>
                </div>
            </div>
        )
    }
}

export class GameListContainer extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            items: null,
            currentItem: null
        };

        this.showItem = this.showItem.bind(this);
    }

    _fetchGamesList() {
        this.props.DataSource.fetchGamesList(this.props.learningUserCourseId)
            .done(jQuery.proxy(function(data) {
                if (data.status === 'success') {
                    this.setState({
                        items: data.data
                    });
                }
            }, this));
    }

    showItem(item) {
        if (item !== null) {
            this.setState({
                currentItem: item
            });

            $("html, body").animate({ scrollTop: $(document).height() }, 3000);
        }
    }

    componentDidMount() {
        this._fetchGamesList();
    }

    render() {
        const items = this.state.items;
        const currentItem = this.state.currentItem;

        if (items === null) {
            return null;
        }

        return (
            <div className="animated fadeInDown item-list working-area">
                <GamesList items={items} showItem={this.showItem}/>
                <GameStart
                    item={currentItem}
                    courseName={this.props.courseName}
                    learningUserCourseId={this.props.learningUserCourseId}
                />
            </div>
        )
    }
}
