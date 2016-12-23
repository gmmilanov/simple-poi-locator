<!DOCTYPE html>
<html>
<head>
    <title></title>
    <script type="text/javascript" src='js/jquery-3.1.1.min.js'></script>
    <script type="text/javascript" src='js/handlebars.min-latest.js'></script>
    <script type="text/javascript" src='js/cookie-util.js'></script>
    <script type="text/javascript" src='js/main.js'></script>
</head>
<body>

<div id='lng'> </div>
<div id='lat'> </div>
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