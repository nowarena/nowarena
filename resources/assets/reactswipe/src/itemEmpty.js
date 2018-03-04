import React, { Component } from 'react';

class ItemEmpty extends Component {

  render() {

    var endOfFeedVisibilityStyle={display: 'none'};
    if (this.props.endOfFeedVisibility == true) {
      endOfFeedVisibilityStyle={display: 'block'};
    }

    var slideStyle={};
    if (this.props.view == 'items') {
      //slideStyle={backgroundColor:'#cccccc'};
    }

    return (
      <div>
        <div className="item" style={endOfFeedVisibilityStyle}>
          <div className="itemCont">
            <div className="itemHeader" style={slideStyle}>
              <div className="itemHeaderUsername">End of feed</div>
              <div className="clearBoth"></div>
            </div>
            <div className="clearBoth"></div>
            <div className="itemBody">
              <div className="itemTextContEmpty"><br /><br />
                <button className="navBtn" type="button" onClick={() => this.props.gotoTop()}>Return to Top of Feed</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    );
  }

}

export default ItemEmpty;