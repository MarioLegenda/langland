import {factory} from "../repository/factory";

import {OuterItem} from "./view/outerItem.jsx";
import {Game} from "./view/game.jsx";

import React from "react";

export class GamesPresentationContainer extends React.Component {
    constructor(props) {
        super(props);

        this.metadataPresentationRepository = factory('metadata-presentation');

        this.state = {
            items: null
        }
    }

    componentDidMount() {
        this.metadataPresentationRepository.getLearningGamesPresentation($.proxy(function(data) {
            this.setState((prevState) => {
                prevState.items = data.collection;
            });
        }, this), $.proxy(function() {

        }, this));
    }

    render() {
        let items = this.state.items;

        if (items === null) {
            return null;
        }

        items = items.data.blocks.courses;

        return <div className="menu-content animated fadeIn">
            <OuterItem items={items} type="game"/>
        </div>
    }
}