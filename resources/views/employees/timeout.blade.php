<!-- Modal -->
<style>
  .container {
    max-width: 800px;
    margin-top: 10px;
  }
  #video {
    position: relative;
    width: 100%;
    border: 3px solid #007bff;
    border-radius: 5px;
    box-shadow: 0 4px 10px rgba(0, 123, 255, 0.2);
  }
  #googleMap {
    height: 150px;
    border: 3px solid #28a745;
    border-radius: 5px;
    box-shadow: 0 4px 10px rgba(40, 167, 69, 0.2);
  }
  #canvas {
    display: none; /* Initially hidden */
    border: 3px solid #28a745;
    border-radius: 5px;
    box-shadow: 0 4px 10px rgba(40, 167, 69, 0.2);
  }
  #captureButton {
    background-color: #007bff;
    color: white;
    border: none;
    padding: 10px 20px;
    font-size: 16px;
    border-radius: 5px;
    font-size: 25px;
    cursor: pointer;
  }
  #captureButton:hover {
      font-size: 25px;
    background-color: #0056b3;
  }
  #retakeButton {
 
    padding: 10px 20px;
    font-size: 25px;
    border-radius: 5px;
    display: none; /* Initially hidden */
  }
  #submitButton {
   
      padding: 10px 20px;
    font-size: 25px;
    border-radius: 5px;
    display: none;
  }
  .alert {
    display: none;
  }
</style>

<div class="modal fade" id="timeOut" tabindex="-1" role="dialog" aria-labelledby="timeOutData" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">Time Out
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method='POST' action='timeout-capture' onsubmit="show();"  enctype="multipart/form-data">
          @csrf   
      <div id="app" class=' '>
        <div class="row mb-2 ">
          <div class="col-md-12 col-sm-12">
              <div class=" text-center">
                  <br>
                  <button id="captureButton" onclick="return false;" class="btn btn-primary btn-fill ">
                    <i class="ti-camera"></i> Capture Photo
                </button>
                
                <button id="retakeButton" onclick="return false;" class="btn btn-danger btn-fill ">
                    <i class="ti-reload"></i> Retake Photo
                </button>
                
                <button id="submitButton" type="submit"  class="btn btn-success btn-fill">
                    <i class="ti-check"></i> Submit TimeOut
                </button>
                

              <br>
              </div>
          </div>
          <div class="col-md-12 col-sm-12 mt-1">
            <div class=" text-center">
              <span id='map_reference' target="_blank" href=""></span>
                  <video id="video" class="img-fluid " width="100%" height="100%" autoplay></video>
                  <div id="googleMap" style="position: absolute; bottom: 30px; left: 15px; width: 100px; height: 100px; border: 3px solid #28a745; border-radius: 5px; box-shadow: 0 4px 10px rgba(40, 167, 69, 0.2);"></div>
  
              <canvas id="canvas" class='mb-4' height="330px" style="width: 100%;"></canvas> 
              <input  type='hidden' id='location_mo' name='location' value='' required>
              <input  type='hidden' id='location_lat' name='location_lat' value='' required>
              <input  type='hidden' id='location_long' name='location_long' value='' required>
              <input type="file" id="imageInput" name="image" accept="image/*"  hidden >
       
            </div>
          </div>
        </div>
      </div>
      </form>
    </div>
  </div>
</div>
<script >
      document.getElementById("captureButton").disabled = true;
  const x = document.getElementById("demo");
  function getLocation() {
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(success, error);
    } else { 
      x.innerHTML = "Geolocation is not supported by this browser.";
    }
  }
  
  function success(position) {
 
  
      var renz = "https://maps.googleapis.com/maps/api/geocode/json?latlng="+position.coords.latitude+","+position.coords.longitude+"&key=AIzaSyDeSpk2-I61V7TFFomaxqOWv-Ir2ZeYkQM";
      fetch(renz)
      .then(response => response.json())
      .then(data => {
          if (data.results && data.results.length > 0) {
              document.getElementById("location_mo").value = data.results[0].formatted_address;
              document.getElementById("map_reference").innerHTML = data.results[0].formatted_address;
          }
          // console.log(data.results[0].formatted_address);
          // document.getElementById("map_reference").href=data.results[0].formatted_address; 
         
          // Process the data as needed
      })
      .catch(error => {
          console.error('Error:', error);
      });
      document.getElementById("location_lat").value = position.coords.latitude;
      document.getElementById("location_long").value = position.coords.longitude;
      const locationLatElement = document.getElementById("location_lat");

      if (locationLatElement && locationLatElement.value.trim() !== "") {
          document.getElementById("captureButton").disabled = false;
      } else {
          document.getElementById("captureButton").disabled = true;
      }
      // var maps = "http://maps.google.com/maps?q="+position.coords.latitude+","+position.coords.longitude;
      myMap(position.coords.latitude,position.coords.longitude)
  }
  
  function error() {
    // document.getElementById("submit_out").disabled = true;
    document.getElementById("captureButton").disabled = true;;
      alert("Sorry, no position available.Please open location and refresh.");
      
    location.reload();
 
    }
  </script>
  <script>
      function myMap(lat,long) {
      var mapProp= {
        center:new google.maps.LatLng(lat,long),
        zoom:18,
        disableDefaultUI: true,
  // add back fullscreen, streetview, zoom
  zoomControl: false,
  streetViewControl: false,
  fullscreenControl: false
      };
      var map = new google.maps.Map(document.getElementById("googleMap"),mapProp);
      var marker = new google.maps.Marker({
              position: new google.maps.LatLng(lat, long),
              map: map,
              icon: {
              url: "{{ asset('images/location.png') }}", // Replace with the path to your custom icon
              scaledSize: new google.maps.Size(50, 50), // Adjust the size of the icon
              }
          });
      }
</script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDeSpk2-I61V7TFFomaxqOWv-Ir2ZeYkQM&callback=getLocation"></script>
<script>
    var name = {!! json_encode(auth()->user()->name) !!};
     const imageInput = document.getElementById('imageInput');
    const video = document.getElementById('video');
    const captureButton = document.getElementById('captureButton');
    const retakeButton = document.getElementById('retakeButton');
    const submitButton = document.getElementById('submitButton');
    const canvas = document.getElementById('canvas');
    const ctx = canvas.getContext('2d');
    const alertBox = document.getElementById('alert');
  
    // Function to start the camera
    function startCamera() {
      navigator.mediaDevices.getUserMedia({ video: true })
        .then(function (stream) {
          video.srcObject = stream;
        })
        .catch(function (error) {
          document.getElementById("captureButton").disabled = true;
          alert("Sorry, Error accessing the camera. Please refresh.");
          console.error('Error accessing the camera:', error);
          location.reload();
        });
    }
  
    // Function to capture a photo from the video stream
    function capturePhoto() {
      var   lat_po = document.getElementById("location_lat").value;
      var   long_po = document.getElementById("location_long").value;
      ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

      // Get the address from the hidden input field
      const address = document.getElementById("location_mo").value;

      // Set styles for the text
      ctx.font = "15px Arial";
      ctx.fillStyle = "white";
      ctx.textBaseline = "top";
      ctx.shadowColor = "black";
      ctx.shadowBlur = 4;    // Text color
  ctx.strokeStyle = "black";    // Border color
  ctx.lineWidth = 2;

  // // Draw snapshot title
  // ctx.strokeText("📸 Live Snapshot", 20, 20);
  // ctx.fillText("📸 Live Snapshot", 20, 20);

      // Add title text
      ctx.strokeText("Name: "+ name, 5, 15);
      ctx.fillText("Name: "+ name, 5, 15);
      ctx.font = "10px Arial";
      
    
      ctx.strokeText("Lat: "+ lat_po, 5, 35);
      ctx.fillText("Lat: "+ lat_po, 5, 35);
      ctx.strokeText("Long: "+ long_po, 5, 45);
      ctx.fillText("Long: "+ long_po, 5, 45);
      // const lines = wrapText(ctx, "Addressasd asd asd asd as dasd as das dasd as da: "+address, 5, 75, canvas.width - 40, 24);
      ctx.strokeText("Address: "+ address, 5, 55);
      ctx.fillText("Address: "+ address, 5, 55);
      canvas.toBlob((blob) => {
            const file = new File([blob], 'captured-image.png', { type: 'image/png' });
            
            // Create a FileList and set it as the value of the file input
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            console.log(dataTransfer.files);
            imageInput.files = dataTransfer.files; // Set the file in the file input

            // Optionally, you can trigger the form submission automatically
            document.getElementById('imageForm').submit(); // Automatically submit the form
        });
      canvas.style.display = 'block'; // Show canvas
      video.style.display = 'none'; // Hide video
      captureButton.style.display = 'none'; // Hide capture button
      retakeButton.style.display = 'inline-block'; // Show retake button
      submitButton.style.display = 'inline-block'; // Show retake button
      alertBox.style.display = 'block'; // Show success message

     
    }
  
    function wrapText(context, text, x, y, maxWidth, lineHeight) {
      const words = text.split(' ');
      let line = '';
      const lines = [];

      for (let n = 0; n < words.length; n++) {
        const testLine = line + words[n] + ' ';
        const metrics = context.measureText(testLine);
        const testWidth = metrics.width;

        if (testWidth > maxWidth && n > 0) {
          context.fillText(line, x, y);
          lines.push(line);
          line = words[n] + ' ';
          y += lineHeight;
        } else {
          line = testLine;
        }
      }
      context.fillText(line, x, y);
      lines.push(line);
      return lines;
    }

  // Function to retake a photo
  function retakePhoto() {
    canvas.style.display = 'none'; // Hide canvas
    video.style.display = 'block'; // Show video
    captureButton.style.display = 'inline-block'; // Show capture button
    retakeButton.style.display = 'none'; // Hide retake button
    submitButton.style.display = 'none'; // Hide retake button
    alertBox.style.display = 'none'; // Hide success message
  }

  // Start the camera when the page loads
  startCamera();

  // Event listener for the capture button
  captureButton.addEventListener('click', capturePhoto);

  // Event listener for the retake button
  retakeButton.addEventListener('click', retakePhoto);

</script>

<!-- Google Map Initialization -->


<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDeSpk2-I61V7TFFomaxqOWv-Ir2ZeYkQM&callback=getLocation"></script>
