
$(document).ready(function () {
  $('#username').keyup(function(event) {
    if (event.keyCode === 13) {
      $('#search-btn').click()
    }
  })
})

function userValid() {
  var username = document.getElementById('username')

  if (username.length > 16 || username.value.match(/[^\d\uFB01\uFB02\u00AA\u00B5\u00BA\u00BF-\u1FFF\u2C00-\uD7FF\w _\.]+/g)) {
    username.classList.add('has-danger')
  } else {
    window.location.href = 'player.php?player_name=' + username.value
  }
}
