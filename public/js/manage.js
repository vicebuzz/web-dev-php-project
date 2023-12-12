// Create Activity Image Changer

document.getElementById('activity-image').addEventListener('change', function() {
  var reader = new FileReader();
  reader.onload = function(e) {
    var imagePreview = document.getElementById('image-preview');
    imagePreview.src = e.target.result;
    imagePreview.style.display = 'block';
  };
  reader.readAsDataURL(this.files[0]);
});


// Revise Activity Image Changer

var editActivityImageInputs = document.querySelectorAll('#revise-activities-section input[type="file"]');


editActivityImageInputs.forEach(function(input) {
  input.addEventListener('change', function() {
    var reader = new FileReader();
    var imagePreview = this.closest('.activity-card').querySelector('.activity-image-preview');

    reader.onload = function(e) {
      imagePreview.src = e.target.result;
      imagePreview.style.display = 'block';
    };

    reader.readAsDataURL(this.files[0]);
  });
});

