import React, { Component } from 'react';

class Lockbtn extends Component {

  render() {

    var btnText = '&#128274;';
    var btnBgColor = {};
    if (this.props.view == 'items') { //viewing all items of a biz, not most recent item from each biz in category
      var btnBgColor = {backgroundColor:'rgb(190, 190, 190)'};
    }

    // Don't display lock swipe if at end aka 'goto top of feed' aka undefined itemsId
    var visibilityStyle = {display:'block'};
    if (typeof this.props.itemsId == 'undefined') {
      visibilityStyle = {display: 'none'};
    }

    return (
      <div key={this.props.view}>
        <div style={visibilityStyle} className="lockBtnCont">
          <button
            onClick={() => this.props.setView(this.props.view, this.props.itemsId)}
            className="lockBtn"
            style={btnBgColor}
            type="button" dangerouslySetInnerHTML={{__html:btnText}}></button>
        </div>
      </div>
    );
  }

}

export default Lockbtn;