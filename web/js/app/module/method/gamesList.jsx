import React from 'react';
import {RouteCreator} from './../routes.js';

import {ListingItem} from './listingItem.jsx';

class GamesList extends React.Component {
    constructor(props) {
        super(props);

        this.chooseGame = this.chooseGame.bind(this);
    }

    chooseGame(e) {
        e.preventDefault();

        const itemIndex = e.currentTarget.getAttribute('data-item-index');
        const item = this.props.items[itemIndex];
    }

    render() {
        console.log(this.props.items);
        const items = this.props.items.map((item, index) => {
            const passedClass = (item.hasPassed === true) ? 'passed-item' : '';

            return <div key={index}>
                <ListingItem
                    chooseItem={this.chooseGame}
                    index={index}
                    className={passedClass}
                    title={item.game.name.toUpperCase()}
                    hasPassed={item.hasPassed}
                />
            </div>
        });

        return (
            <div>
                {items}
            </div>
        )
    }
}

export class GameListContainer extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            items: null
        };
    }

    _fetchGamesList() {
        this.props.DataSource.fetchGamesList(this.props.learningUserCourseId)
            .done(jQuery.proxy(function(data) {
                this.setState({
                    items: data.data
                });
            }, this));
    }

    componentDidMount() {
        this._fetchGamesList();
    }

    render() {
        const items = this.state.items;

        if (items === null) {
            return null;
        }

        return (
            <div className="animated fadeInDown item-list working-area">
                <GamesList items={items}/>
            </div>
        )
    }
}