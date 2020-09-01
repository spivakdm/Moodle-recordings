 <!DOCTYPE html>
<html>
<script src='main.js'></script>
<body>
<select onchange='setVideo(this.value)'>
<option selected='selected'>Select video</option>
<?php
$content=array("Dima" =>'https://www.youtube.com/embed/jEW7D1_XRU8', "Exploding whale"=>"https://www.youtube.com/embed/yPuaSY0cMK8", "Me at the zoo"=>"https://www.youtube.com/embed/jNQXAC9IVRw");

foreach($content as $name => $val) {
  echo "<option value='$val'>$name</option>";
}

?>
</select>
<hr>
<iframe id="video" width="640" height="480" src="" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
</body>
</html>
