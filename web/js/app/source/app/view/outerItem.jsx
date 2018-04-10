import React from "react";

import {InnerItem} from "./innerItem.jsx";

export class OuterItem extends React.Component {
    constructor(props) {
        super(props);
    }

    _buildItems(items) {
        const type = this.props.type;

        return items.map((item, key) => {
            return <InnerItem key={key} item={item} type={type}/>
        });
    }

    render() {
        const items = this._buildItems(this.props.items);

        return <div>
            {items}
        </div>
    }
}