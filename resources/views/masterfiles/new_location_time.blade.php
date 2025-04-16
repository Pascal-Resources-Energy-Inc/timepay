
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDeSpk2-I61V7TFFomaxqOWv-Ir2ZeYkQM&callback=initMap&libraries=places&v=weekly" async></script>
<style>
    #map {
      height: 400px;
      width: 100%;
    }
  </style>
<div class="modal fade" id="newLocationMaps" tabindex="-1" role="dialog" aria-labelledby="newLocationMapsLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="newLocationMapsLabel">New Location</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form method='POST' action='store-location-time' onsubmit='show()' enctype="multipart/form-data">
				@csrf
				<div class="modal-body">
					<div class="row">
						<div class='col-md-12 form-group'>
							Location name:
							<input type="text" name='location_name' class="form-control" required>
						</div>
					</div>
                    <div class="row">
                        <div class="col-md-12">
                          <!-- Input fields for latitude and longitude -->
                          <div class="mb-3">
                            <label for="latitude" class="form-label">Latitude</label>
                            <input type="text" class="form-control" id="latitude" name='latitude' readonly required>
                          </div>
                          <div class="mb-3">
                            <label for="longitude" class="form-label">Longitude</label>
                            <input type="text" class="form-control" id="longitude"  name='longtitude' readonly required>
                          </div>
                          <div class="mb-3">
                            <label for="search" class="form-label">Search Location</label>
                            <input type="text" class="form-control" id="search" placeholder="Search a location">
                          </div>
                        </div>
                        <div class="col-md-12">
                            <!-- Google Maps Display -->
                            <div id="map"></div>
                          </div>
                      </div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary">Save</button>
				</div>
			</form>
		</div>
	</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
    let map, marker, searchBox;

    // Initialize the map
    function initMap() {
      // Try to get the user's current location
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function (position) {
          const userLocation = {
            lat: position.coords.latitude,
            lng: position.coords.longitude,
          };

          // Create a map centered at the user's location
          map = new google.maps.Map(document.getElementById("map"), {
            zoom: 20,
            center: userLocation,
          });

          // Create a marker at the user's location
          marker = new google.maps.Marker({
            position: userLocation,
            map: map,
            draggable: true,
          });

          // Update the latitude and longitude input fields
          document.getElementById("latitude").value = userLocation.lat;
          document.getElementById("longitude").value = userLocation.lng;

          // Add click event listener to the map
          google.maps.event.addListener(map, "click", function (event) {
            const lat = event.latLng.lat();
            const lng = event.latLng.lng();

            // Set the marker position and update the input fields
            marker.setPosition(event.latLng);
            document.getElementById("latitude").value = lat;
            document.getElementById("longitude").value = lng;
          });

          // Add event listener to the marker for drag events
          google.maps.event.addListener(marker, "dragend", function () {
            const lat = marker.getPosition().lat();
            const lng = marker.getPosition().lng();
            document.getElementById("latitude").value = lat;
            document.getElementById("longitude").value = lng;
          });
        });
      } else {
        alert("Geolocation is not supported by this browser.");
      }

      // Initialize the search box
      searchBox = new google.maps.places.SearchBox(document.getElementById("search"));

      // Listen for the event when a user selects a place from the search box
      searchBox.addListener("places_changed", function () {
        const places = searchBox.getPlaces();
        if (places.length === 0) return;

        // Get the first place result
        const place = places[0];
        const lat = place.geometry.location.lat();
        const lng = place.geometry.location.lng();

        // Set the map center to the place's location
        map.setCenter(place.geometry.location);

        // Set the marker position to the selected place
        marker.setPosition(place.geometry.location);

        // Update the latitude and longitude input fields
        document.getElementById("latitude").value = lat;
        document.getElementById("longitude").value = lng;
      });
    }
  </script>