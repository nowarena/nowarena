import React, { Component } from 'react';
import InfoBtns from '../src/infobtns';
import Info from '../src/info';

class Item extends Component {

  render() {

    var socialMediaObj = this.props.socialMediaObj;
    var itemObj = this.props.itemObj;

    var text = '';
    var username = '';
    var usernameLink = '';
    var created_at = '';
    var avatar = '';
    var outboundLink = '';
    var visibilityStyle={display: 'none'};

    if (socialMediaObj != '') {
      visibilityStyle={display: 'block'};
      text = socialMediaObj.text;
      username = socialMediaObj.username;
      if (socialMediaObj.site != 'yelp.com') {
        usernameLink = '<a target="_blank" class="itemHeaderUsernameLink" href="https://' + socialMediaObj.site + '/' + username + '">' + itemObj.title + '</a>';
      } else {
        usernameLink = '<a target="_blank" class="itemHeaderUsernameLink" href="https://www.yelp.com/biz/' + username + '">' + itemObj.title + '</a>';
      }
      if (socialMediaObj.created_at !== null) {
        //created_at = socialMediaObj.created_at.replace('2018-','');
      }
      if (this.props.itemObj.avatar != '') {
        avatar = '<a target=_blank href="' + socialMediaObj.link + '"><img class="avatarImg" src="' + itemObj.avatar + '"/></a>';
      }
      outboundLink = '<a class="outboundLink" target=_blank href="' + socialMediaObj.link + '">&#8599;</a>';

    }

    var info = '';
    var lat = '';
    var lon = '';
    var day = '';
    if (typeof itemObj != 'undefined') {

      switch (new Date().getDay()) {
        case 0:
          day = "Sun";
          break;
        case 1:
          day = "Mon";
          break;
        case 2:
          day = "Tue";
          break;
        case 3:
          day = "Wed";
          break;
        case 4:
          day = "Thu";
          break;
        case 5:
          day = "Fri";
          break;
        case 6:
          day = "Sat";
      }

      if (typeof itemObj.lat != 'undefined') {
        lat = itemObj.lat;
      }
      if (typeof itemObj.lon != 'undefined') {
        lon = itemObj.lon;
      }
      if (typeof itemObj.address != 'undefined' && itemObj.address != '') {
        info+=itemObj.address;
        info+="<br />";
      }
      if (typeof itemObj.hours != 'undefined' && itemObj.hours != '') {
        //info = (info != '') ? info + "<br />" : info;
        var hours = '';
        for (var i in itemObj.hours) {
          if (itemObj.hours[i].includes(day)) {
            hours+= ' ' + itemObj.hours[i] + "<br />";
          }
        }
        info = info + "<div class='hoursCont'>" + hours + "</div>";
      }
      if (typeof itemObj.phone != 'undefined' && itemObj.phone != '') {
        info+= itemObj.phone;
        info+="<br />";
      }
      if (typeof itemObj.website != 'undefined' && itemObj.website != null) {
        //info = (info != '') ? info + "<br /><br />" : info;
        var websiteDisplay = itemObj.website.replace("http://", "");
        info+= "<a target='_blank' href='" + itemObj.website + "'>" + websiteDisplay + "</a>";
      }
      var hasInfo = 0;
      if (info != ''){
        hasInfo = 1;
      }
    }

    var outboundLinkVisibility = {display:'none'};
    if (this.props.displayInfo == 1) {
      var infoVisibility = {display:'block'};
      var itemTextVisibility = {display:'none'};
      //var outboundLinkVisibility = {display:'none'};
    } else {
      var itemTextVisibility = {display:'block'};
      //var outboundLinkVisibility = {display:'block'};
      var infoVisibility = {display:'none'};
    }

    var slideStyle={};
    if (this.props.view == 'items') {
      //slideStyle={backgroundColor:'#ddd'};
    }

    return (
      <div>
        <div className="item" style={visibilityStyle}>
          <div className="itemCont">
            <div className="itemHeader" style={slideStyle}>
              <div className="itemAvatarCont" dangerouslySetInnerHTML={{__html:avatar}}/>
              <div className="itemHeaderUsername" dangerouslySetInnerHTML={{__html:usernameLink}}/>
              <div className="itemHeaderCreatedAt" dangerouslySetInnerHTML={{__html:created_at}}/>
              <InfoBtns
                hasInfo={hasInfo}
                displayInfo={this.props.displayInfo}
                toggleDisplayInfo={() => this.props.toggleDisplayInfo(this.props.displayInfo)}
                itemObj={itemObj}></InfoBtns>
              <div className="clearBoth"></div>
            </div>
            <div className="clearBoth"></div>
            <div className="itemBody">
              <div style={outboundLinkVisibility} className="outboundLinkCont" dangerouslySetInnerHTML={{__html:outboundLink}}/>
              <div style={itemTextVisibility} className="itemTextCont" dangerouslySetInnerHTML={{__html:text}}/>
              <div style={infoVisibility} className="itemTextCont infoCont" dangerouslySetInnerHTML={{__html:info}}/>
            </div>
         </div>
        </div>
      </div>
    );
  }

}

export default Item;