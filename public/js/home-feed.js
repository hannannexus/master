function Page() {
  this.main = function() {
    var self = this;
    $.ajax({
      type: 'POST',
      url: home + 'render/feed',
      data: null,
      dataType: 'json',
      success: function(json) {
        var title = $('#feed-title');
        $('#feed-container').empty().append(json);
      }
    });  
  };
}

$(function() {
  setInterval(function() {var page = new Page(); page.main();}, 3000);
});