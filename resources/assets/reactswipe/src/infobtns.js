import React, { Component } from 'react';

class InfoBtns extends Component {

  render() {

    if (typeof this.props.itemObj == 'undefined' || this.props.hasInfo == 0) {
      return null;
    }

    var itemObj = this.props.itemObj;
    var mapBtnVisibility ={display:'none'}
    if (typeof itemObj.lat != 'undefined') {
      mapBtnVisibility ={display:'block'}
    }
    var infoBtnBgColor = {};
    if (this.props.displayInfo) {
      infoBtnBgColor = {backgroundColor:'rgb(190, 190, 190)'};
    }

    var mapLink = '<a class="mapLink" target="_blank" href="https://www.google.com/maps/?q=' + encodeURIComponent(itemObj.lat + ',' + itemObj.lon) + '"><div  class="mapBtnInner">map</div></a>';

    return (
      <div>
        <div
          onClick={() => this.props.toggleDisplayInfo(this.props.displayInfo)}
          className="infoBtnCont"><button style={infoBtnBgColor} className="infoBtn">info</button></div>
        <div
          className="mapBtnCont"
          style={mapBtnVisibility}><button
          className="mapBtn"
          dangerouslySetInnerHTML={{__html:mapLink}}/></div>
      </div>
    );

  }


}

export default InfoBtns;