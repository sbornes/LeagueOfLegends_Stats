
$(document).ready(function () {
  $('#username').keyup(function (event) {
    if (event.keyCode === 13) {
      $('#search-btn').click();
    }
  });
});

$(function(){

  $(".dropdown-menu a").click(function(){
    $("#regionMenu").text($(this).attr("data-code"));
    $("#regionMenu").val($(this).attr("data-code"));
 });

});

function userValid () {
  var username = document.getElementById('username');
  var region = document.getElementById('regionMenu').value;
  if (username.value.length > 16 || username.value.match(/[^\d\uFB01\uFB02\u00AA\u00B5\u00BA\u00BF-\u1FFF\u2C00-\uD7FF\w _\.]+/g)) {
    // username.classList.add('is-invalid');
    var data = { "status_code": "-1" };
    $.redirect('index.php', {'data': data, 'player': username.value });

  } else {
    window.location.href = 'player.php?player_name=' + username.value + '&region=' + region;
  }
}

function setupTooltip($selector) {
  $($selector).find('[data-toggle="tooltip"]').tooltip({
      animated: 'fade',
      placement: 'top',
      html: true
  });
};

