<?php
$this->WriteInc('Header');
?>
<div class="row">
	<div class="col-sm-8">
    <iframe
      id="JotFormIFrame-200846671445357"
      onload="window.parent.scrollTo(0,0)"
      allowtransparency="true"
      src="https://form.jotformeu.com/200846671445357"
      frameborder="0"
      style="width: 1px;
      min-width: 100%;
      height:539px;
      border:none;"
      scrolling="yes">
    </iframe>
    <script type="text/javascript">
      var ifr = document.getElementById("JotFormIFrame-200846671445357");
      if(window.location.href && window.location.href.indexOf("?") > -1) {
        var get = window.location.href.substr(window.location.href.indexOf("?") + 1);
        if(ifr && get.length > 0) {
          var src = ifr.src;
          src = src.indexOf("?") > -1 ? src + "&" + get : src  + "?" + get;
          ifr.src = src;
        }
      }
      window.handleIFrameMessage = function(e) {
        var args = e.data.split(":");
        if (args.length > 2) { iframe = document.getElementById("JotFormIFrame-" + args[2]); } else { iframe = document.getElementById("JotFormIFrame"); }
        if (!iframe)
          return;
        switch (args[0]) {
          case "scrollIntoView":
            iframe.scrollIntoView();
            break;
          case "setHeight":
            iframe.style.height = args[1] + "px";
            break;
          case "collapseErrorPage":
            if (iframe.clientHeight > window.innerHeight) {
              iframe.style.height = window.innerHeight + "px";
            }
            break;
          case "reloadPage":
            window.location.reload();
            break;
        }
        var isJotForm = (e.origin.indexOf("jotform") > -1) ? true : false;
        if(isJotForm && "contentWindow" in iframe && "postMessage" in iframe.contentWindow) {
          var urls = {"docurl":encodeURIComponent(document.URL),"referrer":encodeURIComponent(document.referrer)};
          iframe.contentWindow.postMessage(JSON.stringify({"type":"urls","value":urls}), "*");
        }
      };
      if (window.addEventListener) {
        window.addEventListener("message", handleIFrameMessage, false);
      } else if (window.attachEvent) {
        window.attachEvent("onmessage", handleIFrameMessage);
      }
      </script>
       </div>
        <div class="col-sm-4">
            <div class="box">
                <h2 class="green">Solliciteren in Yabbis</h2>
				<div class="inner">
				<p>Welkom op de sollicitatie-pagina van Yabbis! We zijn momenteel nog bezig met een eigen systeem en daarom is dit formulier ingezet zodat we toch fatsoenlijk sollicitaties kunnen doornemen en/of ontvangen! Alvast veel succes bij het insturen van de sollicitatie en neem er vooral de tijd voor.<br>
				</p></div>
            </div>
        </div>
    </div>
    <script type="text/javascript" src="/Simple/Assets/js/global/script.js"></script>
    <script type="text/javascript" src="/Simple/Assets/js/loaders/changename.js"></script>
<?php
$this->WriteInc('Footer');
?>