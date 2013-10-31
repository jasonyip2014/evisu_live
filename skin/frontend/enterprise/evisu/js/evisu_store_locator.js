var StoreLocator = {
    config : [],
    map : null,
    //mapOptions : null,
    sortedStores : null,
    //displayMode : null,
    geocoder : null,

    init : function(config)
    {
        this.config = config;
    },

    initOnReady : function() {
        var self = this;

        this.mapOptions = {
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            //disableDefaultUI: false,
            scrollwheel: false
        };
        this.geocoder = new google.maps.Geocoder();

        this.map = new google.maps.Map(document.getElementById("map-canvas"), this.mapOptions);
        this.map.setZoom(parseInt(self.config['default_zoom']));

        this.createMarkers();
        this.mapManualInicialize('manual');
        this.html5Geolocation();

        self.setEvents();

    },

    setEvents : function()
    {
        var self = this;
        //stores list
        jQuery(document).on('click', '.store-link', function(){StoreLocator.displayOnMap(jQuery(this))});

        //search
        jQuery('#search-form').on('submit', function(){StoreLocator.getStoreByAddress();return false;});
        var autocomplete = new google.maps.places.Autocomplete((document.getElementById('search-input')), {types: ['geocode']});
        google.maps.event.addListener(autocomplete, 'place_changed', function() {StoreLocator.getStoreByAddress()});

        google.maps.event.addListener(self.map, 'click', function(event) {
           self.closeAllInfoWindows();
        });
    },

    showMarkers : function(){
        self = this;
        var stores = self.config.stores;
        for(var storeId in stores)
        {
            if(stores.hasOwnProperty(storeId))
            {
                stores[storeId].marker.setMap(self.map);
            }
        }
    },

    createMarkers : function() {
        self = this;
        var stores = self.config.stores;
        for(var storeId in stores)
        {
            if(stores.hasOwnProperty(storeId))
            {
                contentString = '<div class="map-popup">' +
                    '<div class="name">' + stores[storeId].popup.name + '</div>' +
                    '<div class="address">' + stores[storeId].popup.address + '</div>' +
                    '<div class="telephone">' + stores[storeId].popup.telephone + '</div>' +
                    '<div class="times">' +
                    '<div class="times-heading">Opening Times</div>';
                for(var i = 0; i < stores[storeId].popup.times.length; i++)
                {
                    contentString += '<div class="row">' +
                        '<span class="col-1">' + stores[storeId].popup.times[i].days + '</span>' +
                        '<span class="col-2">' + stores[storeId].popup.times[i].times + '</span>' +
                        '</div>'
                }
                contentString +='</div>' +
                    '</div>';
                stores[storeId].infoWindow = new google.maps.InfoWindow({
                    content: contentString
                });
                stores[storeId].marker = new google.maps.Marker({
                    position: new google.maps.LatLng(stores[storeId].coordinates.lat, stores[storeId].coordinates.long),
                    map: null,
                    draggable: false,
                    animation: google.maps.Animation.DROP,
                    icon: self.config.skin_base_url + 'images/map-' + stores[storeId].type + '-pin-icon.png',
                    title : stores[storeId].popup.name
                });

                var infoWindow = stores[storeId].infoWindow;
                google.maps.event.addListener(stores[storeId].marker, 'click', function() {
                    self.closeAllInfoWindows();
                    infoWindow.open(self.map, this);
                    self.styleInfoWindow();
                });
            }
        }
    },

    html5Geolocation : function() {
        var self = this;
        // Try HTML5 geolocation
        if(navigator.geolocation)
        {
            navigator.geolocation.getCurrentPosition(function(position) {
                self.displayClosestStore(position.coords.latitude, position.coords.longitude);
            }, function() {
                console.log('error');
                self.html5GeolocationError(true);
            });
        } else {
            // Browser doesn't support Geolocation
            self.html5GeolocationError(false);
        }
    },

    html5GeolocationError : function(errorFlag)
    {
        var content;
        if (errorFlag) {
            content = 'Error: The Geolocation service failed.';
        } else {
            content = 'Error: Your browser doesn\'t support geolocation.';
        }
        //this.mapManualInicialize(content);
    },

    mapManualInicialize : function(content)
    {
        self = this;
        self.map.setCenter(new google.maps.LatLng(self.config['default_coordinates'].lat, self.config['default_coordinates'].long));
    },

    closeAllInfoWindows : function()
    {
        self = this;
        var stores = self.config.stores;
        for(var storeId in stores)
        {
            if(stores.hasOwnProperty(storeId))
            {
                stores[storeId].infoWindow.close();
            }
        }
    },

    styleInfoWindow : function()
    {
        var infoWindow = jQuery('.gm-style-iw');
        infoWindow.css({left:'135', top:'200'});
        infoWindow.next().remove();
        infoWindow.parent().addClass('auto-width-wind');
        var container = infoWindow.prev().children('div');

        jQuery.each(container, function(index, _div){
            if(index == 2 || index == 0)
            {
                _div.remove();
            }
            if(index == 1 || index == 3)
                jQuery(_div).addClass('auto-width-wind');
        });
    },

    displayOnMap : function(link)
    {
        self = this;

        var storeId = link.data('id');

        var store = self.config.stores[storeId];
        this.map.setCenter(new google.maps.LatLng(store.coordinates.lat, store.coordinates.long));
        this.map.setZoom(parseInt(store.zoom));
        self.closeAllInfoWindows();
        setTimeout(function(){
            self.config.stores[storeId].infoWindow.open(self.map, self.config.stores[storeId].marker);
            self.styleInfoWindow();
        }, 500);
        jQuery('html,body').animate({scrollTop:0},'normal');
        setTimeout(function(){
            self.displayClosestStore(store.coordinates.lat,store.coordinates.long);
        },500);
    },

    rad : function (x)
    {
        return x * Math.PI / 180;
    },

    displayClosestStore : function (lat, lng)
    {
        self = this;
        var var_R = 6371; // radius of earth in km
        var closest = null;
        var stores = self.config.stores;
        var_closest_distance = -1;
        for(var storeId in stores)
        {
            if(stores.hasOwnProperty(storeId))
            {
                var mlat = stores[storeId].coordinates.lat;
                var mlng = stores[storeId].coordinates.long;
                var dLat  = self.rad(mlat - lat);
                var dLong = self.rad(mlng - lng);
                var var_a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                    Math.cos(self.rad(lat)) * Math.cos(self.rad(lat)) * Math.sin(dLong / 2) * Math.sin(dLong / 2);
                var var_c = 2 * Math.atan2(Math.sqrt(var_a), Math.sqrt(1 - var_a));
                stores[storeId].distance = var_R * var_c;
                if(!closest || stores[storeId].distance < closest_distance) {
                    closest = storeId;
                    closest_distance = stores[storeId].distance;
                }
            }
        }
        self.map.setCenter(new google.maps.LatLng(stores[closest].coordinates.lat, stores[closest].coordinates.long));
        self.map.setZoom(parseInt(stores[closest].zoom));
        self.sortStoreList();
        //jQuery('#' + closest_type + closest).addClass('active');
    },

    getStoreByAddress: function()
    {
        self = this;
        var address = jQuery("#search-input").val();
        if (address != '')
        {
            self.geocoder.geocode( { 'address': address}, function(results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    self.displayClosestStore(results[0].geometry.location.lat(),results[0].geometry.location.lng())
                } else {
                    jQuery('#search-error').html("Bad request: " + status);
                }
            });
        }
    },

    getGroupedStores : function()
    {
        var self = this;
        if(!self.sortedStores)
        {
            self.sortedStores = [];

            var stores = self.config.stores;
            var i = 0;
            for(var storeId in stores)
            {
                if(stores.hasOwnProperty(storeId))
                {
                    self.sortedStores[i] = {};
                    self.sortedStores[i].id = storeId;
                    self.sortedStores[i].country = stores[storeId].country;
                    i++;
                }
            }
        }

        var sortFunction = function(a, b){
            stores = self.config.stores;
            if(stores[a.id].distance < stores[b.id].distance) return -1;
            if(stores[a.id].distance > stores[b.id].distance) return 1;
            return 0;
        };
        self.sortedStores.sort(sortFunction);
        var groupedStores = [];
        for(i = 0; i < self.sortedStores.length; i++)
        {
            if(!groupedStores[self.sortedStores[i].country])
            {
                groupedStores[self.sortedStores[i].country] = {};
            }
            groupedStores[self.sortedStores[i].country][self.sortedStores[i].id] = self.sortedStores[i].id;
        }
        return groupedStores;
    },
    sortStoreList : function()
    {
        var groupedStores = self.getGroupedStores();
        var content = '';
        for(var group in groupedStores)
        {
            if(groupedStores.hasOwnProperty(group))
            {
                content += '<div class="group">' +
                    '<div class="group-title">' + group + '</div>' +
                    '<div class="group-stores">';
                for(var storeId in groupedStores[group])
                {
                    if(groupedStores[group].hasOwnProperty(storeId))
                    {
                        content += '' +

                                '<div id="store-' + storeId + '" class="stores ' + self.config.stores[storeId].type + '">' +
                                    '<div class="store-name">' + self.config.stores[storeId].popup.name + '</div>' +
                                    '<div class="store-address">' + self.config.stores[storeId].popup.address + '</div>' +
                                    '<div class="store-phone">' + self.config.stores[storeId].popup.telephone + '</div>' +
                                    '<div class="store-link" data-id="' + storeId + '">View On Map</div>' +

                            '</div>';
                    }
                }
                content += '</div></div>';
            }
        }
        jQuery(".stores-section").html(content);
    }
};


jQuery(function($)
{
    //jQuery('.stores-filter').on('click', function(){StoreLocator.changeDisplayMode('store')});
    //jQuery('.stockist-filter').on('click', function(){StoreLocator.changeDisplayMode('stockist')});
    StoreLocator.initOnReady();


});

jQuery(window).on('load',function(){
    StoreLocator.showMarkers();
    //StoreLocator.sortStoreList();
});