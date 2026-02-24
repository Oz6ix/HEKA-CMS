 <style type="text/css"> 
.www a:hover{color:#ffff!important;}
</style>
 <div class="flash-message" style="position: absolute;top:200px; right: 0;left:0;width: 45%;margin: 0 auto;z-index:1000">
        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
        @if(Session::has('alert-' . $msg))
         <?php 
          $message=Session::get('alert-' . $msg);
       if($message=='You have been completed the first step of registration successfully.') {?>
         <div  style="text-align:center" class="www alert alert-{{ $msg }} alert-dismissible">You have completed the first step of registration successfully.<br> Please scroll down for facial verification.
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>

      <?php } else if($message=='Sorry. The facial verification failed.') {?>
         <div  style="text-align:center" class="www alert alert-{{ $msg }} alert-dismissible">Sorry. The facial verification failed.<br> Please scroll down for re-verification.
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>


        <?php } else if($message=='Please try again. The facial verification failed.') {?>
         <div  style="text-align:center" class="www alert alert-{{ $msg }} alert-dismissible">Please try again. The facial verification failed.<br> Please scroll down for re-verification.
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>

         <?php }else{ ?>
             <div  style="text-align:center" class="alert alert-{{ $msg }} alert-dismissible">{{ Session::get('alert-' . $msg) }}
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div> 
          <?php }?>
        @endif
        @endforeach
</div>


