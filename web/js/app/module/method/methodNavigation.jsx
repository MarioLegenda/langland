import React from 'react';
import { Link } from 'react-router-dom';
import {RouteCreator} from './../routes.js';

export class MethodNavigation extends React.Component {
    constructor(props) {
        super(props);

        this.handleMenuHighlight = this.handleMenuHighlight.bind(this);
    }

    _highlightMenuBasedOnRoutes() {
        if (/lessons/.test(window.location.pathname)) {
            this._highlightMenu(jQuery('.lesson-item'));
        } else {
            this._unHighlightMenu();
        }
    }

    componentDidMount() {
        this._highlightMenuBasedOnRoutes();
    }

    _unHighlightMenu() {
        jQuery('.nav-item').removeClass('position-nav-item');
        jQuery('.nav-item').find('.highlightable').removeClass('highlight-nav-item');
    }

    _highlightMenu(target) {
        this._unHighlightMenu();

        jQuery(target).addClass('position-nav-item');
        jQuery(target).find('.highlightable').addClass('highlight-nav-item');
    }

    handleMenuHighlight(e) {
        this._highlightMenu(e.currentTarget);
    }

    render() {
        this._highlightMenuBasedOnRoutes();

        const learningUserCourseId = this.props.learningUserCourseId;

        return (
            <div className="method-navigation">

                <div onClick={this.handleMenuHighlight} className="nav-item lesson-item">
                    <Link to={RouteCreator.create('app_page_lesson_list', [learningUserCourseId])}>
                        <i className="fa fa-mortar-board fa-2x highlightable"></i>
                        <span className="highlightable">Lessons</span>
                    </Link>
                </div>

                <div onClick={this.handleMenuHighlight} className="nav-item game-item">
                    <Link to="">
                        <i className="fa fa-gamepad fa-2x highlightable"></i>
                        <span className="highlightable">Games</span>
                    </Link>
                </div>

                <div onClick={this.handleMenuHighlight} className="nav-item vocabulary-item">
                    <Link to="">
                        <i className="fa fa-header fa-2x highlightable"></i>
                        <span className="highlightable">Vocabulary</span>
                    </Link>
                </div>

                <div onClick={this.handleMenuHighlight} className="nav-item vocabulary-item">
                    <Link to="">
                        <i className="fa fa-trophy fa-2x highlightable"></i>
                        <span className="highlightable">Trophies</span>
                    </Link>
                </div>
            </div>
        )
    }
}
