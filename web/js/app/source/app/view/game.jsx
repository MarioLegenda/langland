import React from "react";
import {env} from "../../../../global/constants";
import { Link } from "react-router-dom";

const PresentationStatistics = (props) => {
    return <div>
        {props.item.has_completed === 0 && <p>You haven't completed this game yet.</p>}
    </div>
};

const GamePresentationItem = (props) => {
    return <div className="animated fadeIn fadeOut presentation-item">
        <h1>{props.item.name}</h1>

        <PresentationStatistics item={props.item}/>

        <Link to={props.url} className="learn-button">Play <i className="learn-button-icon fa fa-angle-right"></i></Link>
    </div>;
};

export class Game extends React.Component {
    constructor(props) {
        super(props);

        this.enterGame = this.enterGame.bind(this);
    }

    _createPresentationItem(item) {
        console.log(item);
        const url = env.current + `langland/game/${item.id}`;

        return <GamePresentationItem item={item} url={url} />
    }

    enterGame(e) {
        e.preventDefault();

        const presentationItem = this._createPresentationItem(this.props.item);

        this.props.displayPresentationItem(presentationItem);
    }

    render() {
        const item = this.props.item;

        return <div className="lesson-absolute-item-holder">
            <div className="available-lesson-menu-item">
                <button className="available-circle-wrapper" onClick={this.enterGame}>
                    <span className="circle-wrapper-position lesson-text-wrapper">{item.name}</span>
                </button>
            </div>
        </div>
    }
}