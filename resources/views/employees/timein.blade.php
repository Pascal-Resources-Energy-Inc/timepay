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
      height: 200px;
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
    .location-info {
      font-size: 10px;
      color: #666;
      margin-top: 5px;
    }
    .hub-info {
      background: rgba(0, 123, 255, 0.1);
      padding: 5px;
      border-radius: 3px;
      font-size: 9px;
      margin-top: 3px;
    }
  </style>
  
  <div class="modal fade" id="timeIn" tabindex="-1" role="dialog" aria-labelledby="timeInData" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method='POST' action='timein-capture' onsubmit="show();" enctype="multipart/form-data">
            @csrf   
        <div id="app" class=' '>
          <div class="row mb-2 ">
           
            <div class="col-md-12 col-sm-12 mt-1">
              <div class=" text-center">
                    <video id="video" class="img-fluid " width="100%" height="100%" autoplay playsinline muted></video>
                    <div id="googleMap" style="position: absolute; bottom: 69px; left: 25px; width: 150px; height: 150px; border: 3px solid #28a745; border-radius: 5px; box-shadow: 0 4px 10px rgba(40, 167, 69, 0.2);"></div>
    
                <canvas id="canvas" class='mb-4' height="330px" style="width: 100%;"></canvas> 
                <input  type='hidden' id='location_mo' name='location' value='' required>
                <input  type='hidden' id='location_lat' name='location_lat' value='' required>
                <input  type='hidden' id='location_long' name='location_long' value='' required>
                <input type="file" id="imageInput" name="image" accept="image/*"  hidden >
         
                <div class="location-info">
                    <small id='map_reference'></small>
                    <div id='hub_info' class="hub-info"></div>
                </div>
              </div>
            </div>
            <div class="col-md-12 col-sm-12">
              <div class=" text-center">
                <button id="captureButton" 
                class="btn btn-primary btn-sm btn-fill rounded-circle p-3" 
                onclick="return false;">
                <i class="ti-camera"></i>
                </button>
                
                <button id="retakeButton" onclick="return false;"  style='font-size:10px;' class=" btn-smbtn btn-danger btn-fill ">
                    <i class="ti-reload"></i> <small>Retake Photo</small>
                </button>
                
                <button id="submitButton" type="submit" style='font-size:10px;'  class="btn-sm btn btn-success btn-fill">
                    <i class="ti-check"></i><small> Submit</small>
                </button>
              </div>
          </div>
          </div>
        </div>
        </form>
      </div>
    </div>
  </div>

  <script>
    document.getElementById("captureButton").disabled = true;
    
    // Hub locations from Laravel (make sure this is passed from your controller)
    const hubLocations = @json($hubLocations ?? []);
    let userPosition = null;
    let map = null;
    let userMarker = null;
    let hubMarkers = [];
    let radiusCircles = []; // Array to store radius circles

    function getLocation() {
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(success, error);
      } else { 
        console.error("Geolocation is not supported by this browser.");
      }
    }
    
    function success(position) {
        userPosition = position;
        
        // Get address from coordinates
        var geocodeUrl = "https://maps.googleapis.com/maps/api/geocode/json?latlng=" + 
            position.coords.latitude + "," + position.coords.longitude + 
            "&key=AIzaSyBZw51f1ZyJIjCbkNH2rU0Ze5nOiOBsIuE";
            
        fetch(geocodeUrl)
        .then(response => response.json())
        .then(data => {
            if (data.results && data.results.length > 0) {
                document.getElementById("location_mo").value = data.results[0].formatted_address;
                document.getElementById("map_reference").innerHTML = data.results[0].formatted_address;
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });

        document.getElementById("location_lat").value = position.coords.latitude;
        document.getElementById("location_long").value = position.coords.longitude;

        // Enable capture button when location is available
        const locationLatElement = document.getElementById("location_lat");
        if (locationLatElement && locationLatElement.value.trim() !== "") {
            document.getElementById("captureButton").disabled = false;
        } else {
            document.getElementById("captureButton").disabled = true;
        }

        // Update hub info display
        updateHubInfo(position.coords.latitude, position.coords.longitude);

        // Initialize map with user location and hubs
        initializeMapWithHubs(position.coords.latitude, position.coords.longitude);
    }

    function updateHubInfo(userLat, userLon) {
        const hubInfoDiv = document.getElementById('hub_info');
        
        if (!hubLocations || hubLocations.length === 0) {
            hubInfoDiv.innerHTML = '<span style="color: #007bff;">✅ You are allowed to access the camera for attendance.</span>';
            return;
        }

        // Calculate distance to assigned hub
        const hub = hubLocations[0]; // Assuming first hub is the assigned one
        const distance = calculateDistance(userLat, userLon, parseFloat(hub.lat), parseFloat(hub.long));
        const isInRange = distance <= 10; // 10 meter radius

        hubInfoDiv.innerHTML = `
            <div style="color: ${isInRange ? '#28a745' : '#dc3545'};">
                ${isInRange ? '✅' : '📍'} ${hub.hub_name} (${hub.hub_code})
                <br>Distance: ${Math.round(distance)}m ${isInRange ? '(In Range)' : '(Out of Range)'}
            </div>
        `;
    }

    function calculateDistance(lat1, lon1, lat2, lon2) {
        const R = 6371000; // Earth's radius in meters
        const dLat = (lat2 - lat1) * Math.PI / 180;
        const dLon = (lon2 - lon1) * Math.PI / 180;
        const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                  Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                  Math.sin(dLon/2) * Math.sin(dLon/2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
        return R * c;
    }

    function initializeMapWithHubs(userLat, userLon) {
        const mapProp = {
            center: new google.maps.LatLng(userLat, userLon),
            zoom: 19,
            disableDefaultUI: true,
            zoomControl: true,
            streetViewControl: false,
            fullscreenControl: true
        };

        map = new google.maps.Map(document.getElementById("googleMap"), mapProp);

        // Add user location marker (blue)
        userMarker = new google.maps.Marker({
            position: new google.maps.LatLng(userLat, userLon),
            map: map,
            title: "Your Location",
            icon: {
                url: "data:image/svg+xml;charset=UTF-8," + encodeURIComponent(`
                    <svg width="30" height="30" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="15" cy="15" r="12" fill="#007bff" stroke="white" stroke-width="3"/>
                        <circle cx="15" cy="15" r="6" fill="white"/>
                    </svg>
                `),
                scaledSize: new google.maps.Size(30, 30),
                anchor: new google.maps.Point(15, 15)
            }
        });

        // Add hub markers and radius circles
        hubLocations.forEach(hub => {
            const hubLat = parseFloat(hub.lat);
            const hubLon = parseFloat(hub.long);
            
            if (!isNaN(hubLat) && !isNaN(hubLon)) {
                const distance = calculateDistance(userLat, userLon, hubLat, hubLon);
                const isInRange = distance <= 10;
                
                // Create 10-meter radius circle around hub
                const radiusCircle = new google.maps.Circle({
                    strokeColor: isInRange ? '#28a745' : '#dc3545',
                    strokeOpacity: 0.8,
                    strokeWeight: 2,
                    fillColor: isInRange ? '#28a745' : '#dc3545',
                    fillOpacity: 0.15,
                    map: map,
                    center: new google.maps.LatLng(hubLat, hubLon),
                    radius: 10 // 10 meters radius
                });
                
                radiusCircles.push(radiusCircle);
                
                const hubMarker = new google.maps.Marker({
                    position: new google.maps.LatLng(hubLat, hubLon),
                    map: map,
                    title: `${hub.hub_name} (${Math.round(distance)}m away)`,
                    icon: {
                        url: "data:image/svg+xml;charset=UTF-8," + encodeURIComponent(`
                            <svg width="25" height="35" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12.5 0C5.6 0 0 5.6 0 12.5c0 10.9 12.5 22.5 12.5 22.5S25 23.4 25 12.5C25 5.6 19.4 0 12.5 0z" fill="${isInRange ? '#28a745' : '#dc3545'}"/>
                                <circle cx="12.5" cy="12.5" r="8" fill="white"/>
                                <text x="12.5" y="17" font-family="Arial" font-size="10" text-anchor="middle" fill="${isInRange ? '#28a745' : '#dc3545'}">H</text>
                            </svg>
                        `),
                        scaledSize: new google.maps.Size(25, 35),
                        anchor: new google.maps.Point(12.5, 35)
                    }
                });

                // Add info window for hub details
                const infoWindow = new google.maps.InfoWindow({
                    content: `
                        <div style="font-size: 12px;">
                            <strong>${hub.hub_name}</strong><br>
                            Code: ${hub.hub_code}<br>
                            Status: ${hub.hub_status}<br>
                            Distance: ${Math.round(distance)}m<br>
                            Allowed Range: 10m<br>
                            ${isInRange ? '<span style="color: #28a745;">✅ In Range</span>' : '<span style="color: #dc3545;">❌ Out of Range</span>'}
                        </div>
                    `
                });

                hubMarker.addListener('click', () => {
                    infoWindow.open(map, hubMarker);
                });

                hubMarkers.push(hubMarker);
            }
        });

        // Adjust map bounds to show both user and hub locations with radius
        if (hubLocations.length > 0) {
            const bounds = new google.maps.LatLngBounds();
            bounds.extend(new google.maps.LatLng(userLat, userLon));
            
            hubLocations.forEach(hub => {
                const hubLat = parseFloat(hub.lat);
                const hubLon = parseFloat(hub.long);
                if (!isNaN(hubLat) && !isNaN(hubLon)) {
                    // Extend bounds to include the hub and its 10m radius
                    const radiusInDegrees = 10 / 111320; // Convert 10 meters to degrees (approximate)
                    bounds.extend(new google.maps.LatLng(hubLat + radiusInDegrees, hubLon + radiusInDegrees));
                    bounds.extend(new google.maps.LatLng(hubLat - radiusInDegrees, hubLon - radiusInDegrees));
                }
            });
            
            map.fitBounds(bounds);
            
            // Set appropriate zoom level for better visibility
            google.maps.event.addListenerOnce(map, 'bounds_changed', function() {
                if (map.getZoom() > 20) {
                    map.setZoom(20);
                } else if (map.getZoom() < 18) {
                    map.setZoom(18);
                }
            });
        }
    }
    
    function error() {
      console.error("Sorry, no position available. Please enable location and refresh.");
    }

    // Camera and capture functionality (existing code)
    var name = {!! json_encode(auth()->user()->name ?? 'User') !!};
    const imageInput = document.getElementById('imageInput');
    const video = document.getElementById('video');
    const captureButton = document.getElementById('captureButton');
    const retakeButton = document.getElementById('retakeButton');
    const submitButton = document.getElementById('submitButton');
    const canvas = document.getElementById('canvas');
    const ctx = canvas.getContext('2d');
    const alertBox = document.getElementById('alert');
  
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
  
    function capturePhoto() {
      const now = new Date();
      var dateTimeString = now.toLocaleString();
      var datetime = dateTimeString;
      var lat_po = document.getElementById("location_lat").value;
      var long_po = document.getElementById("location_long").value;
      
      ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

      const address = document.getElementById("location_mo").value;

      ctx.font = "15px Arial";
      ctx.fillStyle = "white";
      ctx.textBaseline = "top";
      ctx.shadowColor = "black";
      ctx.shadowBlur = 4;
      ctx.strokeStyle = "black";
      ctx.lineWidth = 2;

      ctx.strokeText("Name: "+ name, 5, 15);
      ctx.fillText("Name: "+ name, 5, 15);
      ctx.font = "10px Arial";
      
      ctx.strokeText("Lat: "+ lat_po, 5, 35);
      ctx.fillText("Lat: "+ lat_po, 5, 35);
      ctx.strokeText("Long: "+ long_po, 5, 45);
      ctx.fillText("Long: "+ long_po, 5, 45);
      ctx.strokeText(datetime, 5, 55);
      ctx.fillText(datetime, 5, 55);
      
      wrapText(ctx, "Address: "+address, 5, 65, canvas.width - 60, 10);
      
      canvas.toBlob((blob) => {
            const file = new File([blob], 'captured-image.png', { type: 'image/png' });
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            imageInput.files = dataTransfer.files;
        });
        
      canvas.style.display = 'block';
      video.style.display = 'none';
      captureButton.style.display = 'none';
      retakeButton.style.display = 'inline-block';
      submitButton.style.display = 'inline-block';
      if(alertBox) alertBox.style.display = 'block';
    }
  
    function wrapText(context, text, x, y, maxWidth, lineHeight) {
      const words = text.split(' ');
      let line = '';

      for (let n = 0; n < words.length; n++) {
        const testLine = line + words[n] + ' ';
        const metrics = context.measureText(testLine);
        const testWidth = metrics.width;

        if (testWidth > maxWidth && n > 0) {
          context.strokeText(line, x, y);
          context.fillText(line, x, y);
          line = words[n] + ' ';
          y += lineHeight;
        } else {
          line = testLine;
        }
      }
      context.strokeText(line, x, y);
      context.fillText(line, x, y);
    }
    
    function retakePhoto() {
      canvas.style.display = 'none';
      video.style.display = 'block';
      captureButton.style.display = 'inline-block';
      retakeButton.style.display = 'none';
      submitButton.style.display = 'none';
      if(alertBox) alertBox.style.display = 'none';
    }
  
    startCamera();
    captureButton.addEventListener('click', capturePhoto);
    retakeButton.addEventListener('click', retakePhoto);

  </script>
  
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBZw51f1ZyJIjCbkNH2rU0Ze5nOiOBsIuE&callback=getLocation"></script>