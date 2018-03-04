import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import querystring from 'querystring';
import ReactSwipe from '../src/reactSwipe';
import ItemEmpty from '../src/itemEmpty';
import Item from '../src/item';
import Lockbtn from '../src/lockbtn';

var newspeopleJson = require('../json/newspeople.json');
var shoppingJson = require('../json/shopping.json');
var fashionJson = require('../json/fashion.json');
//var otherJson = require('../json/other.json');
var diningJson = require('../json/dining.json');
//var servicesJson = require('../json/services.json');
var casualeatscoffeeJson = require('../json/casualeatscoffee.json');
var healthbeautyJson = require('../json/healthbeauty.json');
//var techJson = require('../json/tech.json');

const query = querystring.parse(window.location.search.slice(1));

// change Swipe.js options by query params
//const startSlide = parseInt(query.startSlide, 10) || 0;

class Page extends React.Component {

  constructor(props) {
    super(props);
    this.state = {view: 'cats', itemsId:0, displayInfo:0};
    this.setView = this.setView.bind(this);
    this.lastCatsPos = 0;
    this.initSlide = 0;
    this.toggleDisplayInfo = this.toggleDisplayInfo.bind(this);
  }

  componentDidMount() {
    if (this.initSlide == 0 && typeof this.props.firstRow != 'undefined' && this.props.firstRow == 1) {
      this.initSlide = 1;
      setTimeout(() => this.prev(), 500);
    }
    console.log("initSlide", this.initSlide);
  }

  gotoTop () {
    do {
      this.prev();
    } while (this.reactSwipe.getPos() > 0);
  }

  next () {
    this.reactSwipe.next();
  }

  prev () {
    this.reactSwipe.prev();
  }

  getPos() {
     return this.reactSwipe.getPos();
  }

  // This manages the view, wither 'cats' or 'items'. If the view is 'items', then all the slides for that item is display,
  // otherwise if the view is 'cats', the items in the category get their most recent social media displayed in the slide
  setView(currentView, itemsId) {
    if (currentView == 'cats') {
      this.lastCatsPos = this.getPos();
    }

    var viewVal = (currentView == 'items') ? 'cats' : 'items';
    this.setState({
      view: viewVal,
      itemsId: itemsId
    }, () => this.afterSetViewSetStateFinished(viewVal));


  }

  afterSetViewSetStateFinished(viewVal) {

    if (viewVal == 'items') {
      // they've clicked on lock swipe, jump to the next slide for that item
      setTimeout(() => this.next(), 100);
    } else {
      // they've unlocked the the lock swipe, return them to the last item they viewed, don't go to the top
      while (this.getPos() < this.lastCatsPos) {
        this.next();
      } ;
      setTimeout(() => this.next(), 400);
    }
  }

  toggleDisplayInfo(displayInfo) {

    if (displayInfo == 0) {
      this.setState({
        displayInfo:1
      });
    } else {
      this.setState({
        displayInfo:0
      });
    }

  }

  render () {

    var numberOfSlides = Object.keys(this.props.feed).length + 1;// + 1 because ItemEmpty will be the +1
    if (this.state.view == 'items') {
      numberOfSlides = this.props.feed[this.state.itemsId].social_media.length + 1;// + 1 because ItemEmpty will be the +1
    }

    // each 'items' has a rank value in the json, but the rank doesn't correlate to the order in the json.
    // create an array to map items_id and their most recent social media date to position in this.props.feed array
    var itemsRankArr = [];
    for(var itemsId in this.props.feed) {
      var rank = this.props.feed[itemsId].rank;
      itemsRankArr[rank] = itemsId;
    }

    const paneNodes = Array.apply(null, Array(numberOfSlides)).map((_, i) => {

      if (this.state.view == 'cats') {
          itemsId = itemsRankArr[i];
      } else {
        itemsId = this.state.itemsId;
      }

      var endOfFeedVisibility=true;
      var socialMediaObj = [];
      if (this.state.view == 'cats'
         && typeof this.props.feed[itemsId] != 'undefined'
         && typeof this.props.feed[itemsId].social_media != 'undefined'
         && typeof this.props.feed[itemsId].social_media[0] != 'undefined') {
          // this gets the most recent social_media item for each member of this category
          socialMediaObj = this.props.feed[itemsId].social_media[0];
          endOfFeedVisibility=false;
          var itemObj = this.props.feed[itemsId];
      } else if (this.state.view == 'items'
         && typeof this.props.feed[itemsId] != 'undefined'
         && typeof this.props.feed[itemsId].social_media != 'undefined'
         && typeof this.props.feed[itemsId].social_media[i] != 'undefined') {
          // this gets the social media for an item based on position i of social media being browsed
          socialMediaObj = this.props.feed[itemsId].social_media[i];
          endOfFeedVisibility=false;
          var itemObj = this.props.feed[itemsId];
      }

      return (
        <div key={i}>
          <Item
            displayInfo={this.state.displayInfo}
            toggleDisplayInfo={() => this.toggleDisplayInfo(this.state.displayInfo)}
            view={this.state.view}
            socialMediaObj={socialMediaObj}
            itemObj={itemObj}></Item>
          <Lockbtn
            setView={this.setView}
            itemsId={itemsId}
            view={this.state.view}
          ></Lockbtn>
          <ItemEmpty view={this.state.view} gotoTop={() => this.gotoTop()} endOfFeedVisibility={endOfFeedVisibility}></ItemEmpty>
        </div>
      );

    });

    const startSlide = (typeof this.props.firstRow != 'undefined' && this.props.firstRow == 1) ? 1 : 0;

    const swipeOptions = {
      startSlide: startSlide < paneNodes.length && startSlide >= 0 ? startSlide : 0,
      auto: parseInt(query.auto, 10) || 0,
      speed: parseInt(query.speed, 10) || 300,
      disableScroll: query.disableScroll === 'true',
      continuous: query.continuous === 'true',
      callback () {
        //console.log('slide changed');
      },
      transitionEnd () {
        //console.log('ended transition');
      }
    };

    //
    // The last itemsId when viewing 'cats' will always be undefined because the paneNodes array creation sets that undefined value
    // last in the loop.
    // itemsId gets set when viewing items
    return (

      <div key={itemsId} className="center">
        <div className='parentCont'>
          <div className='parentTitleCont'>
            <div className='parentTitle'>{this.props.title}</div>
            <div className='refreshIcon' onClick={() => this.gotoTop()}>&#x21ba;</div>
          </div>
        </div>
        <div className='clearBoth'></div>
        <ReactSwipe
          ref={reactSwipe => this.reactSwipe = reactSwipe}
          className="mySwipe"
          swipeOptions={swipeOptions}>
          {paneNodes}
        </ReactSwipe>

        <div className="navBtnCont">
          <button className="navBtn prevBtn" type="button" onClick={::this.prev}>&laquo;</button>
          <button className="navBtn nextBtn" type="button" onClick={::this.next}>&raquo;</button>
        </div>

      </div>
    );

  }
}

// ReactDOM.render(
//   <Page title='Tech' feed={techJson} />,
//   document.getElementById('tech')
// );


ReactDOM.render(
  <Page title='Dining' firstRow={1} feed={diningJson} />,
  document.getElementById('dining')
);

ReactDOM.render(
  <Page title='Fashion' feed={fashionJson} />,
  document.getElementById('fashion')
);

ReactDOM.render(
  <Page title='Shopping' feed={shoppingJson} />,
  document.getElementById('shopping')
);

ReactDOM.render(
  <Page title='News & People' feed={newspeopleJson} />,
  document.getElementById('newspeople')
);


ReactDOM.render(
  <Page title='Casual Eats & Coffee' feed={casualeatscoffeeJson} />,
  document.getElementById('casualeatscoffee')
);

ReactDOM.render(
  <Page title='Health & Beauty' feed={healthbeautyJson} />,
  document.getElementById('healthbeauty')
);


// ReactDOM.render(
//   <Page title='Other' feed={otherJson} />,
//   document.getElementById('other')
// );
//
// ReactDOM.render(
//   <Page title='Services' feed={servicesJson} />,
//   document.getElementById('services')
// );