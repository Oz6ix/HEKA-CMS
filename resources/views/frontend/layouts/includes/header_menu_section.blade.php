<!-- Header -->
<header>
<?php $header_data = fetch_header_data();
$controller = class_basename(\Route::current()->controller);
$action = class_basename(\Route::current()->action['uses']);


 ?>
  <nav class="navbar navbar-expand-lg">
    <div class="container scrollheight"> <a class="navbar-brand" href="{{ route('home') }}"><img src="{{ URL::asset('uploads/' . $header_data['directory_logos'] . '/' . $header_data['logo_name']) }}" class="img-fluid" alt=""></a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation"> <span class="navbar-toggler-icon"></span> </button>
      <div class="collapse navbar-collapse" id="navbarResponsive">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item {{ ($controller == "HomeController" && $action == "HomeController@home") ? 'active' : ''}}"> <a class="nav-link" href="{{ route('home') }}">Home <span class="sr-only">(current)</span> </a> </li>


          <li class="nav-item {{ ($controller == "StaticPageController" && $action == "StaticPageController@about") ? 'active' : ''}}"> <a class="nav-link" href="{{ route('about') }}">About</a> </li>


          <li class="nav-item"> <a class="nav-link" href="{{ route('courses') }}">Courses</a> </li>

          <li class="nav-item"> <a class="nav-link" href="{{ route('package_listing') }}">Packages</a> </li>


         <li class="nav-item {{ ($controller == "StaticPageController" && $action == "StaticPageController@faq") ? 'active' : ''}}"> <a class="nav-link" href="{{ route('faq') }}">FAQ</a> </li>

      <li class="nav-item {{ ($controller == "StaticPageController" && $action == "StaticPageController@lecturers") ? 'active' : ''}}"> <a class="nav-link" href="{{ route('lecturers') }}">Lecturers</a> </li> 

          <li class="nav-item {{ ($controller == "ForumController" && $action == "ForumController@forum_topic") ? 'active' : ''}}"> <a class="nav-link" href="{{route('forum_topic')}}">Forum</a> </li>

          @if(!empty(Auth::user()->id))
          <li class="nav-item {{ ($controller == "StaticPageController" && $action == "StaticPageController@lounge") ? 'active' : ''}}"> <a class="nav-link" href="{{route('lounge')}}">Lounge</a> </li>
          @endif
           

             <li class="nav-item dropdown {{ ($controller == "StaticPageController" && $action == "StaticPageController@news_alert") || ($controller == "StaticPageController" && $action == "StaticPageController@blogs_alert") ? 'active' : ''}}"> <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">News</a> 

               <ul class="dropdown-menu">
             <li><a class="dropdown-item " href="{{route('news_alert')}}">News Alert</a></li>
             <li><a class="dropdown-item " href="{{route('blogs_alert')}}">Blog</a></li>
              </ul>


             </li>



            <!--   <li class="nav-item dropdown"><a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">News</a>
           <ul class="dropdown-menu">
             <li><a class="dropdown-item " href="#">News Alert</a></li>
             <li><a class="dropdown-item " href="#">Blog</a></li>
              </ul>
         </li> -->


              <li class="nav-item {{ ($controller == "StaticPageController" && $action == "StaticPageController@contact") ? 'active' : ''}}"> <a class="nav-link" href="{{ route('contact') }}">Contact</a> </li>


          
        </ul>
      </div>
    </div>
  </nav>
</header>
<!-- Header --> 