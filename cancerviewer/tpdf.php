<?php
    $table1 = $_REQUEST["tablePDF"];
    $amp = $_REQUEST["AMP"];
    $del = $_REQUEST["DEL"];
    $down = $_REQUEST["DOWN"];
    $up = $_REQUEST["UP"];
    $hyper = $_REQUEST["HYPER"];
    $hypo = $_REQUEST["HYPO"];
    $init = '<!DOCTYPE html><html><head>
    <meta charset="utf-8">
        <style>
      /* disable text selection */
      svg *::selection {
         background : transparent;
      }

      svg *::-moz-selection {
         background:transparent;
      }

      svg *::-webkit-selection {
         background:transparent;
      }
      rect.selection {
        stroke          : #333;
        stroke-dasharray: 4px;
        stroke-opacity  : 0.5;
        fill            : transparent;
      }

      rect.cell-border {
        stroke: #eee;
        stroke-width:0.3px;
      }

      rect.cell-selected {
        stroke: rgb(51,102,153);
        stroke-width:0.5px;
      }

      rect.cell-hover {
        stroke: #F00;
        stroke-width:0.3px;
      }

      text.mono {
        font-size: 9pt;
        font-family: Consolas, courier;
        fill: #aaa;
      }

      text.text-selected {
        fill: #000;
      }

      text.text-highlight {
        fill: #c00;
      }
      text.text-hover {
        fill: #00C;
      }
      #tooltip {
        position: absolute;
        width: 200px;
        height: auto;
        padding: 10px;
        background-color: white;
        -webkit-border-radius: 10px;
        -moz-border-radius: 10px;
        border-radius: 10px;
        -webkit-box-shadow: 4px 4px 10px rgba(0, 0, 0, 0.4);
        -moz-box-shadow: 4px 4px 10px rgba(0, 0, 0, 0.4);
        box-shadow: 4px 4px 10px rgba(0, 0, 0, 0.4);
        pointer-events: none;
      }

      #tooltip.hidden {
        display: none;
      }

      #tooltip p {
        margin: 0;
        font-family: sans-serif;
        font-size: 12px;
        line-height: 20px;
      }
    </style>
    </meta>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Core CSS -->
    <link href="css/basic.css" rel="stylesheet">

    <!-- Custom CSS -->
    <style>
    body {
        padding-top: 70px;
        /* Required padding for .navbar-fixed-top. Remove if using .navbar-static-top. Change if height of navigation changes. */
    }
    </style>

    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>';

    $labels = '
    <div align="center">
    <div>
        <!--
        <table align="center">
            <tr>
                <td>
                    <div style="border-radius:5px 20px; background-color:'.$amp.'; width:60px;"><h5 style="color:white">AMP</h5></div>
                </td>
                <td>
                    <div style="border-radius:5px 20px; background-color:'.$del.'; width:90px;"><h5 style="color:white">HOMDEL</h5></div>
                </td>
                <td>
                    <div style="border-radius:5px 20px; background-color:'.$up.'; width:50px;"><h5 style="color:white">UP</h5></div>
                </td>
                <td>
                    <div style="border-radius:5px 20px; background-color:'.$down.'; width:60px;"><h5 style="color:white">DOWN</h5></div>
                </td>
                <td>
                    <div style="border-radius:5px 20px; background-color:'.$hyper.'; width:60px;"><h5 style="color:white">HYPER</h5></div>
                </td>
                <td>
                    <div style="border-radius:5px 20px; background-color:'.$hypo.'; width:50px;"><h5 style="color:white">HYPO</h5></div>
                </td>
                <td>
                    <div style="border-radius:5px 20px; background-color:#ad0000; width:60px;"><h5 style="color:white">TRUNC</h5></div>
                </td>
                <td>
                    <div style="border-radius:5px 20px; background-color:#ff0000; width:90px;"><h5 style="color:white">MISSENCE</h5></div>
                </td>
                <td>
                    <div style="border-radius:5px 20px; background-color:#ff7658; width:100px;"><h5 style="color:white">FRAMESHIFT</h5></div>
                </td>
                <td>
                    <div style="border-radius:5px 20px; background-color:#da8900; width:60px;"><h5 style="color:white">SPLICE</h5></div>
                </td>
                <td>
                    <div style="border-radius:5px 20px; background-color:orange; width:80px;"><h5 style="color:white">DELETION</h5></div>
                </td>
                <td>
                    <div style="border-radius:5px 20px; background-color:#fafa00; width:70px;"><h5 style="color:#919191">DELINS</h5></div>
                </td>
                <td>
                    <div style="border-radius:5px 20px; background-color:#fff598; width:90px;"><h5 style="color:#919191">INSERTION</h5></div>
                </td>
            </tr>
        </table>
        -->
        <p><strong style="color:'.$amp.';">AMP </strong><strong style="color:'.$del.';">HOMDEL </strong><strong style="color:'.$up.';">UP </strong><strong style="color:'.$down.';">DOWN </strong><strong style="color:'.$hyper.';">HYPER </strong><strong style="color:'.$hypo.';">HYPO </strong>
        <strong style="color:#ad0000;">TRUNC </strong><strong style="color:#ff0000;">MISSENCE </strong><strong style="color:#ff7658;">FRAMESHIFT </strong></strong><strong style="color:#da8900;">SPLICE </strong>
        <strong style="color:orange;">DELETION </strong><strong style="color:#fafa00;">DELINS </strong><strong style="color:#fff598;">INSERTION </strong>
        </p>
    </div>
    ';

    $end = '</div></body></html>' ;
    $txt = $init.$labels.$table1.$end;
    if(file_exists('tempPdf.html')){
        unlink('tempPdf.html');
    }
    $myfile = fopen("tempPdf.html", "w");
    fwrite($myfile, $txt);
    fclose($myfile);


    if(file_exists('table.pdf')){
        unlink('table.pdf');
    }
    //Mac
    shell_exec('"/usr/local/bin/wkhtmltopdf" --javascript-delay 3000 tempPdf.html table.pdf');
    //Linux
    //exec('xvfb-run --server-args="-screen 0, 1024x768x24" wkhtmltopdf --javascript-delay 3000 tempPdf.html table.pdf');
    echo '<a href="table.pdf" download target="_blank"><img id="downloadImage" src="/img/download.gif" style="width:50px;height:50px;" onclick="hideIm()"></a>';
?>
