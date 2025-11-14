<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StemWijzer - Standen</title>

    <!-- Bootstrap-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"
        defer></script>

    <!--Plotly.js-->
    <script src="https://cdn.plot.ly/plotly-3.1.0.min.js" charset="utf-8" defer></script>

    <link rel="stylesheet" href="./style.css">
    <script src="../../js/script.js" defer></script>
    <script src="./js/graphs.js" defer></script>
</head>

<body>
<!-- header-->
<nav id="navbar" class="navbar navbar-expand-sm bg-body-tertiary mx-auto sticky-top" style="background-color:mediumseagreen !important; width: 50%; border-radius: 10px;">
    <div class="container-fluid">
        <a class="navbar-brand mb-0 h1" href="./partijen.html">StemWijzer</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                <li class="nav-item">
                    <a class="nav-link" href="./partijen.php">Partijen</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="./standen.php">Standen</a>
                </li>

                <!-- <li class="nav-item">
                    <a class="nav-link" href="inlog.html">Inloggen</a>
                </li> -->

            </ul>
        </div>
        <img src="./media/logo/scale_logo.png" alt="logo" width="50px" height="50px" class="d-inline-block align-text-top">
    </div>
</nav>


<div id="barchart" style="height: 1000px; width: 500px;"></div>
<div id="piechart" style="height: 500px; width: 500px;"></div>
<div id="sunburstchart"></div>
</body>

</html>