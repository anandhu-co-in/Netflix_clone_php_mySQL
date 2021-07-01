
$(document).ready(function() {

    $(".buttonMute").click(function(){

        //block of code that runs when the click event triggers
        console.log("Event worked");

        var muted=$(".previewVideo").prop("muted");
        $(".previewVideo").prop("muted",!muted); //Set the opposite of the propery
        $('.buttonMute i').toggleClass("fa-volume-mute fa-volume-up");

      });

      //Hide video after it ends and show the image
      $(".previewVideo").on('ended',function(){
        console.log('Video has ended!');
        $(".previewImage").toggle();
        $(".previewVideo").toggle();
      });


      //If the watch button is clicked go back to previous page
      $(".watchBackButton").click(function (){
        console.log("clicked back button")
        window.history.back();
      });


      //If the watch button is clicked go back to previous page
      $(".replayButton").click(function (){
        console.log("clicked replay");
        $("video")[0].currentTime=0;
        $(".upNext").fadeOut();
      });
})

//For toggling the navbar background while scrolling

$(document).scroll(function(){
  var makeNavBlack = $(this).scrollTop()>$(".navBar").height();
  $('.navBar').toggleClass("navbarMakeBlack",makeNavBlack);
})


//This function to auto hide the nav bar after 2 sec of no mouse movement
function navAutoHide(){
  var timeout=null;

   //Execute after 2 seconds to hide the nav
  timeout=setTimeout(function(){
    $(".videoControls.watchNav").fadeOut();
  },2000)

  //If mouse moved restart timer
  $(document).on("mousemove",function(){
    console.log("mouse moved timer reset..")
    clearTimeout(timeout)
    $(".videoControls.watchNav").fadeIn();

    timeout=setTimeout(function(){
      $(".videoControls.watchNav").fadeOut();
    },2000)
  })

}


function videoProgressTrack(videoId,username){
  console.log(videoId);
  console.log(username);

  //Set existing progress to the player if we have watched the video earlier
  setStartTime(videoId,username);


  //Adds Progress Row if not already existing for the video
  $.post("ajax/addDuration.php",{'videoId':videoId,'username':username},function(data){
    if (data!==null && data !==""){
      console.log(data);
    }
  });


  //Periodically update progress

  var interval;
  $("video").on("playing",function(event){

    console.log("Playing video..");

    //Hide upnext overlay if present
    $(".upNext").fadeOut();


    //Start interval
    window.clearInterval(interval);
    interval=window.setInterval(function(){
      
      //Update the progress to db
      console.log("Update Now");
      currentProgress=event.target.currentTime;
      console.log(currentProgress);

      $.post("ajax/updateDuration.php",{'videoId':videoId,'username':username,'progress':currentProgress},function(data){
        if (data!==null && data !==""){
          console.log(data);
        }
      });
    
    },2000);
 
  })


  //We dont need to update the progress if the video is finished
  $("video").on("ended",function(){

      //display the nuNExt
      $(".upNext").fadeIn();

      console.log("Video Ended, Clearing timer");
      window.clearInterval(interval);

      //Mark the video os finished watching in the progress table

      $.post("ajax/markFinished.php",{'videoId':videoId,'username':username,'progress':currentProgress},function(data){
        if (data!==null && data !==""){
          console.log(data);
        }
      });


    })

}


//Set the the start of video after launching the page
function setStartTime(videoId,username){

  $.post("ajax/getProgress.php",{'videoId':videoId,'username':username},function(data){

    if(isNaN(data)){
      //If data isnt a number (otherwise the geprogess.php returns some string)
      console.log("Progress NOT obtained");
      console.log(data);
      return;
    }
    else{
      console.log("Progress obtained");
      console.log(data);

      // The canplay event occurs when the browser can start playing the specified audio/video (when it has buffered enough to begin).https://www.w3schools.com/tags/av_event_canplay.asp
      $("video").on("canplay",function(){
        this.currentTime=data; //this here refers to the video here
        $("video").off("canplay");
        console.log("Resume time set");
      })

    }
  });

}

//Call this function when user clicks play button, to navigate to the watch page
function watchVideo(videoId){
  console.log("Play clicked");
  window.location.href="watch.php?id="+videoId;
}

