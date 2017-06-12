import React from 'react';

export const ListingItem = (props) => {
    const chooseItem = null;

    if (props.hasOwnProperty('chooseItem')) {
        const chooseItem = props.chooseItem;
    }

    return <div onClick={props.chooseItem} data-item-index={props.index} className={"item " + props.className}>
        <div>
            <h1>{props.title}</h1>
        </div>

        {props.hasPassed === true &&
        <i className="fa fa-check"></i>
        }

        {props.hasPassed === false && props.hasOwnProperty('isEligable') && props.isEligable === false &&
        <i className="fa fa-lock"></i>
        }
    </div>
};