<!DOCTYPE html>
<html>
<head>
  <!-- Include meta tag to ensure proper rendering and touch zooming -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Include jQuery Mobile stylesheets -->
  <script type="text/javascript" src='js/jquery-3.1.1.min.js'></script>
  <script type="text/javascript" src='js/handlebars.min-latest.js'></script>
</head>
<body>

<div data-role="page" id="pageone">
  <div data-role="header">
    <h1>Shop Locator</h1>
  </div>

  <div data-role="main" class="ui-content">
    <p>Shop Locator Mobile View</p>
    <p id='lng'></p> 
    <p id='lat'></p>
    <p id='err'></p> 
  </div>

  <div data-role="footer">
    <h1>Shop Locator Footer</h1>
  </div>
</div> 

<div id='shops-table'></div>

<script id="entry-template" type="text/x-handlebars-template">
  <table class="my-new-list" id="shops">
    <thead>
        <tr>
            <th>Name</th>
            <th>Address</th>
            <th>Distance KM</th>
        </tr>
    </thead>
    <tbody>
    {{#each shops}}
    <tr id='_{{id}}'>
        <td>{{name}}</td>
        <td>{{address}}</td>
        <td>{{distance}}</td>
    </tr>
    {{/each}}
    </tbody>
  </table>
</script>


<script type="text/javascript">

var source   = $("#entry-template").html();
var template = Handlebars.compile(source);

$(document).ready(function (e) {
	//One time snapshot
	navigator.geolocation.getCurrentPosition(
     processGeolocation,
     // Optional settings below
     geolocationError,
     {
         timeout: 20000,
         enableHighAccuracy: true,
         maximumAge: Infinity
     }
);  
});

function processGeolocation(position){
    lat = position.coords.latitude ;
    lng = position.coords.longitude ;
    $.getJSON('get/' + lng + '/' + lat + '/50/20/1', function(data){
            
            console.log(data);
            var context = {shops : data} ;
            var html    = template(context)
            console.log(html);
            
            //Display Shops
            $('#shops-table').html(html);
            
            $('#shops-table').on('click', ' table tbody tr', function(){
        // alert($(this).attr('id'));
        var shop_id = $(this).attr('id');
        //read http://stackoverflow.com/questions/2430936/whats-the-difference-between-window-location-and-document-location-in-javascrip
        //http://stackoverflow.com/questions/503093/how-do-i-redirect-to-another-page-in-jquery
        //http://stackoverflow.com/questions/7857878/window-location-vs-document-location
        window.location.href = 'shop/' + shop_id.replace('_', '') + '/' + lat + '/' + lng + '/1' ; 
    });
    })
    
}

function geolocationError(error)
{
	//alert('error gps');//
	console.log(error);
}
</script>
<style type="text/css">
    
    .my-new-list th{
        border : 2px solid #aaa;
    }
    .my-new-list td{
        border : 1px solid #000;
    }

    tbody tr:nth-child(odd) {background: #eee}

    tbody tr:hover{
        background: #ffc;
        cursor: pointer;
    }

</style>

</body>
</html>
