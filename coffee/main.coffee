lat = 37.4204
lng = -122.1031
timeout = 5000

#settings
radius = 5
limit = 20
msr = 1
#cookie exp.
exp_days = 365

if not getCookie 'unit'
    setCookie 'unit', unit, exp_days
else
    unit = getCookie 'unit'

if  not getCookie 'radius'
    setCookie 'radius' , radius, exp_days
else
    radius = getCookie 'radius'

if  not getCookie 'msr'
    setCookie 'msr' , msr, exp_days
else
    msr = getCookie 'msr'


$ ->
    # alert 'jQuery is Here'
    # handlebars.js
    source   = $("#entry-template").html()
    template = Handlebars.compile source
    
    if "geolocation" of navigator
        navigator.geolocation.getCurrentPosition (position) ->
            lat = position.coords.latitude
            lng = position.coords.longitude
            return

    callback = ->
        console.log "Lng:#{ lng } Lat:#{ lat}"
        url = "get/#{lat}/#{lng}/#{radius}/#{limit}/#{msr}"
        $.getJSON url, (data) ->
            console.log data
            context = shops : data
            html = template context
            $('#shops-table').html html

    $('#shops-table').on 'click', 'table tbody tr', ->
        poi_id = ($(this).attr 'id').replace('_', '')
        # alert poi_id
        url = "poi/#{poi_id}/#{lat}/#{lng}/#{msr}"

        window.location.href = url

    setTimeout callback, timeout
