<!DOCTYPE html>
<html>
<head>
    <title>Iframe Test</title>
</head>
<body>

    <h1>Main Page</h1>

    <input type="text" id="lotNumberInput" placeholder="Enter Lot Number">
    <button id="checkLotButton">Check Lot</button>

    <h2>API Response:</h2>
    <div id="apiResponseContainer"></div>

    <iframe id="copartIframe" src="http://www.copart.com/public/data/lotdetails/solr/84957074" style="width: 100%; height: 400px; border: 1px solid #ccc;"></iframe>


</body>
</html>