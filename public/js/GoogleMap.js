$(document).ready(function () {
  /* Google Map */
  
  function mapInitialize() {
    //var mapStyle =  [{"featureType":"administrative","elementType":"labels.text.fill","stylers":[{"color":"#444444"}]},{"featureType":"landscape","elementType":"all","stylers":[{"color":"#f2f2f2"}]},{"featureType":"poi","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"all","stylers":[{"saturation":-100},{"lightness":45}]},{"featureType":"road.highway","elementType":"all","stylers":[{"visibility":"simplified"}]},{"featureType":"road.arterial","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"transit","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"water","elementType":"all","stylers":[{"color":"#f7d991"},{"visibility":"on"}]}];
    
    var maps = $("body").find(".google-map");
    
    $(maps).each(function() {

      var elem = $(this);

      var yourLatitude = parseFloat(elem.attr('data-lat'));
      var yourLongitude = parseFloat(elem.attr('data-lon'));

      var myOptions = {
        zoom: 11,
        center: new google.maps.LatLng(yourLatitude,yourLongitude-0.01),
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        mapTypeControl: false,
        panControl: false,
        zoomControl: false,
        scaleControl: false,
        streetViewControl: false,
        scrollwheel: false,
        //styles: mapStyle
      };
      var map = new google.maps.Map(elem.get(0), myOptions);

      var image = new google.maps.MarkerImage('img/map-location.png');
      var myLatLng = new google.maps.LatLng(yourLatitude,yourLongitude);
      var myLocation = new google.maps.Marker({
        position: myLatLng,
        map: map,
        icon: image
      });

    });

  }
  
  google.maps.event.addDomListener(window, 'load', mapInitialize());


  $('.jps_data_param').click(function(e) {
    var map_div = $(this).parents(".signal_map").find(".google-map");

    mapRestart(map_div);
  });

  function mapRestart(elem) {
    var elem = elem;

    var yourLatitude = parseFloat(elem.attr('data-lat'));
    var yourLongitude = parseFloat(elem.attr('data-lon'));

    var myOptions = {
      zoom: 11,
      center: new google.maps.LatLng(yourLatitude,yourLongitude-0.01),
      mapTypeId: google.maps.MapTypeId.ROADMAP,
      mapTypeControl: false,
      panControl: false,
      zoomControl: false,
      scaleControl: false,
      streetViewControl: false,
      scrollwheel: false,
        //styles: mapStyle
    };
    var map = new google.maps.Map(elem.get(0), myOptions);

    var image = new google.maps.MarkerImage('img/map-location.png');
    var myLatLng = new google.maps.LatLng(yourLatitude,yourLongitude);
    var myLocation = new google.maps.Marker({
      position: myLatLng,
      map: map,
      icon: image
    });
  }

});