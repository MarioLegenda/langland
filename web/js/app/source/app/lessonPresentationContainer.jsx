import {factory} from "../repository/factory";

import {LessonItems} from "./view/lessonItems.jsx";

import React from "react";

export class LessonPresentationContainer extends React.Component {
    constructor(props) {
        super(props);

        this.metadataPresentationRepository = factory('metadata-presentation');

        this.state = {
            items: null
        }
    }

    componentDidMount() {
        this.metadataPresentationRepository.getLearningLessonPresentation($.proxy(function(data) {
            this.setState((prevState) => {
                prevState.items = data.collection;
            });
        }, this), $.proxy(function() {

        }, this));
    }

    render() {
        const items = this.state.items;

        if (items === null) {
            return null;
        }


        return <div>
            <LessonItems items={items}/>
        </div>
    }
}