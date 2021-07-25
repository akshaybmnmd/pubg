<?php
date_default_timezone_set('Asia/Kolkata');

$myfile = fopen("registration_data.json", "r") or die("Unable to open file!");
$array = json_decode(fread($myfile, filesize("registration_data.json")))->data;
$slots = $array->slots;
$div = "";
$data = "<div>registerd";

foreach ($array->available_slots as $key => $struct) {
  $key = $key + 1;
  // print_r($struct);
  if ($struct->available) {
    $style = "style='cursor: pointer;' onclick='selslot($key)'";
    $css = "btn-primary";
  } else {
    $style = "style='cursor: not-allowed;'";
    $css = "btn-secondary";
    $data .= "<br>slot$key: " . $struct->team;
  }
  if ($key > $slots) {
    $style = "style='cursor: not-allowed;'";
    $css = "btn-light ";
  }
  if ($key % 2) {
    $div .= "<div class='btn-group'> <button type='button' $style class='btn $css btn-lg' id='slot_$key'> __ $key __ </button>";
  } else {
    $div .= "<button type='button' $style class='btn $css btn-lg' id='slot_$key'> __ $key __ </button> </div>";
  }
}
$data .= "</div>";
fclose($myfile);


?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="icon" href="../img/icon.jpeg" type="image/jpeg">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous" />

  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <title>Wolf Pack rooms - Register</title>
</head>

<body>
  <div>
    <div class="mb-3">
      <label for="teamName" class="form-label">Team Name</label>
      <input type="text" class="form-control" id="teamName" aria-describedby="emailHelp">
      <div id="emailHelp" class="form-text">If you are already registered as a team <a href="#">signup</a></div>
    </div>
    <div class="mb-3">
      <label for="playerCount" class="form-label">Players Count</label>
      <input type="number" class="form-control" id="playerCount">
    </div>
    <!-- <div class="mb-3">
      <label for="playerNames" class="form-label">Players Names</label>
      <textarea type="textarea" class="form-control" id="playerNames"></textarea>
    </div> -->
    <div class="mb-3">
      <label for="slots" class="form-label">Choose slot</label>
      <div class="btn-group-vertical" role="group" aria-label="Basic example" id="slots">

        <?php echo $div; ?>

      </div>
    </div>

    <button type="submit" class="btn btn-primary" onclick="register()">Submit</button>

    <?php echo $data; ?>

  </div>
</body>

<script>
  setTimeout(function() {
    window.location.reload();
  }, 60000);

  var slot = "";
  var token = "";

  function selslot(s) {
    if (slot)
      $("#slot_" + slot)[0].className = "btn btn-primary btn-lg";
    slot = s;
    $("#slot_" + slot)[0].className = "btn btn-success btn-lg";
  }

  function getslot() {
    $.ajax({
      url: "./registration_data.json",
      cache: false,
      type: 'GET',
      success: function(result) {
        console.log("upload_action", result);
        $("#slot_" + slot)[0].className = "btn btn-success btn-lg";
      },
      error: function(result) {}
    });
  }

  function register(s) {
    if (slot) {
      if ($("#teamName").val()) {
        if ($("#playerCount").val()) {
          var form_data = new FormData();
          form_data.append('token', token);
          form_data.append('team_name', $("#teamName").val());
          form_data.append('count', $("#playerCount").val());
          form_data.append('slot', slot);
          form_data.append('players', '$("#playerNames").val()');
          $.ajax({
            url: "../api/register.php",
            dataType: 'text',
            data: form_data,
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function(result) {
              console.log("response", result);
              window.location.reload();
            },
            error: function(result) {}
          });
          alert("registered");
        }
      } else
        alert("Add a team name");
    } else
      alert("Select a available slot.");

  }
</script>

</html>