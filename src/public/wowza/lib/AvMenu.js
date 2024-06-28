/*
 * This code and all components (c) Copyright 2019-2020, Wowza Media Systems, LLC. All rights reserved.
 * This code is licensed pursuant to the BSD 3-Clause License.
 */

var browserName;

(function() {
    function getBrowserName() {
        var userAgent = navigator.userAgent;
        if (userAgent.indexOf("Firefox") > -1) {
            return "firefox";
        } else if (userAgent.indexOf("Opera") > -1 || userAgent.indexOf("OPR") > -1) {
            return "opera";
        } else if (userAgent.indexOf("Trident") > -1) {
            return "Internet Explorer";
        } else if (userAgent.indexOf("Edge") > -1) {
            return "edge";
        } else if (userAgent.indexOf("Chrome") > -1) {
            return "chrome";
        } else if (userAgent.indexOf("Safari") > -1) {
            return "safari";
        } else {
            return "Unknown";
        }
    }

    // Set the global variable
    browserName = getBrowserName();

    // Log the browser name to the console
    console.log("Browser: " + browserName);
})();

const browserDetails = browserName;

let selectedMic = "";
let selectedCam = "";
let selectedMobileCam = "";

const canScreenShare = () => {
  if (browserDetails.browser === 'safari') {
    return false;
  }
  if (browserDetails.browser === 'firefox') {
    return false;
  }
  if (/mobi|android/i.test(navigator.userAgent.toLowerCase())) {
    return false;
  }
  return true;
}

const init = (cameras,mics,camCallback,micCallback) => {

  if (canScreenShare())
  {
    cameras.push({deviceId:'screen_screen',kind:'videoinput',label:'Screen Share'});
  }

  $("#show-cameras-menu").popover({
    content: deviceListDesktopHTML(cameras, "Camera"),
    html:true,
    trigger: "click",
    placement: "right"
  });

  $("#show-mics-menu").popover({
    content: deviceListDesktopHTML(mics, "Microphone"),
    html:true,
    trigger: "click",
    placement: "left"
  });

  $("#settings-button-large").click(() => {
    $("#show-mics-menu, #show-cameras-menu").popover('hide');
  });

  // console.log(selectedCam);

  $("#camera-list-select").append(deviceListSelectHTML(cameras,"Camera"));
  $("#camera-list-select").change(() => {
    let camera = $("#camera-list-select option:selected").attr("id").split("_")[1];
    camCallback(camera);
  });

  $("#mic-list-select").append(deviceListSelectHTML(mics,"Microphone"));
  $("#mic-list-select").change(() => {
    let mic = $("#mic-list-select option:selected").attr("id").split("_")[1];
    micCallback(mic);
  });

  // the click listeners for desktop need to be attached when the menu is shown
  $("#show-cameras-menu").on('inserted.bs.popover', () => {
    if(selectedCam) {$(`#${selectedCam}`).addClass("font-weight-bold");};
    if(selectedMobileCam) {
      $("#camera-list-select option").attr("selected",false)
      $(`#${selectedMobileCam}`).attr("selected", true);
    };
    attachOnClickListeners(cameras,camCallback, "Camera");
  });
  // the click listeners for desktop need to be attached when the menu is shown
  $("#show-mics-menu").on('inserted.bs.popover',  () => {
    if(selectedMic) {$(`#${selectedMic}`).addClass("font-weight-bold");};
    attachOnClickListeners(mics,micCallback, "Microphone");
  });
}

const deviceListDesktopHTML = (devices,label) => {
  let deviceList = ""
  for(var i = 0; i < devices.length; i++){
    let deviceId = `${label}_${devices[i].deviceId}`;
    if(i==0) {setSelectedDevice(deviceId,label);};
    let deviceLabel = devices[i].label || `${label}_${i}`;
    deviceList = deviceList + `<p id="${deviceId}" class="device-list-item">${deviceLabel}</p>`
  }
  return deviceList;
}

const deviceListSelectHTML = (devices,label) => {
  let deviceList = []
  for(var i = 0; i < devices.length; i++){
    let deviceId = `${label}Mobile_${devices[i].deviceId}`;
    if(i==0) { selectedMobileCam = deviceId;};
    let deviceLabel = devices[i].label || `${label}_${i}`;
    let element = $('<option>').attr("id", deviceId).attr("value", deviceId).text(deviceLabel);
    deviceList.push(element);
  }
  return deviceList;
}

// pass label here too so we can mark selected guy
const attachOnClickListeners = (devices, callback,label) => {
  for(var i = 0; i < devices.length; i++){
    let deviceId = `${label}_${devices[i].deviceId}`
    $(`#${deviceId}`).click(() => {
      callback(deviceId.split("_")[1]);
      setSelectedDevice(deviceId,label);
      $("#show-mics-menu, #show-cameras-menu").popover('hide'); //stupid hack to make popovers work in safari because "focus" doesn't
      $(".device-list-item").remove(); //listener garbage collection here
    });
  }
}

const setSelectedDevice = (id,label) => {
  if(label == "Camera"){
    selectedCam = id;
  }else {
    selectedMic = id;
  }
}

let AvMenu = {
  init: init
}

export default AvMenu;
