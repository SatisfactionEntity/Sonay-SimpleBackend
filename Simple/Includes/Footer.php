<?php if (Users::$Session == true && CMS::$Config['cms']['ads']) { ?>
<div class="box">
    <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
    <ins class="adsbygoogle"
         style="display:block"
         data-ad-client="<?= CMS::$Config['cms']['data-ad-client'] ?>"
         data-ad-slot="<?= CMS::$Config['cms']['data-ad-slot'] ?>"
         data-ad-format="auto"></ins>
    <script>
        (adsbygoogle = window.adsbygoogle || []).push({});
        
    </script>
</div>
<?php } ?>
</div>

<footer>
    
<div class="container">


</div>
    
    <div class="center copyright">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>


  <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">Credits</button>
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          
          <h4 style="color: black;" class="modal-title"><center> &nbsp; SimpleCMS 2.0</center></h4>
        </div>
        <div class="modal-body">
          <p style="color: black;">Backend / Core <br><br> Rexus<br><br><br>Emulator / WebSockets <br><br> Mad Haze<br><br><br>Layout / Template <br><br> Quasar & Sonay</p>
        </div>
        <div style="color: black;" class="modal-footer">
        <p>Met liefde gemaakt door en voor het community van Nambo! ;) Als je nog fouten ziet meld dit aan 1 van Nambo haar developers! Have fun!!!!</p>
        </div>
      </div>
    </div>
  </div>


<script>
$(document).ready(function(){
    $('[data-toggle="popover"]').popover();   
});
</script>

</div>
</footer>
</body>
</html>
