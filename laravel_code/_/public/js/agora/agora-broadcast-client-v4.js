/**
 * Agora Broadcast Client
 */

 var agoraAppId = appIdAgora; // set app id
 var channelName = agorachannelName; // set channel name

// create client instance
const client = AgoraRTC.createClient({mode: 'live', codec: 'vp8'}); // h264 better detail at a higher motion
var mainStreamId; // reference to main stream

// set video profile
// [full list: https://docs.agora.io/en/Interactive%20Broadcast/videoProfile_web?platform=Web#video-profile-table]
var cameraVideoProfile = '1080p_5'; // 960 × 720 @ 30fps  & 750kbs

var localTracks = {
    videoTrack: null,
    audioTrack: null
};

var localTrackState = {
  videoTrackEnabled: true,
  audioTrackEnabled: true
}

var remoteUsers = {};
// keep track of streams
var options = {
  uid: null,
  appid: appIdAgora,
  channel: agorachannelName,
  role: role, // host or audience
  audienceLatency: 2,
  camera: {
    camId: '',
    micId: '',
    stream: {}
  }
};

// keep track of devices
var devices = {
  cameras: [],
  mics: []
}

// set log level:
// -- .DEBUG for dev
// -- .NONE for prod
AgoraRTC.setLogLevel(1);

function getVideoContainer() {
  return document.getElementById("full-screen-video");
}

function prepareVideoContainer() {
  const container = getVideoContainer();
  if (!container) {
    throw new Error("Live preview container #full-screen-video was not found");
  }

  container.innerHTML = "";
  container.style.backgroundColor = "#000";
  container.style.overflow = "hidden";

  return container;
}

function playTrackInContainer(track, playerConfig) {
  const container = prepareVideoContainer();
  track.play(container, Object.assign({
    fit: "cover"
  }, playerConfig || {}));
}

function safeErrorDetails(error) {
  try {
    return JSON.stringify(error);
  } catch (e) {
    return String(error);
  }
}

async function reportLiveError(stage, error) {
  try {
    const token = $('meta[name="csrf-token"]').attr('content');
    await fetch(URL_BASE + '/live/debug/error', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': token
      },
      body: JSON.stringify({
        stage: stage,
        message: error && error.message ? String(error.message) : String(error),
        code: error && error.code ? String(error.code) : '',
        details: safeErrorDetails(error),
        page: window.location.pathname,
        channel: options.channel,
        role: options.role
      })
    });
  } catch (requestError) {
    console.log('Failed to send live debug error', requestError);
  }
}

function notifyLiveError(stage, error) {
  const code = error && error.code ? ' [' + error.code + ']' : '';
  const message = (error && error.message ? error.message : 'Unknown live streaming error') + code;

  if (typeof swal === 'function') {
    swal({
      type: 'error',
      title: 'Live streaming error',
      text: stage + ': ' + message
    });
  } else {
    alert('Live streaming error: ' + stage + ': ' + message);
  }
}

function notifyLiveWarning(stage, error) {
  const message = error && error.message ? error.message : 'Live streaming warning';

  console.log('Live streaming warning at', stage, error);

  // Optional devices such as mic can fail without blocking video playback.
  if (window.console && console.warn) {
    console.warn(stage + ': ' + message, error);
  }
}

function handleLiveError(stage, error) {
  console.log('Live streaming error at', stage, error);
  reportLiveError(stage, error);
  notifyLiveError(stage, error);
}

// init Agora SDK

$("#mute-audio").click(function (e) {
  if (localTrackState.audioTrackEnabled) {
    muteAudio();
  } else {
    unmuteAudio();
  }
});

$("#mute-video").click(function (e) {
  if (localTrackState.videoTrackEnabled) {
    muteVideo();
  } else {
    unmuteVideo();
  }
});

async function joinChannel() {

  if (options.role === "audience") {
      client.setClientRole(options.role, {level: options.audienceLatency});
      // add event listener to play remote tracks when remote user publishs.
      client.on("user-published", handleUserPublished);
      client.on("user-unpublished", handleUserUnpublished);
  }
  else{
      client.setClientRole(options.role);
  }

  try {
    options.uid = await client.join(options.appid, options.channel, options.token || null, options.uid || null);

    if (options.role === "host") {
        // Create local tracks independently so one missing device does not block live start.
        let trackErrors = [];

        try {
          localTracks.audioTrack = await AgoraRTC.createMicrophoneAudioTrack();
        } catch (audioError) {
          localTracks.audioTrack = null;
          trackErrors.push(audioError);
        }

        try {
          localTracks.videoTrack = await AgoraRTC.createCameraVideoTrack({
            encoderConfig: cameraVideoProfile,
          });
        } catch (videoError) {
          localTracks.videoTrack = null;
          trackErrors.push(videoError);
        }

        const tracksToPublish = Object.values(localTracks).filter(Boolean);
        if (!tracksToPublish.length) {
          throw trackErrors[0] || new Error("No microphone/camera device available");
        }

        if (localTracks.videoTrack) {
          playTrackInContainer(localTracks.videoTrack, {
            mirror: true
          });
        } else {
          handleLiveError("camera_track", {
            code: "VIDEO_DEVICE_UNAVAILABLE",
            message: "Camera not available. Live will continue with audio only."
          });
        }

        if (!localTracks.audioTrack) {
          notifyLiveWarning("microphone_track", {
            code: "AUDIO_DEVICE_UNAVAILABLE",
            message: "Microphone not available. Live will continue without audio."
          });
        }

        await client.publish(tracksToPublish);

        // Get Cameras
        AgoraRTC.getCameras()
        .then(function(cameras) {
          devices.cameras = cameras; // store cameras array
            cameras.forEach(function(camera, i){
              var name = camera.label.split('(')[0];
              var optionId = 'camera_' + i;
              var deviceId = camera.deviceId;
              if (i === 0 && options.camera.camId === '') {
                options.camera.camId = deviceId;
              }
              $('#camera-list').append('<a class="dropdown-item" id="' + optionId + '">' + name + '</a>');
            });
            $('#camera-list a').click(function(event) {
              var index = event.target.id.split('_')[1];
              console.log('Cameras available: '+ index);
              changeStreamSource(index, "video");
            });
        })
        .catch(function(err) {
          console.log('Error get cameras', err);
        });

        // Get Microphones
        AgoraRTC.getMicrophones()
        .then(function(mics) {
          devices.mics = mics; // store cameras array
            mics.forEach(function(mic, i){
              var name = mic.label.split('(')[0];
              var optionId = 'mic_' + i;
              var deviceId = mic.deviceId;
              if(i === 0 && options.camera.micId === ''){
                options.camera.micId = deviceId;
              }
              if(name.split('Default - ')[1] != undefined) {
                name = '[Default Device]' // rename the default mic - only appears on Chrome & Opera
              }
              $('#mic-list').append('<a class="dropdown-item" id="' + optionId + '">' + name + '</a>');
            });
            $('#mic-list a').click(function(event) {
              var index = event.target.id.split('_')[1];
              changeStreamSource(index, "autio");
            });
        })
        .catch(function(err) {
          console.log('Error get Microphones', err);
        });

        console.log("publish success");

    }// if is host

    console.log("join success");
  } catch (e) {
    handleLiveError("join_channel", e);
  }
}

function changeStreamSource(deviceIndex, deviceType) {
  console.log('Switching stream sources for: ' + deviceType);
  var deviceId;
  var existingStream = false;

  if (deviceType === "video") {
    if (!localTracks.videoTrack) {
      return;
    }
    deviceId = devices.cameras[deviceIndex].deviceId;

    localTracks.videoTrack.setDevice(deviceId);
    options.camera.camId = deviceId;
    localTracks.videoTrack.setEncoderConfiguration(cameraVideoProfile);
  }

  if (deviceType === "audio") {
    if (!localTracks.audioTrack) {
      return;
    }
    deviceId = devices.mics[deviceIndex].deviceId;

    localTracks.audioTrack.setDevice(deviceId);
    options.camera.micId = deviceId;

  }
}

// client callbacks
client.on('stream-published', function (evt) {
  console.log('Publish local stream successfully');
  // beauty effects are processor intensive
  // evt.stream.setBeautyEffectOptions(true, {
  //   lighteningContrastLevel: 2,
  //   lighteningLevel: 0.5,
  //   smoothnessLevel: 0.8,
  //   rednessLevel: 0.5
  // });
});

//live transcoding events..
client.on('liveStreamingStarted', function (evt) {
  console.log("Live streaming started");
});

client.on('liveStreamingFailed', function (evt) {
  console.log("Live streaming failed");
});

client.on('liveStreamingStopped', function (evt) {
  console.log("Live streaming stopped");
});

client.on('liveTranscodingUpdated', function (evt) {
  console.log("Live streaming updated");
});

// ingested live stream
client.on('streamInjectedStatus', function (evt) {
  console.log("Injected Steram Status Updated");
  console.log(JSON.stringify(evt));
});

// when a remote stream leaves the channel
client.on('peer-leave', function(evt) {
  console.log('Remote stream has left the channel: ' + evt.stream.getId());
});

// show mute icon whenever a remote has muted their mic
client.on('mute-audio', function (evt) {
  console.log('Mute Audio for: ' + evt.uid);
});

client.on('unmute-audio', function (evt) {
  console.log('Unmute Audio for: ' + evt.uid);
});

// show user icon whenever a remote has disabled their video
client.on('mute-video', function (evt) {
  console.log('Mute Video for: ' + evt.uid);
});

client.on('unmute-video', function (evt) {
  console.log('Unmute Video for: ' + evt.uid);
});

function leaveChannel() {

  client.leave(function() {
    console.log('client leaves channel');
    options.camera.stream.stop() // stop the camera stream playback
    options.camera.stream.close(); // clean up and close the camera stream
    client.unpublish(options.camera.stream); // unpublish the camera stream
    //disable the UI elements
    $('#mic-btn').prop('disabled', true);
    $('#video-btn').prop('disabled', true);
    $('#exit-btn').prop('disabled', true);
    $("#add-rtmp-btn").prop("disabled", true);
    $("#rtmp-config-btn").prop("disabled", true);
  }, function(err) {
    console.log('client leave failed ', err); //error handling
  });
}

async function subscribe(user, mediaType) {
  try {
    const uid = user.uid;
    // subscribe to a remote user
    await client.subscribe(user, mediaType);
    console.log("subscribe success");
    if (mediaType === 'video') {

        playTrackInContainer(user.videoTrack);
    }
    if (mediaType === 'audio') {

      $('#liveAudio').click(function(event) {
        if (user.audioTrack.isPlaying) {
            user.audioTrack.stop();
            $('#liveAudio').html('<i class="fas fa-volume-mute"></i>');
            return;
        }
        user.audioTrack.play();
        $('#liveAudio').html('<i class="fas fa-volume-up"></i>');
      });
  }//mediaType
  } catch (error) {
    handleLiveError("subscribe_" + mediaType, error);
  }
}

function handleUserPublished(user, mediaType) {
    const id = user.uid;
    remoteUsers[id] = user;
    subscribe(user, mediaType);
}

client.on('exception', function (evt) {
  console.log('Agora exception', evt);
  reportLiveError('agora_exception', evt);
});

function handleUserUnpublished(user, mediaType) {
    if (mediaType === 'video') {
        const id = user.uid;
        delete remoteUsers[id];
    }
}

async function muteAudio() {
  if (!localTracks.audioTrack) return;
  await localTracks.audioTrack.setEnabled(false);
  localTrackState.audioTrackEnabled = false;
  $("#mute-audio > a").html('<i class="bi-mic mr-1"></i> '+textUnmuteAudio);
}

async function muteVideo() {
  if (!localTracks.videoTrack) return;
  await localTracks.videoTrack.setEnabled(false);
  localTrackState.videoTrackEnabled = false;
  $("#mute-video > a").html('<i class="bi-camera-video mr-1"></i> '+textUnmuteVideo);
}

async function unmuteAudio() {
  if (!localTracks.audioTrack) return;
  await localTracks.audioTrack.setEnabled(true);
  localTrackState.audioTrackEnabled = true;
  $("#mute-audio > a").html('<i class="bi-mic-mute mr-1"></i> '+textMuteAudio);
}

async function unmuteVideo() {
  if (!localTracks.videoTrack) return;
  await localTracks.videoTrack.setEnabled(true);
  localTrackState.videoTrackEnabled = true;
  $("#mute-video > a").html('<i class="bi-camera-video-off mr-1"></i> '+textMuteVideo);
}
