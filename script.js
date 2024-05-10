document.addEventListener("DOMContentLoaded", function () {
   // Homepage Slider
  const slider = function () {
    // variable initialization
    let curSlide = 0;
    const maxSlide = $(".slider").length; //get the total number of slides

    // this function will help us to move the slide
    const goToSlide = function (slide) {
      $(".slider").each((i, s) => {
        $(s).css("transform", `translateX(${100 * (i - slide)}%)`);
      });
    };

    // Next slide
    const nextSlide = function () {
      if (curSlide === maxSlide - 1) {
        curSlide = 0;
      } else {
        curSlide++;
      }
      goToSlide(curSlide);
    };

    // Prev slide
    const prevSlide = function () {
      if (curSlide === 0) {
        curSlide = maxSlide - 1;
      } else {
        curSlide--;
      }
      goToSlide(curSlide);
    };
    const init = function () {
      goToSlide(0);
    };
    init(); //initialization

    // click events on next/prev buttons to make slider work,
    $(".nextbtn").click(nextSlide);
    $(".prevbtn").click(prevSlide);

    // you can move the slider by arrow as well
    $(document).keydown(function (e) {
      if (e.key === "ArrowLeft") prevSlide();
      e.key === "ArrowRight" && nextSlide();
    });
  };
  slider();
 
});
function editEvent(event_id) {
  // Fetch event details using AJAX
  $.ajax({
    url: 'fetch_event.php',
    type: 'post',
    data: {event_id: event_id},
    success: function(response) {
      var event = JSON.parse(response);

      // Fill the update form with the event details
      $('#event-id').val(event.event_id);
      $('#event-name').val(event.event_name);
      $('#category-id').val(event.category_id);
      $('#description').val(event.description);
      $('#image-path').val(event.image_path);
    }
  });
}
function redirectToEventDetail() {
  window.location.href = "event_detail.php";
}


