<?php $fetch_home_course_details = fetch_home_course_item(); 
$fetch_home_course_detail_count = fetch_home_course_item_count(); 
?>
@if(!empty($fetch_home_course_details))
<div  class="container-fluid darkbg">
  <div class="container">
    <div class="class-section">
      <h2 class="classheadingspace">Explore Our Courses</h2>   
    </div>  
  </div>
  <div class="container">
    <div class="tab-content" id="pills-tabContent" >
      <div class="tab-pane fade show active" id="showall" role="tabpanel" aria-labelledby="showall-tab">
        <div class="row classpadding">
          <?php $count = 0; ?>
          @foreach($fetch_home_course_details as $fetch_home_course_detail)
          <?php if($count == 6) break; ?>

          <div class="col-md-4">
            @if(!empty($fetch_home_course_detail['course_image']))
              <div class="Portfolio"><a href="{{ url('/course/' . $fetch_home_course_detail['sef_url']) }}"><img class="course-img" src="{{ URL::asset('uploads/' .'courses' . '/' . $fetch_home_course_detail['course_image']) }}" alt="">
                <div class="desc">{{$fetch_home_course_detail['course_title']}} </div>
                <!-- <div class="subdesc">Scelerisque Nibh Vitae</div> -->
                </a></div>
              @else
                <div class="Portfolio"><a href="{{ url('/course/' . $fetch_home_course_detail['sef_url']) }}"><img class="course-img" src="{{ URL::asset('uploads/' .'common' . '/'.'no-image.jpg') }}" alt="">
                <div class="desc">{{$fetch_home_course_detail['course_title']}} </div>
                <!-- <div class="subdesc">Scelerisque Nibh Vitae</div> -->
                </a></div>
              @endif
          </div>
           <?php $count++; ?>
          @endforeach
         
        </div>
     
      
      </div>  
     
    </div>
  </div>
 @if(sizeof($fetch_home_course_detail_count) >= 6)
  <div class="row">
    <div class="col-md-12 text-center showbutton"> <a href="{{ route('courses') }}" class="border-button">Show More</a> </div>
  </div>
  @endif

</div>
@endif
<!-- </div> -->
<!-- Filter Gallery Ends Here -->