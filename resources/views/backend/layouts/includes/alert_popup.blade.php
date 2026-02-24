 <style type="text/css"> 
.www a:hover{color:#ffff!important;}
</style>
<!--  <div class="flash-message" style="position: absolute;top:200px; right: 0;left:0;width: 45%;margin: 0 auto;z-index:1000"> -->
<div class="flash-message">
@foreach (['danger', 'warning', 'success', 'info'] as $msg)
@if(Session::has('alert-' . $msg))
<div class="alert alert-{{ $msg }} alert-dismissible">{{ Session::get('alert-' . $msg) }}
<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>
@endif
@endforeach
</div>
<!-- </div> -->


