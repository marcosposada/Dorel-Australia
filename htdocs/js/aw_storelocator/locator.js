var awStoreLocatorGoogleMap = Class.create({
    
    initialize: function(config, location) {
        this.latitude = location.latitude ? location.latitude : config.latitude;
        this.longtitude = location.longtitude ? location.longtitude : config.longtitude;
        this.countries = config.countries;
        this.config = config;
        this.location = location;
        google.maps.event.addDomListener(window, 'load', this.initGoogleMap.bind(this));
    },
     
    initGoogleMap: function() {
        this.myLatlng = new google.maps.LatLng(this.latitude,this.longtitude);
        var mapOptions = {         
            zoom: this.location.zoom ? parseInt(this.location.zoom) : 6,
            center: this.myLatlng,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        this.map = new google.maps.Map($('google-map-contents'), mapOptions);
        this.marker = new google.maps.Marker({
            position: this.myLatlng,
            draggable: true,
            map: this.map
        });
        google.maps.event.addListener(this.marker, 'dragend', function(event) {            
            this.updateCoords(event.latLng.lat(), event.latLng.lng());
        }.bind(this));
        google.maps.event.addListener(this.map, 'zoom_changed', function() {
            document.getElementById('location_zoom').value = this.map.getZoom();
        }.bind(this));
        this.monitorAddress();
    },  
    
    
    processSearchResults: function(results, options) {
        if(results.length > 0) {
           var location = new google.maps.LatLng(results[0].geometry.location.lat(),results[0].geometry.location.lng());
           this.marker.setPosition(location);
           this.map.panTo(location);
           this.map.setZoom(15);
           this.map.setZoom(16);
           this.updateCoords(results[0].geometry.location.lat(), results[0].geometry.location.lng());    
           $('storelocator-messages').setStyle({display: 'none'});  
        } else {
            $('storelocator-messages').down('span').update(this.config.noresult);
            $('storelocator-messages').setStyle({display: 'block'});             
        }
    },
    
    updateCoords: function(lat, lng) {
        document.getElementById('location_latitude').value = lat;
        document.getElementById('location_longtitude').value = lng;
    },
    
    monitorAddress: function() {
        Event.observe('aw-storelocator-search', 'click', function(event) {
            if($('location_city').value.empty() || $('location_street').value.empty()) {
                $('storelocator-messages').down('span').update(this.config.fields);
                $('storelocator-messages').setStyle({display: 'block'});
                return;
            }

            var query = [];
            var country = $('location_country');
            query.push(this.countries[country.options[country.selectedIndex].value]);

            var region = null;
            if($('location_billing:region').visible()) {
                region = $('location_billing:region').value;
            } else {
                var select = $('location_billing:region_id');
                region = select[select.selectedIndex].title;
            }
            query.push(region);
            query.push($('location_city').value);
            query.push($('location_street').value);
            var request = {
                query: query.join(' ')
            };
            var service = new google.maps.places.PlacesService(this.map);
            service.textSearch(request, this.processSearchResults.bind(this));
        }.bind(this));
     
    }
    
    
});
