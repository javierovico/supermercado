<!DOCTYPE html>
<html>
<head>
    <!--Import Google Icon Font-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!--Import materialize.css-->
    <link type="text/css" rel="stylesheet" href="css/bootstrap.min.css"  media="screen,projection"/>

    <!--Let browser know website is optimized for mobile-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <script src="js/app.js" defer></script>
    <script src="js/jquery-3.4.0.min.js"></script>
    <script src="js/bootstrap.js"></script>
</head>

<body>
    <div id="app">
        <h1 style="text-align: center">iFrame vPos</h1>
        <div style="height: 700px; width: 100%; margin: auto" id="iframe-container"></div>
    </div>
    <script>
        const styles = {
            "form-background-color": "#001b60",
            "button-background-color": "#4faed1",
            "button-text-color": "#fcfcfc",
            "button-border-color": "#dddddd",
            "input-background-color": "#fcfcfc",
            "input-text-color": "#111111",
            "input-placeholder-color": "#111111"
        };

        window.onload = function (){
            Bancard.Checkout.createForm('iframe-container', '{{$processId}}' , styles);
        }
    </script>
</body>
</html>
