
var alertList = document.querySelectorAll('.alert')
alertList.forEach(function(alert) {
    new bootstrap.Alert(alert)
})

var alertQs = document.querySelector('.alert')
var bsAlert = bootstrap.Alert.getInstance(alertQs)
window.setTimeout(() => { bsAlert.close(); }, 3000).fadeTo(500, 0).slideUp(500);

