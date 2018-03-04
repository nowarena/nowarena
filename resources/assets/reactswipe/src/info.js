import React, { Component } from 'react';
import Item from './item'

class Info extends Component {

  render() {

    if (typeof this.props.itemObj == 'undefined') {
      return null;
    }

    var itemObj = this.props.itemObj;
    var mapIcon = '';

    if (typeof itemObj.lat != 'undefined') {
      mapIcon = '<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAAVvSURBVGhD7ZfpTxxlHMeJL/wTtAaKphqpDTUWZrkKu5y2CvWqHNUibdrE+IJGmza1kKb2iMGSaCQFl6qA1hSBSoLQg1WupSy0u7Ase7GVGixnWig34Wj7c57pM+vM7jPD7LK78oJv8slkn3lm8/3wzDyz+K1nPc4xbQ8NtMplh6xyqs6ikPVY5NQgOtKfa9G4MYbaiKeuzZgUsg10YSVdeNmqkIEgT84X9sSHP4svXTuxxYSG0RJDTqVFQCtFHyn8Ff9/zLEh4XShOW5JF5jrlVMy/FXSsuGHD573L04v8Fem1Qco0//wBCFf727pSIpYIBSUDL0yA32Rkc/gmuJ5rjg10F+ZPhFQnA6eJP9gArGcGxTiquIJUKZ9Syry6k8HIaf1Ryi3NkKVrQXybpZDVHm20zwS277ZDaZYYik79HMDXTGhzJF0nsPSbUVYAK4rHPo2aHQs8llTEcwszoNjlh89hHxtBW8uiezDO0mFGMxyCipCgyF384tw9OVNkBv0ElRSwcw4aT4mG9cVDn1btXJLHGo8j2sDzCzNQ/NAN1z5uwPuz0/B0qNl2H/9HK80ibI9ClIZBiSBBBxBMqT5CHrVanBd4XBFgssOwNTiLCOhG7UB9csn9nJBJVmQXJ1j/yxG3dvbhQrZV8KR40GbwOww336dnOrGdYXDFTnaUsxIzC8vgIwj4SrqNyKJhdAzQZJg0UWHEq9DuxeuKxyuSInxGiOiGTLzilnH/4HJhVkeBV3VvDlcVClR5EI07q2IzIrrCocr8rNZxYioB3p4xQZn7jPj3JSZ6nlzuHy/N5ZYCIGeBZJIZehW4nwELVKF6wqHK3KirZQpOb04xzwv7Pi7NSdhT91Z+OhaHszRtx3KmfaL9vOOZH2eTCyEQLsTkskJerIy6IgkxHYti4LKwHWFwxXZdvFj5vlAUfXrYHPJPnu5jcUZUGyoZc6hJFQdsZ9z5IWiNNDsiCCWYkG3kTY6RPB24jBs2rLlaVxXOFwRxGn6L81maGYMLlkbQEkLmMf68ShA3Z0OXnESx44Iv0tcZOV3CIqjCOKrW78yLz9SWgeN8Erpft58R7aWpIHpVjz07wsjFXMBSgN+fk/hquIhiSBiKw5Dob6GefDbhkxQ0dvEvAwDL2Q4zeWCJIzaeABrDCw2RoMtiVRQAnLZrDWOCsI1V46QiDtwJVgmS8kvx5XoVVB7cUVp8ZQISYLl7rF4Ylkhbnx6YAnXkx5PiIhJzJkzQN9eD+b3U4ilHTGn7oLKP3+bxvWkZ7UiK0p0NoFWdxM6ay6DNV58S7YmREHX79VQoa6dwvWkZzUiohKW/yRYDF8cJwtgek7lMvN8KiIm8bAvC+6N/AVdeh1PRNumBvNbiUQJyzs7QKtR+1ZkJYnJ8bswMTEBo6OjTjL67wqIImicneMTEakSLE4yHW1gSYnjSVh2JTLjPhMRk+jryAKDjS8hJGM4c4InYvjypP2c10XEJG5rsiDl9CAkn52CTpuziKNMZ0M9WGPxz5e4cNA1qHwjIkVCnjvFIFXG/OF7jIg5M5Un4TURVyRckWG3YnbL9aqIOxIsK8kYSi8wIvoSpXdFViPBIiYzbOhmRDpVV70n4gkJFjGZgfqr/K3ZkyKelGARv81GnGRWLeINCRZXZFYl4k0JFjGZEY6M2yK+kGCRIuOWyGslqe2+kmARkxkaGYZqzXXX/7Fq0STc86UEi5iM9o5xBteTHtWNRHpD960Ei5BMtbFZj+tJT5FqZ8REj+IxK2HTZPpEggXJ6Gxjdon+0cHH+epL4bieazlf92a4Sp2gb27KnH49T/Mg7FT3uC9JOmd6cKXbNo1Wwm2J9axnPetZg/Hz+xcJzHInL8F4FAAAAABJRU5ErkJggg==">';
      mapIcon = '<a target="_blank" href="https://www.google.com/maps/?q=' + encodeURIComponent(this.props.lat + ',' + this.props.lon) + '">' + mapIcon + '</a>';
    } else {

    }

    return (
      <div>
        <div className="infoIcon">i</div>
        <div className="clearBoth"></div>
        <div className="mapIcon" dangerouslySetInnerHTML={{__html:mapIcon}}/>
      </div>
    );

  }


}

export default Info;