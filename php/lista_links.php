<?php include 'no_cache_header.php'; ?>

<html>
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta http-equiv="X-UA-Compatible" content="ie=edge" />
        <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
        <meta http-equiv="Pragma" content="no-cache" />
        <meta http-equiv="Expires" content="0"/>
        <meta http-equiv="Last-Modified" content="0">
        <link rel="icon" type="image/ico" href="../imagenes/FAVICON-LINK.png" sizes="250x250">
        <script src="../js/jquery.min.js"></script>
        <script src="../js/jquery.tablesorter.min.js"></script>

        <style>
            @font-face {
                font-family: 'title1';
                src: URL('../fonts/721.ttf') format('truetype');
            }

            @font-face {
                font-family: 'font2';
                src: URL('../fonts/722.ttf') format('truetype');
            }

            @font-face {
                font-family: 'font3';
                src: URL('../fonts/723.ttf') format('truetype');
            }

            .title{
                font-family: title1;
                font-size: 28px;
                text-align: center;
                text-transform: uppercase;
            }
            .title-2{ color: #419544; }
            .title-3{ color: #D03121; }

            button{
                margin-top: .75rem;
                padding: .5rem 1rem;
                background-color: #419544;
                color: #fff;
                border-radius: 7px;
                border: none;
                font-size: 18px;
                box-shadow: 0 5px #D03121;
                transform: scale(1);
                transition: transform .1s;
                outline: none;
            }
            button:active{
                transform: scale(.95);
            }

            table{
                border-collapse: collapse;
                width: 80vw;
                margin: 0 auto;
                box-sizing: border-box;
            }

            th{
                font-family: font2;
                transition: background-color .2s, color .2s;
            }
            th:active{
                background-color: #000;
                color: yellow;
            }

            td{
                font-family: font3;
            }

            td, th{
                padding: .25rem .75rem;
                border: 1px solid #000000;
                font-size: 16px;
                padding: .35rem .5rem;
                text-align: center;
                vertical-align: center;
                width: 20vw;
            }
            .th-1{ background-color: #949191; }
            .th-2{ background-color: #000000; color: #ffffff; }
            .th-3{ background-color: #4D84B5; }
            .th-4{ background-color: #8C0632; color: #ffffff; }

            .font2{
                font-family: font2;
            }

            .ml{ margin-left: 10vw; }
        </style>
    </head>
    <body>
        <h1 class="title"><span class="title-1">gestionar</span> <br> <span class="title-2">links</span> <span class="title-3">generados</span></h1>

        <main>
            <label class="font2 ml" for="borrar_links_check">Borrar Links</label> <input type="checkbox" name="borrar_links_check" id="borrar_links_check">

            <form id="formDelete" action="" method="GET"><button class="font2 ml">Borrar</button></form>
            <!--$_SERVER['REQUEST_METHOD'];-->

            <table id="tabla">
                <thead>
                    <tr>
                        <th class="th-1">#</th>
                        <th class="th-2">TIEMPO</th>
                        <th class="th-3">LINKS</th>
                        <th class="th-4">A / V</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        require_once 'data.php';

                        if (is_file("../json/link_es.json")){
                            $datos = new Link_es();
                            $array = $datos->Load()->Get();
                            $i = 1;

                            $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
                            $serverhost = $protocol.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
                            $current = "php/".basename($_SERVER["SCRIPT_FILENAME"]);

                            $fullpath = substr($serverhost, 0, -(strlen($current)));

                            if (is_file("../json/webaudio.json")) {
                                $webaudio= new Webaudio();
                                $webaudio = $webaudio->Load()->Get();
                                $GLOBALS['fullpath'] = $GLOBALS['fullpath'].$webaudio['carpeta_link']."/";
                            }

                            function id($value){
                                $value = strval($value);
                                $value_len = strlen($value);

                                $value = ($value_len < 9) ? str_repeat('0', (9 - $value_len)).$value : $value;

                                return wordwrap($value, 3, '-', true);
                            }

                            if($_SERVER['REQUEST_METHOD'] == "POST"){

                                if (is_file("../json/webaudio.json")) {
                                    $webaudio= new Webaudio();
                                    $webaudio = $webaudio->Load()->Get();
                                    $carpeta = $webaudio['carpeta_link'];
                                    $GLOBALS['fullpath'] = $GLOBALS['fullpath'].$webaudio['carpeta_link']."/";
                                }

                                $carpeta = '../'.$carpeta.'/';
                                
                                foreach ($array as $key => $item) {
                                    $fecha_final=$item['fecha_final'];
                                    $segundoTimes = strtotime(date('Y-m-d G:i:s')) - strtotime($fecha_final);

                                    if($segundoTimes>0){
                                        unlink($carpeta.$item['enlace']);
                                        unset($array[$key]);
                                        //$array = array_values($array);
                                    }
                                }

                                $datos->Set($array);
                                $code = $datos->Save();
                            }

                            if(count($array) > 0){

                                foreach ($array as $key => $item) {
                                    $fecha_final=$item['fecha_final'];
                                    $segundoTimes = strtotime(date('Y-m-d G:i:s')) - strtotime($fecha_final);

                                    $estado = ($segundoTimes>0) ? "<td style='color: red'>VENCIDO</td>" : "<td style='color: green'>ACTIVO</td>";

                                    $row = "<tr>";
                                    $row = $row."<td>".id($i)."</td>";
                                    $row = $row."<td>".$item['modo_duracion']."</td>";
                                    $row = $row."<td><a href='".$fullpath.$item['enlace']."' target='_blank'>".$item['enlace']."</a></td>";
                                    $row = $row.$estado;
                                    $row = $row."</tr>";
 
                                    echo $row;
                                    $i++;
                                }
                            }else{
                                echo "<td colspan='4' style='text-align: center'>Sin enlaces</td>";
                            }
                        }else{
                            echo "<td colspan='4' style='text-align: center'>Sin enlaces</td>";
                        }
                     ?>
                </tbody>
            </table>
        </main>

        <script>
            document.getElementById('borrar_links_check').addEventListener('change', e => {
                let active = e.target.checked;

                document.getElementById('formDelete').setAttribute('method', (active) ? "POST" : "GET");
            });

            document.getElementById('formDelete').addEventListener('submit', e => {
                e.preventDefault();

                let active = document.getElementById('borrar_links_check').checked;

                if(active){
                    if(confirm("¿Borrar enlaces inactivos?"))
                        e.target.submit();
                }
            });

            $('#tabla').tablesorter(); 
        </script>
    </body>
</html>