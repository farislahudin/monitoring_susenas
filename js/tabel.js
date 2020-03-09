$(document).ready(function(){
  $('form').on('submit', function(e){
    e.preventDefault()
    $.ajax({
      type:'POST',
      url:'ajaxTabel.php',
      data:$('form').serialize(),
      success: function(html){
        alert('okeokeoke')
        // $('#dataTable').html(html)
        
        // console.log("aaaaa")
      }
    })
  })
})