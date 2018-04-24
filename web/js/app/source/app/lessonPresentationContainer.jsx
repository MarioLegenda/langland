import React from "react";

import {factory} from "../repository/factory";

import {OuterItem} from "./view/outerItem.jsx";

import {
    store,
    handleMenuHeight
} from "../events/events";

export class LessonPresentationContainer extends React.Component {
    constructor(props) {
        super(props);

        this.metadataPresentationRepository = factory('metadata-presentation', true);

        this.state = {
            items: null
        }
    }

    _handleMenuHeight() {
        const courseList = $('.course-list');

        let h = 0;
        courseList.each(function(index, item) {
            h += item.offsetHeight;
        });

        store.dispatch(handleMenuHeight(h));
    }

    componentDidMount() {
        this.metadataPresentationRepository.getLearningLessonPresentation($.proxy(function(data) {
            this.setState({
                items: data.collection
            });

            this._handleMenuHeight();

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
            <OuterItem items={items} type="lesson"/>
        </div>
    }
}