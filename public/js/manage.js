document.getElementById('activity-image').addEventListener('change', function() {
  var reader = new FileReader();
  reader.onload = function(e) {
    var imagePreview = document.getElementById('image-preview');
    imagePreview.src = e.target.result;
    imagePreview.style.display = 'block';
  };
  reader.readAsDataURL(this.files[0]);
});
