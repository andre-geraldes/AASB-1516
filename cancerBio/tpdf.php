<?php
<<<<<<< HEAD
    $table1 = $_REQUEST["tablePDF"];
    $amp = $_REQUEST["AMP"];
    $del = $_REQUEST["DEL"];
    $down = $_REQUEST["DOWN"];
    $up = $_REQUEST["UP"];
=======

    $table1 = $_REQUEST["tablePDF"];
>>>>>>> origin/master
    $init = '<!DOCTYPE html><html><head>
    <meta charset="utf-8">
        <style>
      /* disable text selection */
      svg *::selection {
         background : transparent;
      }
<<<<<<< HEAD

      svg *::-moz-selection {
         background:transparent;
      }

=======
     
      svg *::-moz-selection {
         background:transparent;
      } 
     
>>>>>>> origin/master
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
<<<<<<< HEAD
        stroke-width:0.3px;
=======
        stroke-width:0.3px;   
>>>>>>> origin/master
      }

      rect.cell-selected {
        stroke: rgb(51,102,153);
<<<<<<< HEAD
        stroke-width:0.5px;
=======
        stroke-width:0.5px;   
>>>>>>> origin/master
      }

      rect.cell-hover {
        stroke: #F00;
<<<<<<< HEAD
        stroke-width:0.3px;
=======
        stroke-width:0.3px;   
>>>>>>> origin/master
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

    <title>Temp</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

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

<<<<<<< HEAD
<body>';

    $labels = '<div>
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
                    <div style="border-radius:5px 0px 0px 20px; background-color:#d63d2f; width:60px;"><h5 style="color:white">MUTA</h5></div>
                </td>
                <td>
                    <div style="border-radius:5px 20px 5px 0px; background-color:rgb(254, 247, 155); width:60px;"><h5 style="color:rgb(116, 116, 116)">TIONS</h5></div>
                </td>
            </tr>
        </table>
    </div>
    <div>
    ';

    $end = '</div></body></html>' ;
    $txt = $init.$labels.$table1.$end;
    $myfile = fopen("tempPdf.html", "w") or die("Unable to open file!");
    fwrite($myfile, $txt);
    fclose($myfile);


    if(file_exists('table.pdf')){
        unlink('table.pdf');
    }
    //Mac
    shell_exec('"/usr/local/bin/wkhtmltopdf" --javascript-delay 3000 tempPdf.html table.pdf');
    //Linux
    //exec('"/usr/bin/wkhtmltopdf" --javascript-delay 3000 tempPdf.html table.pdf');
    echo '<a href="table.pdf" download target="_blank"><img id="downloadImage" src="/img/download.gif" style="width:50px;height:50px;" onclick="hideIm()"></a>';
?>
=======
<body>
           
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">';
   
    $end = '</div></div></div></body></html>' ;
    $txt = $init.$table1.$end;
               
    $myfile = fopen("tempPdf.html", "w") or die("Unable to open file!");
    fwrite($myfile, $txt);
    fclose($myfile);
    
    //exec('wkhtmltopdf http://google.com google.pdf');
    //exec('wkhtmltopdf/bin/wkhtmltopdf.exe http://cancerviewer.me/table.html table.pdf');
    unlink('table.pdf');
    exec('"wkhtmltopdf/bin/wkhtmltopdf" --javascript-delay 3000 http://localhost/cancer3/tempPdf.html table.pdf');
    echo '<a href="table.pdf" download="table.pdf">download</a>';
    //print ($output);
?>
>>>>>>> origin/master
