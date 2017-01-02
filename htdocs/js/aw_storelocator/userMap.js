var awStoreLocatorUserMap = Class.create({
    location: [],
    marker: [],
    window: [],
    icon: [],

    initialize: function (config, baseConfig) {
        this.config = config;
        this.baseConfig = baseConfig;
        google.maps.event.addDomListener(window, 'load', this.initGoogleMap.bind(this));
    },

    initGoogleMap: function () {
        /* Init google map default location */
        if (this.config.items.length > 0) {
            var loc = this.config.items[0];
            var defaultLatlng = new google.maps.LatLng(loc.latitude, loc.longtitude);
            var options = {
                zoom: parseInt(loc.zoom),
                center: defaultLatlng,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };
            this.map = new google.maps.Map($('aw-storelocator-google-map'), options);
            this.highlightNavigation(loc.location_id);
        } else {
            var zeroLatlng = new google.maps.LatLng(0, 0);
            var mapOptions = {
                zoom: 2,
                center: zeroLatlng,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };
            this.map = new google.maps.Map($('aw-storelocator-google-map'), mapOptions);
        }

        for (i = 0; i < this.config.items.length; i++) {
            (function () {
                var loc = this.config.items[i];
                this.location[loc.location_id] = new google.maps.LatLng(loc.latitude, loc.longtitude);

                var markerConfig = {
                    position: this.location[loc.location_id],
                    map: this.map
                };
                if (this.getIconUrl(loc)) {
                    this.icon[loc.location_id] = {
                        url: this.getIconUrl(loc),
                        size: new google.maps.Size(20, 30),
                        scaledSize: new google.maps.Size(20, 30)
                    };
                    markerConfig.icon = this.icon[loc.location_id];
                }
                this.marker[loc.location_id] = new google.maps.Marker(markerConfig);

                this.window[loc.location_id] = new google.maps.InfoWindow({
                    content: this.parseTemplate(loc),
                    identity: loc.location_id
                });

                $('aw-storelocator-more-details-' + loc.location_id).observe('click',
                    this.listenMoreDetails.bindAsEventListener(this, loc, 'aw-storelocator-more-details-' + loc.location_id)
                );

                google.maps.event.addListener(this.marker[loc.location_id], 'click', function () {

                    try {
                        this.window.each(function (w) {
                            w.close();
                        });
                    } catch (e) {

                    }
                    this.window[loc.location_id].open(this.map, this.marker[loc.location_id]);
                    this.clearNavigation();
                    this.highlightNavigation(loc.location_id);
                    this.panTo(loc.location_id);

                }.bind(this));

            }.bind(this))();
        }
        this.listenNavigation();
        this.listenSearch();

        var address = document.getElementById('aw-storelocator-search-block-address');
        var autocomplete = new google.maps.places.Autocomplete(address);
        google.maps.event.addListener(autocomplete, 'place_changed', function () {
            var place = autocomplete.getPlace();

            document.getElementById('latitude').value = place.geometry.location.lat();
            document.getElementById('longitude').value = place.geometry.location.lng();
        });

        if (!$('aw-storelocator-search-block-address').value) {
            $('aw-storelocator-search-block-radius', 'aw-storelocator-search-block-measurement').invoke('disable');
        }
    },

    listenMoreDetails: function (event, location, element) {
        var effect = new PopupEffect($(element).id, {className: "popup_effect1"});
        var template = this.parseMoreDetailsTemplate(location);

        Dialog.alert(template,{okLabel: 'Close', className:'alphacube', width: 400, height:null, showEffect:effect.show.bind(effect), hideEffect:effect.hide.bind(effect), destroyOnClose: true})
    },

    clearNavigation: function () {
        $$('.aw-storelocator-navigation-item').each(function (el) {
            el.removeClassName('aw-storelocator-item-selected');
        });
        return this;
    },

    highlightNavigation: function (id) {
        try {
            $('aw-storelocator-navigation-item-' + id).addClassName('aw-storelocator-item-selected');
        } catch (e) {

        }
        return this;
    },

    listenNavigation: function () {
        document.observe('click', function (event) {
            var element = Event.element(event);

            var parent = null;
            if (element.hasClassName('aw-storelocator-navigation-item')) {
                parent = element;
            }
            else {
                parent = element.up('.aw-storelocator-navigation-item');
            }

            if (parent) {
                this.clearNavigation();
                parent.addClassName('aw-storelocator-item-selected');
                this.panTo(this.getIdentity(parent.id));
                /* close info windows if move to other navigation items */
                this.window.each(function (w) {
                    if (w.identity != this.getIdentity(parent.id)) {
                        w.close();
                    }
                }.bind(this));
            }
        }.bind(this));
    },

    listenSearch: function () {
        Event.observe('aw-storelocator-find-location', 'click', function(event) {
            if (navigator.geolocation) {
                var locationTimeout = setTimeout(this.searchError.bind(this), 10000);

                navigator.geolocation.getCurrentPosition(function(position) {
                    var geocoder = new google.maps.Geocoder();
                    var latlng = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);

                    geocoder.geocode({'latLng': latlng}, function(results, status) {
                        if (status == google.maps.GeocoderStatus.OK) {
                            if (results[1]) {
                                document.getElementById('aw-storelocator-search-block-address').value = results[1].formatted_address;
                                this.toggleSearchSelect();
                            } else {
                                alert('No results found');
                            }
                        } else {
                            alert('Geocoder failed due to: ' + status);
                        }
                    }.bind(this));

                    document.getElementById("latitude").value = position.coords.latitude;
                    document.getElementById("longitude").value = position.coords.longitude;

                    clearTimeout(locationTimeout);
                }.bind(this), function(error) {
                    clearTimeout(locationTimeout);
                    this.searchError();
                }.bind(this));
            } else {
                this.searchError();
            }
        }.bind(this));
        Event.observe('aw-storelocator-search-block-address', 'keyup', function(event) {
            this.toggleSearchSelect();
        }.bind(this));
    },

    toggleSearchSelect: function () {
        if ($('aw-storelocator-search-block-address').value) {
            $('aw-storelocator-search-block-radius', 'aw-storelocator-search-block-measurement').invoke('enable');
        } else {
            $('aw-storelocator-search-block-radius', 'aw-storelocator-search-block-measurement').invoke('disable');
        }
    },

    searchError: function () {
        alert('Error: The Geolocation service failed. Please try again.');
    },

    panTo: function (id) {
        this.map.panTo(this.location[id]);
        this.map.setZoom(parseInt(this.getLocationById(id).zoom))
    },

    getLocationById: function (id) {
        for (i = 0; i < this.config.items.length; i++) {
            if (id == this.config.items[i].location_id) {
                return this.config.items[i];
            }
        }
        return {zoom: 16};
    },

    getIdentity: function (str) {
        return /\d+/i.exec(str)[0];
    },

    parseTemplate: function (location) {
        if (location.image) {
            var img = document.createElement('img');
            img.src = this.baseConfig.store_img_tpl.replace('{{id}}', location.location_id).replace("{{name}}", location.image);
            $$('.aw-storelocator-store-image')[0].update(null);
            $$('.aw-storelocator-store-image')[0].appendChild(img);
        }
        var template = $$('.aw-storelocator-template')[0].innerHTML;
        template = template.replace("{{description}}", location.description ? location.description : '')
            .replace("{{street}}", location.street ? location.street : '')
            .replace("{{city}}", location.city ? location.city : '')
            .replace("{{country}}", location.country ? this.baseConfig.countries[location.country] : '')
            .replace("{{phone}}", location.phone    ? location.phone : '')
            .replace("{{state}}", location.state ? this.getStateCode(location.state) : '')
            .replace("{{zip}}", location.zip ? location.zip : '')
        ;
        return template
    },

    parseMoreDetailsTemplate: function (location) {
        if (location.hours != '') {
            $$('.aw-storelocator-store-opening-hours')[0].show();
        } else {
            $$('.aw-storelocator-store-opening-hours')[0].hide();
        }

        if ($$('.aw-storelocator-details-store-image img')[0]) {
            $$('.aw-storelocator-details-store-image img')[0].remove();
        }

        if (location.image) {
            var img = document.createElement('img');
            img.src = this.baseConfig.store_img_tpl.replace('{{id}}', location.location_id).replace("{{name}}", location.image);
            $$('.aw-storelocator-details-store-image')[0].update(null);
            $$('.aw-storelocator-details-store-image')[0].appendChild(img);
        }

        var template = $$('.aw-storelocator-details-template')[0].innerHTML;
        template = template.replace("{{title}}", location.location_title ? location.location_title : '')
            .replace("{{description}}", location.description ? location.description : '')
            .replace("{{street}}", location.street ? location.street : '')
            .replace("{{city}}", location.city ? location.city : '')
            .replace("{{country}}", location.country ? this.baseConfig.countries[location.country] : '')
            .replace("{{state}}", location.state ? this.getStateCode(location.state) : '')
            .replace("{{zip}}", location.zip ? location.zip : '')
            .replace("{{phone}}", location.phone ? location.phone : '')
            .replace("{{hours}}", location.hours ? location.hours : '')
        ;

        return template
    },

    getIconUrl: function (location) {
        if (!location.custom_marker) {
            return null;
        }
        return this.baseConfig.marker_img_tpl
            .replace('{{id}}', location.location_id)
            .replace("{{name}}", location.custom_marker)
            ;
    },


    getStateCode: function (code) {
        var regions = this.baseConfig.regions;
        for (z = 0; z < regions.items.length; z++) {
            var region = regions.items[z];
            if (region.region_id == code) {
                return region.code;
            }
        }
        return code;
    }

});
