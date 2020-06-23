        document.onkeypress = manejador;
        function manejador(elEvento){
            var evento = elEvento || window.event;
            var codigo = evento.charCode || evento.keyCode;
            var caracter = String.fromCharCode(codigo);
            if(caracter=='1'){
                console.log("Lista Actual:");
                console.log("Lista "+current_lista+":["+lista.toString()+"]");
            }
            if(caracter=='2'){
                console.log("Listas Generadas:");
                var xhr = new XMLHttpRequest();
                xhr.open('GET', "php/mostrar_listas.php", true);
                xhr.responseType = 'text';
                xhr.onload = function () {
                    if (xhr.readyState === xhr.DONE) {
                        if (xhr.status === 200) {
                            var responjson= JSON.parse(this.responseText);
                            var lista_rep=JSON.parse(responjson.lista_reproduccion); // MUESTRA LA LISTA DE REPRODUCCION
                            //console.log(lista_rep);
                            for(let i=0; i<lista_rep.length ; i++){
                                console.log("Lista "+i+':['+lista_rep[i].lista+"]");
                            }
                        }
                    }
                };
                xhr.send(null);
            }
            
                
        }
    // SINCRONIZAR CON DATOS GUARDADOS EN JSON
        function sincronizar(){
            var xhr = new XMLHttpRequest();
            xhr.open('GET', "php/obtener_variables.php", true);
            xhr.responseType = 'text';
            xhr.onload = function () {
                if (xhr.readyState === xhr.DONE) {
                    if (xhr.status === 200) {
                        var responjson= JSON.parse(this.responseText);
                        console.log(new Date(), responjson); // FECHA Y VERSION DEL PROGRAMA
                        cargar_variables(responjson);
                        var revolver= JSON.parse(responjson.revolver);    
                    
                        if(revolver==false ){
                            if(generos.lenght!=0){
                                update_configuracion();
                                inicializar_variables();
                                principio();
                            }
                        }
                        else{
                                if(version!=0 && time_delete !=0){
                                        player.currentTime = parseInt(current_times);//-1;
                                }
                                inicio();
                        }
                    }
                }
            };
            xhr.send(null);
        }
        
        
        function conf_peticion_servidor(){ // por finalizar
            var req = new XMLHttpRequest();
            req.open('GET', "php/obtener_current.php", false);
            req.onload = onLoad;
            req.send(null); 
            function onLoad(e) {
                if(e.target.readyState == 4 && e.target.status == 200) {
                    var responjson= JSON.parse(this.responseText);
                    //console.log(responjson.conectividad);
                    if(responjson.conectividad=='SI' && responjson.current_lista==0 && responjson.current_track==0 && responjson.current_times<5){    // obtiene la nueva lista    
                        current_lista=responjson.current_lista;
                        current_track=0;
                        obtener_lista();
                    }
                    if(responjson.conectividad=='NO'){    // finaliza todas las conecciones    
                        //console.log('Listo');
                        //console.log(new Date());
                        current_track=0;
                        current_lista=0;
                        if( responjson.crear=='SI'){    // crea las siguientes listas1
                            update_configuracion();
                            obtener_variables();
                            inicializar_variables();    // inicializa todas las variables y va al principio
                            principio();
                        }
                        else{    // obtiene la nueva lista
                            setTimeout(function(){ conf_peticion_servidor(); },5000);
                        }
                    }
                    if(responjson.conectividad=='SI' && responjson.current_times>5){    // verifica que termine todas las conecciones
                        setTimeout(function(){ conf_peticion_servidor(); },4000);
                    }    
                }
            }
        }
        
        function procesar_listas(){
            var xhr = new XMLHttpRequest();
            xhr.open('GET', "php/obtener_variables.php", true);
            xhr.responseType = 'text';
            xhr.onload = function () {
                if (xhr.readyState === xhr.DONE) {
                    if (xhr.status === 200) {
                        var responjson= JSON.parse(this.responseText);
                        console.log(new Date()); // FECHA Y VERSION DEL PROGRAMA
                        cargar_variables(responjson);
                        update_configuracion();
                        inicializar_variables();
                        mezclarComercialesLista(); // MEZCLA LA LISTA DE LOS COMERCIALES POR FISHER YATES Y SATTOLO 
                        mezclarGenerosLista(); // MEZCLA LA LISTA DE LOS GENEROS POR FISHER YATES Y SATTOLO 
                        crear_listas();
        
                    }
                }
            };
            xhr.send(null);
        }
        
        // verificar fin de la lista1 en todas las conecciones
/*        function fin_peticion_servidor(){
            var req = new XMLHttpRequest();
            req.open('GET', "php/obtener_current.php", false);
            req.onload = onLoad;
            req.send(null); 
            function onLoad(e) {
                if(e.target.readyState == 4 && e.target.status == 200) {
                    var responjson= JSON.parse(this.responseText);
                    //console.log(responjson.conectividad);
                    if(responjson.conectividad=='SI' && responjson.current_lista%10==0){    // obtiene la nueva lista    
                        current_lista=responjson.current_lista;
                        current_track=0;
                        obtener_lista();
                    }
                    if(responjson.conectividad=='NO'  && responjson.current_lista%10==9 ){    // finalizo todas las conecciones    
                        //console.log('Listo');
                        //console.log(new Date());
                        current_track=0;
                        if( responjson.crear=='SI'){    // crea las siguientes listas1
                            crear_listas();
                        }
                        else{    // obtiene la nueva lista
                            setTimeout(function(){ fin_peticion_servidor(); },5000);
                        }
                    }
                    if(responjson.conectividad=='SI' && responjson.current_lista%10==9){    // verifica que termine todas las conecciones
                        setTimeout(function(){ fin_peticion_servidor(); },5000);
                    }
                    
                    
                }
            }
        }*/
        
        
            // actualizar cambios en la configuracion de los generos y comerciales
        function update_configuracion(){
            var req = new XMLHttpRequest();
            req.open('GET', "php/update_configuracion.php", false);
            req.onload = onLoad;
            req.send(null); 
            function onLoad(e) {
                if(e.target.readyState == 4 && e.target.status == 200) {
                    var responjson= this.responseText;
                    console.log(responjson);
                    
                }
            }
        }
        
        
        
                
        // CREA 10 LISTA DE REPRODUCCIONES Y LAS GUARDAS EN LIST1.JSON
        function crear_listas(){
            var listas_reproducciones=[];
            //mezclarComercialesLista(); // MEZCLA LA LISTA DE LOS COMERCIALES POR FISHER YATES Y SATTOLO 
            //mezclarGenerosLista(); // MEZCLA LA LISTA DE LOS GENEROS POR FISHER YATES Y SATTOLO 
            //console.log(generos_A_P);
            //console.log(current_lista);
            //alert(nronda);    
            //var ultimo_current=current_lista;
            for(let i=0, j=0; i<parseInt(nronda); i++,j++){
                mezclar_generos_RANDOM(); // MEZCLA LOS GENEROS POR EL RANDOM CORRESPONDIENTE
                reproducir(generos_A_P); // CREA LA LISTA DE REPRODUCCION        
                listas_reproducciones[j]={
                    //time_control: new Date(),
                    current_lista: (j),
                    lista: lista,
                    //revolver: false
                };
            }
            console.log('');
            console.log('Listas Generadas:');
            console.log(listas_reproducciones);
            guardar_variables_lista();
            $.ajax({
                type: "POST",
                url: 'php/save_list.php',
                data: {
                        //procesar: procesar,
                        lista: JSON.stringify(listas_reproducciones),
                        current_lista: current_lista
                },
                success: function( response ){
                        var responjson= JSON.parse(response);
                        lista=responjson.lista;
                        //console.log(lista);
                        current_lista=responjson.current_lista;
                        //alert('Current_lista:'+current_lista);
                        current_track=0;
                    
                        if(current_lista%nronda==0){
                            //current_lista++;
                            //obtener_lista();
                            inicio();
                        }
                        else{
                            inicio();
                        }
                    
                }
            });
        }

        // GUARDA TODAS LAS VARIABLES EN GENEROS_A_P.JSON, COMERCIALES.JSON, GENERAL.JSON
        function guardar_variables_lista(){
            var tmp_lista_reproducciones=[];
            var lista_variables ={
                    RANDOM: RANDOM,
                    PIZZICATO: PIZZICATO, 
                    SEPARAR_GENERO: SEPARAR_GENERO,
                    nronda: nronda,
                    //current_lista: current_lista,
                    temporal: temporal,
                    escalar: escalar,
                    cont_A_P: cont_A_P,
                    temporal_A_P: temporal_A_P, 
                    permutacion: permutacion,
                    permutado_pasado: permutado_pasado,
                    activar_permutacion: activar_permutacion,
                    comerciales_generos: comerciales_generos,
                    conta: conta,
                    iniciar_R_2: iniciar_R_2,
                    version: version,
                    tiempo_inactividad: time_delete,
                    usuario: usuario,
                    clave: clave,
                    nombre_emisora:nombre_emisora,
                    color_emisora:color_emisora,
                    letra_emisora:letra_emisora,
                    slogan_emisora:slogan_emisora,
                    color_slogan:color_slogan,
                    letra_slogan:letra_slogan,
                    url_logo:url_logo,
                    ancho_logo:ancho_logo,
                    largo_logo:largo_logo,
                    redondeo:redondeo
            };
            var list_var=JSON.stringify(lista_variables);
            //var list_tracks = JSON.stringify(lista);
            var list_generos_A_P = JSON.stringify(generos_A_P);
            var list_comerciales = JSON.stringify(comerciales);    
            //console.log("Lista de Reproduccion:");
            //console.log(lista);
            $.ajax({
                type: "POST",
                url: 'php/guardar_variables.php',
                data: {
                        variables: list_var,
                        //lista: list_tracks,
                        current_lista: current_lista,
                        generos_A_P: list_generos_A_P,
                        comerciales: list_comerciales
                },
                success: function( response ){
                        //var responjson= JSON.parse(response);
                        //cargar_variables(responjson);
                        //alert(current_track);
                        //console.log("Segundos:"+responjson.segundos);
                        //current_track = 0;
                        //inicio();
                }
            });
        }

        // CARGA LAS VARIABLES DE LOS JSON
        function cargar_variables(responjson){
            // sincronizar listas generos y comerciales
            generos = JSON.parse(responjson.lista_generos);
            comerciales = JSON.parse(responjson.lista_comerciales);
                        
            // sicroniza la lista de variables
            var l_var=JSON.parse(responjson.lista_variables);
                        
            // INICIALIZA LOS VALORES OBTENIDOS DEL JSON
            RANDOM= l_var.RANDOM;
            PIZZICATO= l_var.PIZZICATO; 
            SEPARAR_GENERO=l_var.SEPARAR_GENERO;
            time_delete = l_var.tiempo_inactividad;
            version=l_var.version;
            nronda=l_var.nronda;
            
            var lista_rep=JSON.parse(responjson.lista_reproduccion); // SINCRONIZA LA LISTA DE REPRODUCCION
            var l_current= JSON.parse(responjson.lista_current); // SINCRONIZA LOS CURRENT LISTA, AUDIO Y POR DONDE VA
            var l_generos_A_P = JSON.parse(responjson.lista_generos_A_P); // SINCRONIZA EL GENERO PRESENTE
    
            // VARIABLES DE USUARIOS
            usuario=l_var.usuario;
            clave=l_var.clave;
            
            // VARIABLES DE DATOS EMISORA (LOGO, NOMBRE Y SLOGAN)
            nombre_emisora=l_var.nombre_emisora;
            color_emisora=l_var.color_emisora;
            letra_emisora=l_var.letra_emisora;
            slogan_emisora=l_var.slogan_emisora;
            color_slogan=l_var.color_slogan;
            letra_slogan=l_var.letra_slogan;
            url_logo=l_var.url_logo;
            ancho_logo=l_var.ancho_logo;
            largo_logo=l_var.largo_logo;
            redondeo=l_var.redondeo;
            
            // SINCRONIZANDO VARIABLES
            temporal= l_var.temporal;  
            escalar= l_var.escalar;  
            cont_A_P= l_var.cont_A_P;  
            temporal_A_P= l_var.temporal_A_P; 
            comerciales_generos= l_var.comerciales_generos;
            conta= l_var.conta;
            iniciar_R_2= l_var.iniciar_R_2;
            if(RANDOM==3){
                permutacion= l_var.permutacion;
                permutado_pasado= l_var.permutado_pasado;
                activar_permutacion= l_var.activar_permutacion;
            }        

            // SINCRONIZANDO LA VARIABLE DEL GENERO PRESENTE
            generos_A_P=l_generos_A_P.generos_A_P; 
            //console.log("Contador="+generos_A_P[0].contador);
            
            // SINCRONIZANDO LOS CURRENT LISTA, AUDIO Y POR DONDE VA
            current_lista=l_current.current_lista;
            current_track=l_current.current_tracks;    
            current_times = l_current.current_times;
            
            // SINCRONIZANDO LISTA DE REPRODUCCION
            //lista = JSON.parse(lista_rep.lista);
            lista = lista_rep.lista;
            /*if(current_track==0){
                console.log("Lista de Reproduccion:");
                console.log(lista);
            }*/
        }

        // INICIALIZA LAS VARIABLES 
        function inicializar_variables(){
            // INICIALIZA LOS CURRENT EN CERO
            current_lista = 0; // CONTROLA LA LISTA EN QUE VA EL REPRODUCTOR
            current_times = 0; // CONTROLA EL TIEMPO EN QUE VA EL TRACKS
            current_track = 0; // CONTROLA EL TRACKS DE LA LISTA DE REPRODUCCION
            // INICIALIZA VARIABLES DE FUNCIONAMIENTO
            escalar = -1;
            cont_A_P= -1;
            temporal_A_P=0; 
            permutacion = []; // guarda las combinaciones de permutacion
            permutado_pasado = []; // guarda la combinacion ya recorrida
            activar_permutacion = false; // ACTIVA EL USO DE LA PERMUTACION
            comerciales_generos=false; // 
            conta=0;    // VARIABLE PARA LAS FUNCIONES PERMUTA Y PERMUTACION
            iniciar_R_2 = false; // VARIABLE PARA LA FUNCION MEZCLAR_GENEROS_RANDOM
            
            // INICIAR VARIABLES ORDENADAS
            generos.sort(function (a, b) {  if (a.ID > b.ID) {    return 1; }  if (a.ID < b.ID) {    return -1;  }  return 0;});
            comerciales.sort(function (a, b) {  if (a.ID > b.ID) {    return 1; }  if (a.ID < b.ID) {    return -1;  }  return 0;});
            for(let i=0;i<generos.length;i++){
                generos[i].lista.sort();
                generos[i].reproduccion = [];
                generos[i].contador=0;
                generos[i].ultima="";
                generos[i].posicion_Perm=0;
                generos[i].seleccion_pasado=[];
            }
        
            for(let i=0;i<comerciales.length;i++){
                comerciales[i].lista.sort();
                comerciales[i].reproduccion = [];
                comerciales[i].contador=0;
                comerciales[i].ultima="";
                comerciales[i].seleccion_pasado=[];
            }
            generos_A_P=mostrar_Ausente_Presente();

        }
        
// VARIABLES-------------------------------------------------------------------------------------------------------------------------------


        // VARIABLES GLOBALES
        var RANDOM = 0; // 0- SIN RANDOM (AUSENTE PRESENTE), 1- FISHER YATES (AUSENTE PRESENTE), 2- SATTOLO (AUSENTE PRESENTE), 3- PERMUTACION
        var PIZZICATO =0; // 0- SIN EFECTO , 1- EFECTO LOS AUDIOS, 2- EFECTOS LOS COMERCIALES, 3- EFECTOS AMBOS 
        var SEPARAR_GENERO = 1; // 1.- LIBRE , 2.- SEPARAR (NOTA: APLICA A 5 O MAS GENEROS CON RANDOM 1 Y 2)
        var nronda=0; // CANTIDAD DE RONDAS A GENERAR
        
        // VARIABLES DE LOGIN
        var usuario;
        var clave;
        
        // VARIABLES DE DATOS EMISORA (LOGO, NOMBRE Y SLOGAN)
        var nombre_emisora, color_emisora, letra_emisora;
        var slogan_emisora, color_slogan, letra_slogan;
        var url_logo, ancho_logo, largo_logo, redondeo;
        
        // VARIABLES DE FUNCIONAMIENTO
        //var debug = false;
        var temporal;
        var escalar = -1;
        var cont_A_P= -1;
        var temporal_A_P=0; 
        var generos_A_P = new Array(); // VARIABLE QUE GUARDA LOS GENEROS ACTIVOS AL APLICAR AUSENTE PRESENTE
        var permutacion = []; // guarda las combinaciones de permutacion
        var permutado_pasado = []; // guarda la combinacion ya recorrida
        var activar_permutacion = false; // ACTIVA EL USO DE LA PERMUTACION
        var comerciales_generos=false; // 
        var conta=0;    // VARIABLE PARA LAS FUNCIONES PERMUTA Y PERMUTACION
        var iniciar_R_2 = false; // VARIABLE PARA LA FUNCION MEZCLAR_GENEROS_RANDOM
        
        // VARIABLES DE CANCION Y TIEMPO
        var version = 0; // Control de versiones del programa [0]. Desactivado (Sincroniza canciones) [1]. Activado (Sincroniza cancion y tiempo)
        var current_lista = 0; // CONTROLA LA LISTA EN QUE VA EL REPRODUCTOR
        var current_times = 0; // CONTROLA EL TIEMPO EN QUE VA EL TRACKS
        var current_track = 0; // CONTROLA EL TRACKS DE LA LISTA DE REPRODUCCION
        var audio_track = '';
        var time_delete = 0; // Tiempo en que borrará lista generada, luego de que no haya conexión con ningún oyente.
        
        
        // VARIABLES DE CONTROL
        var player = document.getElementById('Playing'); // CONTROLA EL ELEMENTO AUDIO
        var next = document.getElementById('next'); // CONTROLA EL BOTON NEXT
        var btnPlay = document.getElementById("btnplay"); // CONTROLA EL BOTON PLAY
        
        // VARIABLES DE DISEÑO
        var holding = false;
        var track = document.getElementById('track');
        var progress = document.getElementById('progress');
        var current_track = 0;
        var song, audio, duration, porcencap;
        var playing = false;
        var MEDIA_ELEMENT_NODES = new WeakMap();
        var ANALIZER_NOTE = new WeakMap();
        var o1 = {}, analizador;
        
        // VARIABLES DE ANALIZADOR DE AUDIO
        var canvas, ctx, source, context, analyser, fbc_array, bars, bar_x, bar_width, bar_height, padre;
        var encipa = true;
        
        // LISTAS 
        var lista = []; // lista de reproduccion
        var comerciales = []; // lista de comerciales
        var generos = []; // lista de generos
            
// FIN VARIABLES-------------------------------------------------------------------------------------------------------------

// FUNCIONES-----------------------------------------------------------------------------------------------------------------

        // OBTIENE LAS VARIABLES DE LOS JSON
        function obtener_variables(){
            var valor;
            var req = new XMLHttpRequest();
            req.open('GET', "php/obtener_variables.php", false);
            req.onload = onLoad;
            req.send(null); 
            function onLoad(e) {
                if(e.target.readyState == 4 && e.target.status == 200) {
                        var responjson= JSON.parse(this.responseText);
                        console.log(new Date()); // FECHA Y VERSION DEL PROGRAMA
                        cargar_variables(responjson);
                }
            }
            //return valor;
        }

        // OBTIENE LA LISTA CORRESPONDIENTE DE LIST1.JSON
        function obtener_lista(){
            var valor;
            var req = new XMLHttpRequest();
            req.open('GET', 'php/change_list.php?current_lista='+current_lista, false);
            req.onload = onLoad;
            req.send(null); 
            function onLoad(e) {
                if(e.target.readyState == 4 && e.target.status == 200) {
                        var responjson= JSON.parse(this.responseText);
                        lista=responjson.lista;
                        current_lista=responjson.current_lista;
                        /*if(current_lista==0){
                            //current_track=0;
                        }*/
                        inicio();    
                }
            }
        }

        // DETIENE LA REPRODUCCION E INICIALIZA LA REPRODUCCION
        function inicializar_lista(){
            //alert('epale');
            var xhr = new XMLHttpRequest();
            xhr.open('GET', "php/inicializar_lista.php", true);
            xhr.responseType = 'text';
            xhr.send(null);
        }
        
    // PASA AL SIGUIENTE AUDIO
        function siguiente(){
            current_track++;
            //alert('');
            if(current_lista==nronda-1 && current_track == lista.length-1){
                //procesar_listas();
            }
            if(current_track == lista.length){
                current_track=0;
                current_lista++;
//                alert('current_lista:'+current_lista);
                if(current_lista==nronda){
                    //obtener_variables();
                    current_lista=0;
                    //player.pause();
                    //fin_peticion_servidor();
                    inicializar_lista();
                    //conf_peticion_servidor();
                    //procesar_listas()
                    //obtener_lista();
                }
                else{
                    obtener_lista();
                }
            }
            else{
                inicio();
            }
        }

    // INICIO DE LA REPRODUCCION
        function inicio(){
            if(RANDOM==0){
                generos_A_P = burbuja_generos(generos_A_P);
            }
            //console.log("Lista de Generos A P:");
            //console.log(generos_A_P);
            //mostrarListaGeneros(generos_A_P);
        //    mostrarListaComerciales();
            mostrarListaAudio();
            document.getElementById("txtCancion").innerHTML = "Audio Activo: "+lista[current_track];
            // muestra en html el genero activo y la cantidad de tracks
            for(var i=0;i<generos.length; i++){
                if(generos[i].lista.indexOf(lista[current_track])!= -1){
                    document.getElementById("txtGeneroActivo").innerHTML = "Genero Activo: "+generos[i].Name+" Ntracks:"+generos[i].Ntracks;
                    break;    
                }
            }
            var extension,indice;
            if(lista.length!=0){
                indice = lista[current_track].lastIndexOf(".");
                extension =  lista[current_track].slice (indice);
                // EXTENSIONES PERMITIDAS
                if(extension == ".js" || extension == ".json" || extension == ".txt"){
                    cargar_audio(extension,getAbsolutePath()+lista[current_track].substring(3));
                }
                else 
                if(extension == ".mp3" || extension == ".ogg" || extension == ".opus" || extension == ".aac" || extension == ".m4a" ){ // aac mp3 ogg opus m4a
                    audio_track = getAbsolutePath()+lista[current_track].substring(3);
                    player.setAttribute('src', audio_track);
                    //player.play();
                     var playPromise = player.play();
                         
                          if (playPromise !== undefined) {
                            playPromise.then(_ => {
                              player.play();
                            })
                            .catch(error => {});
                          }
                }
                else if(extension == ".ini" || extension == ".log" || extension == ".pdf" || extension == ".rtf"){
                     cargar_audio(extension,getAbsolutePath()+lista[current_track].substring(3));
                }
            }
            else{
                // sino hay nada en la lista -----------------------------------------------------------------------------------------------        
                alert("No hay lista para reproducir...");
            }        
        }
        


    
    // EVENTO QUE GUARDA LA DURACION DEL AUDIO
        player.ondurationchange = () => {
            duration = player.duration;
            //console.log("Tiempo de duracion:");
            //console.log(duration);
        }

    // EVENTO PARA ACTUALIZACION DEL TRACK DE REPRODUCCION
        player.addEventListener("timeupdate", function(){
            let curtime = parseInt(player.currentTime, 10);
            let percent = Math.round((curtime * 100) / duration);
            progress.style.width = percent + '%';
            handler.style.left = percent + '%';
            handler.innerHTML = percent + "<span style='color: "+color_signo+"'> %</span>";
            //console.log(duration);
            timesControlInfo.innerHTML = "<span id='timeProgress' style='color: "+color_tiempo+"; font-size:"+tactual+"px;'>" + hora(curtime)+
                                         "</span> / <span id='timesActual' style='color: "+color_tiempo+"; font-size:"+tduracion+"px;'>"+hora(duration) +
                                         "</span> / <span style='color: "+color_guion+"'>-</span> <span id='timesRestante' style='color: "+color_tiempo+"; font-size:"+trestante+"px;'>"+horaRestar(duration, curtime)+"</span>";
            if(percent < 68) {
                handler.style.marginLeft = -percent+'px';
                porcencap = -percent+'px';
            }
            else{
                handler.style.marginLeft = '-67px';
            }
            current_times= curtime;    
            var xhr = new XMLHttpRequest();
            xhr.open('GET', "php/current.php?current_times="+current_times+"&current_track="+current_track+"&current_lista="+current_lista, true);
            xhr.responseType = 'text';
            xhr.onload = function () {
                if (xhr.readyState === xhr.DONE) {
                    if (xhr.status === 200) {
                        //console.log(this.responseText);
                        if(this.responseText=='true'){
                            //console.log('hola');
                                player.pause();
                                current_track=0;
                                current_lista=0;
                                //setTimeout(,5000);
                                setTimeout(function(){ obtener_lista(); }, 5000);
                                //conf_peticion_servidor();
                        }
                    }
                }
            };            
            xhr.send(null);
            
        });

    // EVENTO DEL BOTON PAUSE
        muteded.onclick = function () {
            player.muted = true;
            muteded.classList.add('active');
            if(muteded.classList.contains('active')) {
                muteded.style.background="url('"+getAbsolutePath()+"imagenes/PAUSE-1.png')";
                muteded.style.backgroundRepeat="no-repeat";
                muteded.style.backgroundSize = "74px";
                btnPlay.classList.remove('active');
                btnPlay.style.background="url('"+getAbsolutePath()+"imagenes/PLAY-2.png')";
                btnPlay.style.backgroundRepeat="no-repeat";
                btnPlay.style.backgroundSize = "75px";
            }
        }

    // EVENTO DEL BOTON PLAY
        btnPlay.onclick = function () {
            // alert("Presionado");
            player.play();
            player.muted = false;
            btnPlay.classList.add('active');
            if(btnPlay.classList.contains('active')) {
                //alert(getAbsolutePath()+"imagenes/PLAY-1.png");
                btnPlay.style.background="url('"+getAbsolutePath()+"imagenes/PLAY-1.png')";
                btnplay.style.backgroundRepeat="no-repeat";
                btnplay.style.backgroundSize = "75px";
                muteded.classList.remove('active');
                muteded.style.background="url('"+getAbsolutePath()+"imagenes/PAUSE-2.png')";
                muteded.style.backgroundRepeat="no-repeat";
                muteded.style.backgroundSize = "74px";
            }
            // Wave Audio
            var audioContext = new(window.AudioContext || window.webkitAudioContext)();
            canvas = document.getElementById('analyser_render');
            ctx = canvas.getContext('2d');
            // Vuelva a enrutar la reproducción de audio en el gráfico de procesamiento del AudioContext
            if (MEDIA_ELEMENT_NODES.has(player)) {
                source = MEDIA_ELEMENT_NODES.get(player);
                analizador = ANALIZER_NOTE.get(o1);
                source.connect(analizador);
            }
            else {
                analyser = audioContext.createAnalyser(); // AnalyserNode method
                source = audioContext.createMediaElementSource(player);
                MEDIA_ELEMENT_NODES.set(player, source);
                ANALIZER_NOTE.set(o1, analyser);
                source.connect(analyser);
                analyser.connect(audioContext.destination);
            }
            frameLooper();
        }

// FUNCIONES DE FUNCION.JS

    // MEZCLA LOS GENEROS SEGUN EL RANDOM ESTABLECIDO
        function mezclar_generos_RANDOM(){
            var temp_genero = new Array();
            var primero_ultimo=0;
            var ultimo,primero;
            //alert(RANDOM);
            if(cont_A_P==-1){    
                temp_genero=mostrar_Ausente_Presente();
                
                for(let i=0; i<generos.length;i++){
                    generos[i].posicion_Perm=i;                     // coloca las posiciones iniciales de la lista generos
                }                    
            }
            else
                temp_genero=generos_A_P;
            //alert(cont_A_P);    
            if(temp_genero.length <=2){
                    //generos_A_P=mostrar_Ausente_Presente();
            }
            if(temp_genero.length>=3){
                ultimo=temp_genero[temp_genero.length-1].Name;
                mostrarGenerosConsola(temp_genero,0);
                incrementar_A_P();
                var encontrar = false;
                var detener=0;
                while(encontrar == false){
                    encontrar = true;
                    generos_A_P=mostrar_Ausente_Presente();
                
                    if(RANDOM == 0){ // SIN RANDOM
                        //alert('pasooooooooooooooooooo');
                        generos_A_P=burbuja_generos(generos_A_P);
                    }
                    if(RANDOM == 1){ // MEZCLA GENEROS CON FISHER YATES
                        generos_A_P=shuffleArray(generos_A_P);
                    }
                    else if(RANDOM == 2){ // MEZCLA GENEROS CON SATTOLO
                        //console.log(RANDOM);
                        generos_A_P=sattolo(generos_A_P);
                    }
                    else if(RANDOM == 3){ // MEZCLA GENEROS CON PERMUTACION
                        //console.log(permutacion.length+"="+permutado_pasado.length);
                        permutaciones(generos_A_P); 
                        
                    }
                    // INSTRUCCIONES PARA SEPARAR GENEROS IGUALES ___________________________________________________________________________________________________
                    if(SEPARAR_GENERO == 2){ 
                        if(RANDOM == 1 || RANDOM == 2){
                            if(generos_A_P<=4){
                                alert("Debe poseer 5 o más géneros para separar iguales generos");
                            }
                            else{
                                var cont_rep = 0;
                                for(var i=0,j=1;i < generos_A_P.length-1;i++,j++){
                                    if(generos_A_P[i].Name == generos_A_P[j].Name){
                                        cont_rep++;
                                    }
                                }
                                if(cont_rep > 0){
                                    encontrar=false;
                                    //alert("Generos Pegados");        
                                }
                                cont_rep = 0;
                            }
                        }
                        else if(RANDOM == 3) alert("El modo permutacion no separa los generos");
                    }
                    // FIN INSTRUCCIONES PARA SEPARAR GENEROS IGUALES _______________________________________________________________________________________________
//                    mostrarListaGeneros(generos_A_P);
                    primero=generos_A_P[0].Name;
                    //console.log(ultimo+","+primero);
            
                    if(iniciar_R_2!= false){
                        if(ultimo == primero ){
                            encontrar = false;
                            primero_ultimo++;
                            mostrarGenerosConsola(generos_A_P,1);
                            console.log("Ultimo genero es igual al primero:"+detener);
                            //alert("Ultimo genero es igual al primero;"+detener);
                            //detener++;
                            //if(detener>5){
                            //    inicializar_variables();
                            //    principio();                                
                            //}
                            if(RANDOM!=3){
                                generos_A_P=temp_genero;
                            }
                            if(RANDOM==3){
                            //    alert("Ultimo genero es igual al permutacion");    
                                permutacion.push(permutado_pasado.pop());
                            //    console.log(permutacion.length+"="+permutado_pasado.length);
                            }
                            
                        }
                    }else iniciar_R_2=true;
                }
                if(primero_ultimo == 0) 
                    mostrarGenerosConsola(generos_A_P,1);
            }
            else{ 
                if(RANDOM == 1) alert("Debe poseer 3 o más generos para mezclar con fisher yates");
                else if(RANDOM ==2) alert("Debe poseer 3 o más generos para mezclar con sattolo");
                else if(RANDOM ==3) alert("Debe poseer 3 o más generos para mezclar con permutacion");
            }
        }

    // REMOVER UN ITEM DE LA LISTA
        function removeItemFromArr ( arr, item ) {
            var i = arr.indexOf( item );
            if ( i !== -1 ) {
                arr.splice( i, 1 );
            }
        }
        
    // INSERTAR UN ITEM A LA LISTA
        function insertSattolo(array,temporal,escalar){
            removeItemFromArr(array,temporal);
            lista=sattolo(array);
            lista.splice(escalar, 0, temporal);
        }

    // REVOLVER CON SATTOLO
        function sattolo(array){
            const len = array.length;
            for(let i = 0;i < len -1; i++){
                let j = Math.floor(Math.random() * (len-(i+1)))+(i+1);
                const temp = array [i];
                array[i] = array[j];
                array[j] = temp;
            }
            return array;    
        }

    // REVOLVER CON FISHER YATES
        function shuffleArray(array) {
            for (let i = array.length - 1; i > 0; i--) {
                let j = Math.floor(Math.random() * (i + 1));
                [array[i], array[j]] = [array[j], array[i]];
            }
            return array;
        }

    // METODO DE ORDENACION
        function burbuja_generos(miArray){
            for(var i=1;i<miArray.length;i++){
                for(var j=0;j<(miArray.length-i);j++){
                    if(miArray[j].ID>miArray[j+1].ID){
                        k=miArray[j+1];
                        miArray[j+1]=miArray[j];
                        miArray[j]=k;
                    }
                }
            }
            return miArray;
        }

    // PARA INICIALIZAR PIZZICATO (EN CONSTRUCCION)
        function inicializar_pizzicato(){
            // EN CONSTRUCCION
        }

    // Calcula el tiempo restante en segundos
        hora = (segundos) => {
            var d=new Date(segundos*1000); 
            // Ajuste de las 23 horas
            var minuto = (d.getMinutes()<10)?"0"+d.getMinutes():d.getMinutes();
            var segundo = (d.getSeconds()<10)?"0"+d.getSeconds():d.getSeconds();
            return minuto+"<span style='color: "+color_puntos+"'>:</span>"+segundo;  
        }
    
    // Resta el tiempo restante en segundos
        horaRestar = (segundos1, segundo2) => {
            var total = segundos1-segundo2;
            var d=new Date(total*1000); 
            // Ajuste de las 23 horas
            var minuto = (d.getMinutes()<10)?"0"+d.getMinutes():d.getMinutes();
            var segundo = (d.getSeconds()<10)?"0"+d.getSeconds():d.getSeconds();
            return minuto+"<span style='color: "+color_puntos+"'>:</span>"+segundo;  
        }

        segundos = (segundos1, segundo2) => {
            var total = segundos1-segundo2;
            var d=new Date(total*1000);
            // calculamos los minutos a partir de las horas y minutos de la fecha creada
            var segun = d.getMinutes() * 60 + d.getSeconds();
            return segun;
        }

    // OBTENER LA RUTA ABSOLUTA
        function getAbsolutePath() {
            var loc = window.location;
            var pathName = loc.pathname.substring(0, loc.pathname.lastIndexOf('/') + 1);
            return loc.href.substring(0, loc.href.length - ((loc.pathname + loc.search + loc.hash).length - pathName.length));
        }    

    // RECORRIDO DE AUSENTE PRESENTE EN LOS GENEROS
        function incrementar_A_P(){
            cont_A_P++;
            if(cont_A_P == generos[0].AUSENTE_PRESENTE.length)
                cont_A_P=0;
        }    

    // APLICA AUSENTE PRESENTE EN LOS GENEROS
        function mostrar_Ausente_Presente(){
            var generos_p = new Array();
            temporal_A_P = cont_A_P;            
            if(cont_A_P==-1){ 
                cont_A_P=0;
                for(var i=0,j=0; i<generos.length; i++){
                    //console.log(generos.length);
                    if(parseInt(RANDOM) == 3 && parseInt(generos[i].Ntracks)!=0){
                        generos_p[j++]=generos[i];
                    }
                    else if(parseInt(generos[i].Ntracks)!=0 && parseInt(generos[i].AUSENTE_PRESENTE[parseInt(cont_A_P)])!=0 && generos[i].AUSENTE_PRESENTE.length!=0){
                        generos_p[j++]=generos[i];
                    }
                }
                cont_A_P= -1;
            }
            else{
                for(var i=0,j=0; i<generos.length; i++){
                    //console.log(generos[i].AUSENTE_PRESENTE.length);
                    if(parseInt(RANDOM) == 3 && parseInt(generos[i].Ntracks)!=0){
                        generos_p[j++]=generos[i];
                    }
                    else if(parseInt(generos[i].Ntracks)!=0 && parseInt(generos[i].AUSENTE_PRESENTE[parseInt(cont_A_P)])!=0 && generos[i].AUSENTE_PRESENTE.length!=0){
                        generos_p[j++]=generos[i];
                    }
                }
            }
            return     generos_p;
        }    

            // MUESTRA POR LA PAGINA HTML LA LISTA DE REPRODUCCION
        function mostrarListaAudio(){
                    var tmp_lista='';
            for(let i=0;i<lista.length;i++){
                tmp_lista=tmp_lista+lista[i];
                if(i!=lista.length-1){
                    tmp_lista=tmp_lista+',';
                }
                if((i+1)%3==0 && i!=0 && i!=lista.length-1){
                    tmp_lista=tmp_lista+'<br>';
                }
            }
            //tmp_lista=tmp_lista.substring(-);
            document.getElementById("txtArray").innerHTML = "["+tmp_lista+"]";        
        }

    // MUESTRA POR CONSOLA EL GENERO ANTES Y NUEVO LUEGO DE REVOLVER LA LISTA
        function mostrarGenerosConsola(array,bandera){
            var cadena_temp="";
            if(bandera == 0){
                cadena_temp="Generos Antes=[";
            }
            else{
                cadena_temp="Generos Nuevo=[";
            }
            for(var i=0;i<array.length;i++){
                if(i!=array.length-1){
                    cadena_temp= cadena_temp+array[i].Name + ",";
                }
            }
            cadena_temp= cadena_temp+array[i-1].Name + "]";
            console.log(cadena_temp);
        }
        
            // MUESTRA POR LA PAGINA HTML LOS COMERCIALES
        function mostrarListaComerciales(){
            var cadena_comercial= "";
            for(var i=0; i< comerciales.length ; i++){
                cadena_comercial=cadena_comercial + "Comerciales:[";
                for(var j=0; j<comerciales[i].lista.length; j++){
                    cadena_comercial = cadena_comercial + comerciales[i].lista[j];
                    if( j != comerciales[i].lista.length-1){
                        cadena_comercial = cadena_comercial + ",";
                    }
                }
                cadena_comercial=cadena_comercial + "]<BR>";
            }
            document.getElementById("txtComercial").innerHTML = cadena_comercial;
        }

    // MUESTRA POR LA PAGINA HTML LOS GENEROS ACTIVOS
        function mostrarListaGeneros(generos_A_P){
            var cadena_genero= "";
            var pos_A_P=cont_A_P;
            // bloque de genero
            for(var i=0; i< generos_A_P.length ; i++){
                cadena_genero=cadena_genero + generos_A_P[i].Name+":[";
                for(var j=0; j<generos_A_P[i].lista.length; j++){
                    cadena_genero = cadena_genero + generos_A_P[i].lista[j];
                    if( j != generos_A_P[i].lista.length-1){
                        cadena_genero = cadena_genero + ",";
                    }
                }
                cadena_genero=cadena_genero + "]<BR>";
            }
            document.getElementById("txtGenero").innerHTML = cadena_genero;
        }

        function getRandomInt(min, max) {
            return Math.floor(Math.random() * (max - min)) + min;
        }

        function seleccionar_algoritmo_revolver(p_eliminar){
            var arreglo=[1,2,3,4,5,6,7,8];
            var porcentaje=parseFloat(p_eliminar);
            var n_eliminar=parseInt(arreglo.length*porcentaje/100);
            var eliminado_sattolo=[];
            var eliminado_fisher=[];
            var elegido=0;
            var total_sattolo=0;
            var total_fisher=0;
            
            
            // ELECCION PARA FISHER YATES
            while(1){
                elegido=getRandomInt(0,8);
                if(eliminado_fisher.indexOf(elegido)==-1){
                    eliminado_fisher.push(elegido);                            
                }
                if(eliminado_fisher.length==n_eliminar) break;
            }
            //alert(eliminado_fisher);
            
            // ELECCION PARA SATTOLO
            while(1){
                elegido=getRandomInt(0,8);
                if(eliminado_sattolo.indexOf(elegido)==-1){
                    eliminado_sattolo.push(elegido);                            
                }
                if(eliminado_sattolo.length==n_eliminar) break;
            }
            //alert(eliminado_sattolo);
            //alert(arreglo);
            
            // ELIMINAR LOS ESCOGIDOS DE FISHER DEL ARREGLO
            var tmp_fisher=[];
            for(var i=0;i<arreglo.length;i++){
                tmp_fisher[i]=arreglo[i];
            }
            for(var i=0;i<n_eliminar;i++){
                removeItemFromArr(tmp_fisher,arreglo[eliminado_fisher[i]]);
            }
            console.log('Restantes Fisher:');
            console.log(tmp_fisher);
            
            // ELIMINAR LOS ESCOGIDOS DE SATTOLO DEL ARREGLO
            var tmp_sattolo=[];
            for(var i=0;i<arreglo.length;i++){
                tmp_sattolo[i]=arreglo[i];
            }
            
            for(var i=0;i<n_eliminar;i++){
                removeItemFromArr(tmp_sattolo,arreglo[eliminado_sattolo[i]]);
            }
            console.log('Restantes Sattolo:');
            console.log(tmp_sattolo);
            
            
            for(var j=0;j<tmp_fisher.length;j++){    
                total_fisher=total_fisher+parseInt(tmp_fisher[j]);
                total_sattolo=total_sattolo+parseInt(tmp_sattolo[j]);
            }
            if(total_fisher==total_sattolo) {
                console.log('La suma de fisher y sattollo son iguales');
                seleccionar_algoritmo_revolver(p_eliminar);
            }
            else {
                console.log('Total Fisher:'+total_fisher+',Total Sattolo:'+total_sattolo);
            }
            if(total_fisher>total_sattolo){
                return 0;
            }
            else{
                return 1;
            }
            //alert('STOP');
            /*
            elegido=getRandomInt(0,8);
            // ELECCION PARA SATTOLO
            for(let i=0;i<arreglo.length-n_eliminar;i++){
                
            }*/
            
            
        }
        

// MEZCLAR LA LISTA DE ACUERDO AL MODO DE REVOLVER
        var pasado='';
        function mezclar(array,pos,generos_A_P_T){ 
            var tempLista =new Array();
            var fisherLista=new Array();
            var ultimoLista;
            const len = array.length;
            // copiando datos al nuevo arreglo
            //alert(generos_A_P_T.modo_revolver
            
            for(let i = 0;i < len; i++){
                tempLista[i]=array[i];
                fisherLista[i]=array[i];
            }
            var constante = shuffleArray(fisherLista);
            
                    
            if(generos_A_P_T.modo_revolver == 4){
                var algoritmo=seleccionar_algoritmo_revolver(generos_A_P_T.p_eliminar);
                if(algoritmo==0) console.log('Algoritmo Escogido: Fisher Yates');
                if(algoritmo==1) console.log('Algoritmo Escogido: Sattolo');
            }
            
            
        /*    if(generos_A_P_T.modo_revolver == 2){    // MODO DE REVOLVER 0
                if(temporal == constante[0]){
                    temporal=constante[1];
                }
                else temporal=constante[0];
            }
            else*/ 
            
            
            
            if(generos_A_P_T.modo_revolver == 1 || generos_A_P_T.modo_revolver == 2 || generos_A_P_T.modo_revolver == 3 ){
                for(var j=0; j<len; j++){
                    temporal=constante[j];
                    if(generos_A_P_T.seleccion_pasado.length==0 && temporal==pasado && j==0){
                        console.log('TEMPORAL ES IGUAL A PASADO');
                        temporal=constante[j+1];
                    }
                    //console.log('tamano genero pasado:'+generos_A_P_T.seleccion_pasado.length);
                    if(generos_A_P_T.seleccion_pasado.indexOf(temporal)==-1){
                        generos_A_P_T.seleccion_pasado.push(temporal);//constante[j]);
                        if(tempLista.indexOf(temporal)==len-1 && generos_A_P_T.modo_revolver == 1){
                            generos_A_P_T.seleccion_pasado.pop();    
                            continue;
                        }
                        break;    
                    }
                }
                //console.log('temporal2:'+temporal);
                //console.log('pasado2:'+pasado);
                if(generos_A_P_T.lista.length == generos_A_P_T.seleccion_pasado.length){
                    pasado=generos_A_P_T.seleccion_pasado.pop();
                    //console.log('temporal:'+temporal);
                    //console.log('pasado:'+pasado);
                    generos_A_P_T.seleccion_pasado = [];
                    //generos_A_P_T.seleccion_pasado.push(pasado);
                }
            
                escalar = tempLista.indexOf(temporal);
                // verificación
                console.log("");
                console.log("Audio Escogido="+temporal+" posicion="+escalar);
            }
            console.log("Array Antes=["+tempLista+"]");
            ultimoLista = tempLista[len-1];
            
            if(generos_A_P_T.modo_revolver == 2){    
                insertSattolo(array,temporal,escalar);
            }
            if(generos_A_P_T.modo_revolver == 1){

                insertSattolo(array,temporal,0);
                var validacion=false;
                while(validacion==false){
                    
                    validacion=true;
                    if(array.length-1==generos_A_P_T.seleccion_pasado.length){
                        if(generos_A_P_T.seleccion_pasado.indexOf(lista[array.length-1])==-1){
                            //console.log('solucion_________________________________________________');
                            
                            //console.log(array);
                            //console.log(tempLista);
                            //console.log('___________________________________________________________');
                            //array=tempLista;
                            insertSattolo(array,temporal,0);
                            if(generos_A_P_T.seleccion_pasado.indexOf(lista[array.length-1])!=-1){
                                //.......
                            }
                            else
                                validacion=false;                        
                        }
                    }
                    
                }
                //console.log(array);
                //console.log('N Pasado:'+generos_A_P_T.seleccion_pasado.length);
                //console.log('N Array:'+array.length);
                //console.log('temporal:'+temporal);
                //console.log('Ultimo del Array:'+lista[array.length-1]);                
                //console.log(generos_A_P_T.seleccion_pasado.length);
            }
            if(generos_A_P_T.modo_revolver == 3){
                insertSattolo(array,temporal,array.length-1);
            }
            
            if(generos_A_P_T.modo_revolver == 4){ 
                if(algoritmo==0){
                    lista=shuffleArray(array);
                }else if(algoritmo==1){
                    lista=sattolo(array);
                }
            }

            var encontrar=false;
            var contador;
            var contador2;
             while(encontrar == false){
                encontrar = true;
                contador=0;
                 contador2=0;
                for(var i=0; i < lista.length; i++){
                    if(tempLista[i] != lista[i])
                        break;
                    contador++;
                }
                //Paso 1.- verificacion de igualdad
                if(contador==lista.length){
                    encontrar = false;
                    console.log("son iguales");
                    if(generos_A_P_T.modo_revolver == 2){    
                            insertSattolo(array,temporal,escalar);
                    }
                    if(generos_A_P_T.modo_revolver == 1){
                        insertSattolo(array,temporal,0);
                    }
                    if(generos_A_P_T.modo_revolver == 3){ 
                        insertSattolo(array,temporal,array.length-1);
                    }
                    if(generos_A_P_T.modo_revolver == 4){ 
                        if(algoritmo==0){
                            lista=shuffleArray(array);
                        }else if(algoritmo==1){
                            lista=sattolo(array);
                        }
                    }
                    contador=0;
                }
                //Paso 2.- verificacion de ultimo con primero
                if(ultimoLista ==array[0]){
                    encontrar = false;
                    if(generos_A_P_T.modo_revolver == 2){    
                        insertSattolo(array,temporal,escalar);
                    }
                    if(generos_A_P_T.modo_revolver == 1){
                        console.log("ultimo igual a primero 2");
                        //console.log(array);
                        insertSattolo(array,temporal,0);
                        //console.log(lista);
                    }
                    if(generos_A_P_T.modo_revolver == 3){
                        console.log("ultimo igual a primero 3");
                        insertSattolo(array,temporal,array.length-1);
                    }
                    if(generos_A_P_T.modo_revolver == 4){ 
                        console.log("ultimo igual a primero 4");
                        if(algoritmo==0){
                            lista=shuffleArray(array);
                        }else if(algoritmo==1){
                            lista=sattolo(array);
                        }
                    }
                }
                //Paso 3.- verificacion de posicion repetida
                if(generos_A_P_T.modo_revolver == 3){
                    var t_lista=tempLista;
                    removeItemFromArr(t_lista,lista [lista.length-1]);
                    //console.log(t_lista);
                    for(i=0,j=0;i<lista.length-1; i++){
                        if(t_lista[i] == lista [i])
                            contador2++;
                    }
                    if(contador2>0){
                        encontrar = false;
                        console.log("hay una posicion repetida3");
                        insertSattolo(array,temporal,array.length-1);    
                    }
                    contador2=0;
                }
                if(generos_A_P_T.modo_revolver == 2){
                    for(var i=0; i < lista.length; i++){
                    if(tempLista[i] == lista[i])
                        contador2++;
                    }
                    if(contador2>1){
                        encontrar = false;
                        console.log("hay una posicion repetida1");
                        insertSattolo(array,temporal,escalar);
                    }
                    contador2=0;
                }
                if(generos_A_P_T.modo_revolver == 1){    // ...................................
                    var t_lista=tempLista;
                    removeItemFromArr(t_lista,lista [0]);
                    //console.log(t_lista);
                    //console.log(lista);
                    for(i=1,j=0;i<lista.length; i++,j++){
                        //console.log(t_lista[j]+'='+lista [i]);
                        if(t_lista[j] == lista [i]){
                            contador2++;
                        }
                    }
                    //console.log('contador2:'+contador2);
                    if(contador2>0){
                        encontrar = false;
                        console.log("hay una posicion repetida2");
                        //console.log(array);
                        insertSattolo(array,temporal,0);
                        
                        for(let i=0;i<array.length;i++){
                                if(generos_A_P_T.seleccion_pasado.indexOf(array[i])==-1){
                                    var faltante=array[i];
                                    //console.log("Faltante:"+array[i]);
                                    if(faltante==lista[array.length-1]){
                                        //console.log('pruebaaaaaaaaaaaaaaaaaaaaaaaaaaaaa');
                                        insertSattolo(array,temporal,0);
                                    }
                                }
                        }
                        
                        //console.log(lista);
                    }
                    contador2=0;
                }
                if(generos_A_P_T.modo_revolver == 4 && algoritmo==1){    // ...................................
                    var t_lista=tempLista;
                    for(i=0;i<lista.length; i++,j++){
                        if(t_lista[i] == lista [i]){
                            contador2++;
                        }
                    }
                    if(contador2>0){
                        encontrar = false;
                        console.log("hay una posicion repetida4");
                        if(algoritmo==0){
                            lista=shuffleArray(array);
                        }else if(algoritmo==1){
                            lista=sattolo(array);
                        }
                    }
                    contador2=0;
                }
            }
            // verificación
            console.log("Array Nuevo=["+lista+"]");
            document.getElementById("txtArray").innerHTML = "["+lista+"]";
            escalar=-1;
        }

    // AGREGAR A COMERCIALES LOS TRACKS A LA LISTA DE REPRODUCCION
        function lista_reproduccion_comerciales(){
            // Añandiendo a lista de reproduccion los comerciales
            var i=0; j=0, k=0; 
            var inicio=0;
            for(i=0; i< comerciales.length ; i++){
                inicio=     parseInt(comerciales[i].contador);
                if(parseInt(comerciales[i].tipo) != 2){
                    if((inicio+parseInt(comerciales[i].Ntracks))<=comerciales[i].lista.length){
                        for(j=inicio,k=0; j< parseInt(comerciales[i].Ntracks)+inicio; j++,k++){
                            comerciales[i].reproduccion[k]=comerciales[i].lista[j];
                        }
                    // aqui va ultima posicion y audio    
                    }else if(j<comerciales[i].lista.length){
                        for(j=inicio,k=0; j<comerciales[i].lista.length; j++,k++){
                            comerciales[i].reproduccion[k]=comerciales[i].lista[j];
                        }
                        if(k < parseInt(comerciales[i].Ntracks)){
                            mezclar(comerciales[i].lista,i,comerciales[i]);
                            var tope=parseInt(comerciales[i].Ntracks)-k;    
                            for(var w=0; w < tope; w++,k++){
                                comerciales[i].reproduccion[k]=comerciales[i].lista[w];
                            }
                            j=w;
                        }
                    }
                    comerciales[i].contador=j; // ultima posicion de la lista genero de reproduccion
                    comerciales[i].ultima= comerciales[i].lista[j]; // ultimo audio de la lista de reproduccion
                    if(comerciales[i].lista.length==j){
                        mezclar(comerciales[i].lista,i,comerciales[i]);
                        comerciales[i].contador=0; // ultima posicion de la lista genero de reproduccion
                        comerciales[i].ultima= "";
                        j=0;
                    }
                }
            }
            //console.log(comerciales);
//            mostrarListaComerciales();
        }

    // AGREGA A GENEROS LOS TRACKS A LA LISTA DE REPRODUCCION
        function lista_reproduccion_generos(){
            var i=0,j=0,k=0;
            var inicio=0;
            // Añandiendo a lista de reproduccion los generos
            for(i=0;i<generos_A_P.length;i++){
                inicio=     parseInt(generos_A_P[i].contador);
            //    console.log("Contador="+generos_A_P[i].contador+', Genero='+generos_A_P[i].Name);                        // prueba 1
                j=inicio;
                if((inicio+parseInt(generos_A_P[i].Ntracks))<=generos_A_P[i].lista.length){
                    for(j=inicio,k=0; j< parseInt(generos_A_P[i].Ntracks)+inicio; j++,k++){
                        generos_A_P[i].reproduccion[k]=generos_A_P[i].lista[j];
                    }
                // aqui va ultima posicion y audio    
                }else if(j<generos_A_P[i].lista.length){
                    for(j=inicio,k=0; j<generos_A_P[i].lista.length; j++,k++){
                        generos_A_P[i].reproduccion[k]=generos_A_P[i].lista[j];
                    }
            //    console.log('Reproduccion:');                                                                        // prueba 2
            //    console.log(generos_A_P[i].reproduccion);
                    if(k < parseInt(generos_A_P[i].Ntracks)){
                        mezclar(generos_A_P[i].lista,i,generos_A_P[i]);
                    //    console.log('Prueba4:');
                        var tope=parseInt(generos_A_P[i].Ntracks)-k;                                        // prueba 4
                        for(var w=0; w < tope; w++,k++){
                        //    console.log(w);
                            //console.log(parseInt(generos_A_P[i].Ntracks)-k);
                            generos_A_P[i].reproduccion[k]=generos_A_P[i].lista[w];
                        }
                    //    console.log('Reproduccion2:');                                                                        // prueba 3
                    //    console.log(generos_A_P[i].reproduccion);
                        j=w;
                    }
                }
                                                                            
                generos_A_P[i].contador=parseInt(j); // ultima posicion de la lista genero de reproduccion
                generos_A_P[i].ultima= generos[i].lista[j]; // ultimo audio de la lista de reproduccion
                if(generos_A_P[i].lista.length==parseInt(j)){
                    mezclar(generos_A_P[i].lista,i,generos_A_P[i]);
                    generos_A_P[i].contador=0; // ultima posicion de la lista genero de reproduccion
                    generos_A_P[i].ultima= "";
                    j=0;
                }
            }
//            mostrarListaGeneros(generos_A_P);
            //var ppp=generos_A_P;
            //console.log(ppp);
            //alert('1');
        }

    // LIMPIA LA LISTA DE REPRODUCCION DE LOS GENEROS
        function limpiar_reproduccion_generos(){
            for(var i=0; i< generos.length;i++){
                generos[i].reproduccion= new Array();    
            }
        }

    // LIMPIA LA LISTA DE REPRODUCCION DE LOS COMERCIALES
        function limpiar_reproduccion_comerciales(){
            for(var i=0; i< comerciales.length;i++){
                comerciales[i].reproduccion= new Array();    
            }
        }
        
    // CREA LA LISTA DE REPRODUCCION
        function reproducir(generos_A_P){
            var i=0,j=0,k=0;
            var l_reproducir=new Array();
            var pos_A_P=0;
            lista_reproduccion_generos();    // asigna a reproduccion los tracks de generos a reproducir
            for(i=0;i<generos_A_P.length;i++){
                
                limpiar_reproduccion_comerciales(); // limpia la variable reproduccion de los comerciales
                
                lista_reproduccion_comerciales(); // asigna a reproduccion los tracks de comerciales a reproducir
                // cargando los comerciales de los generos******************************************************
                if(comerciales_generos == false){
                    comerciales_generos = true;
                }
                else{
                    for(var w=0;w<comerciales.length;w++){
                        if(comerciales[w].ID == generos_A_P[i].ID_comerciales_generos){
                            var inicio=parseInt(comerciales[w].contador);
                            comerciales[w].reproduccion = new Array();
                            for(var p=0;p<parseInt(comerciales[w].Ntracks);p++){
                                comerciales[w].reproduccion[p] = comerciales[w].lista[inicio++];
                            }
                            comerciales[w].contador = parseInt(inicio);
                            if(comerciales[w].lista.length == inicio){
                                mezclar(comerciales[w].lista,w,comerciales[w]);
                                comerciales[w].contador=0; // ultima posicion de la lista comerciales de reproduccion
                                inicio=0;    
                            }
                            for(var z=0;z<comerciales[w].reproduccion.length;z++){
                                l_reproducir[k++]=comerciales[w].reproduccion[z];
                            }
                        }
                    }
                }
                // fin comerciales de los generos*************************************************************************
                // cargando generos****************************************************************************************
                for(j=0;j<generos_A_P[i].reproduccion.length;j++){
                    l_reproducir[k++]=generos_A_P[i].reproduccion[j];
                }
                // fin de genero ******************************************************************************************
                
                for(var w=0;w<comerciales.length;w++){
                    // cargando comerciales entradas *************************************************************************
                    if(parseInt(comerciales[w].tipo) == 3){
                        for(var z=0;z<comerciales[w].reproduccion.length;z++){
                            l_reproducir[k++]=comerciales[w].reproduccion[z];
                        }
                    }
                    // fin comerciales entradas ********************************************************************************
                }
                        
                for(var w=0;w<comerciales.length;w++){
                    // cargando comerciales generales *************************************************************************
                    if(parseInt(comerciales[w].tipo) == 1){
                        for(var z=0;z<comerciales[w].reproduccion.length;z++){
                            l_reproducir[k++]=comerciales[w].reproduccion[z];
                        }
                    }
                    // fin comerciales *****************************************************************************************
                }
                
            }
            limpiar_reproduccion_generos();
            lista=l_reproducir;    
        }
        
    // MEZCLA LA LISTA DE LOS GENEROS
        function mezclarGenerosLista(){
            // mezclar con fisher y sattolo
            for(let i=0; i<generos.length; i++){ // controla los generos 
                if(generos[i].lista.length>=4){
                    if(parseInt(generos[i].Ntracks)!=0)
                        mezclar(generos[i].lista,i,generos[i]);
                }else{
                    alert("La lista del genero "+generos[i].Name+" debe poseer 4 o mas audios");
                    generos[i].Ntracks=0;
                }
            }
        }

    // MEZLCA LA LISTA DE LOS COMERCIALES
        function mezclarComercialesLista(){
            // mezclar con fisher y sattolo
            
            for(let i=0; i<comerciales.length; i++){ // controla los comerciales 
                if(comerciales[i].lista.length>=4){
                    if(parseInt(comerciales[i].Ntracks)!=0)
                        mezclar(comerciales[i].lista,i,comerciales[i]);
                }else{
                    alert("La lista del comercial posicion "+i+" debe poseer 4 o mas audios");
                    comerciales[i].Ntracks=0;
                }
            }
        }

    // CARGA EN VARIABLE LAS DIFERENTES COMBINACIONES DE LA PERMUTACION
        function permuta (cad_I,cad_D){
            if (cad_D.length == 1){
                permutacion[conta++]=cad_I + cad_D;
                      return;
            }
            for (var i =0; i < cad_D.length ; i++){
                permuta (cad_I + cad_D.charAt(i), cad_D.replace(cad_D.charAt(i),""));
            } 
            return;
        }

    // ORDENA LA PERMUTACION
        function ordenar_permutacion(fisher){
            var temp_generos_A_P; 
            for(let i=0;i<generos_A_P.length; i++){
                for(let j=0; j<generos_A_P.length; j++){
                    if(generos_A_P[j].posicion_Perm == fisher.charAt(i)){
                        temp_generos_A_P=generos_A_P[i];
                        generos_A_P[i]=generos_A_P[j];
                        generos_A_P[j]=temp_generos_A_P;
                    }
                }
            }
        }

    // CONSULTA SI LA COMBINACION DE GENEROS EXISTENTE EN PERMUTACION YA FUE SELECCIONADO
        function consultar_pasado(fisher_elegido){
            var encontrar=true;
            for(let i=0; i<permutado_pasado.length;i++){
                if(permutado_pasado[i]==fisher_elegido){
                    encontrar=false;
                    break;
                }
            }
            return encontrar;
        }

    // CONTROLA LA PERMUTACION
        function permutaciones(array){
            var tam_array=array.length;
            var cadena="";
            var fisher_elegido="";
            if(activar_permutacion==false){
                for(let i=0;i<tam_array;i++){
                    cadena+=generos_A_P[i].posicion_Perm;
                }
                permuta("",cadena);
                activar_permutacion=true;
            }
            //console.log(permutado_pasado.length+'='+conta);
            if(permutado_pasado.length==conta){
                permutacion=permutado_pasado;
                permutado_pasado= [];
            }        
            
            if(permutado_pasado.length==0 && permutacion.length==conta){
                var factorial=1;            
                for(let i=1;i<=generos_A_P.length;i++) factorial=factorial*i;
        //        console.log("Lista de permutaciones");
                permutacion=shuffleArray(permutacion); // fisher yates a los elementos permutados

//                alert(permutacion);
                var factorial=1;
                for(let i=1;i<=generos_A_P.length;i++) factorial=factorial*i;
                var reacomodar=false;
                while(reacomodar==false){
                    reacomodar=true;
                    var cont_perm=0;
                    for(var j=0;j<factorial;j++){    
                        for(var i=1+j;i<factorial;i++){ 
                            //    console.log('i='+i+', j='+j);
                                //console.log(permutacion[j].slice(-1)+' != '+permutacion[i].slice(0,1));
                                if(permutacion[j].slice(-1) != permutacion[i].slice(0,1)){
                                    var temp=permutacion[j+1];
                                    permutacion[j+1]=permutacion[i];
                                    permutacion[i]=temp;
                                    break;
                                }
                                if(i==factorial-1 && j==i-1 && permutacion[j].slice(-1)==permutacion[i].slice(0,1)){
                                    console.log('ultimo iguales');
                            //        console.log(permutacion);
                                    permutacion=shuffleArray(permutacion);
                                    reacomodar=false;
                                    //permutado_pasado= [];
                                    //permutaciones(array);

                                }
                                cont_perm++;
                                //console.log(cont_perm);
                                if(cont_perm>factorial*factorial){
                                    permutacion=shuffleArray(permutacion);
                                    reacomodar=false;
                                    //i=0;
                                    j=factorial;
                                    break;
                                }
//                                alert(permutacion);
                        }
                    }
                    
                    // verificar si esta bien estructurado
                    for(let i=0;i<factorial-1;i++){
                        if(permutacion[i].slice(-1) == permutacion[i+1].slice(0,1)){
                            permutacion=shuffleArray(permutacion);
                            reacomodar=false;
                            break;
                        }
                    }
                    
                }
//                alert('finnnnnnnnnn');
//                alert(permutacion);
                var tmp_permutacion=[];
                for(var i=0, j=factorial-1;j>=0;j--,i++){
                    tmp_permutacion[i]=permutacion[j];
                }    
                
                permutacion=tmp_permutacion;
            }
/*            if(permutacion.length==factorial)
                console.log(permutacion);                            */
//            alert('Pasoooooooo');
//            alert(permutacion);
            while(1){
                fisher_elegido=permutacion.pop();
                if(fisher_elegido!=undefined) break;
            }
        //    console.log(permutacion);        
        //    console.log('fisher:'+fisher_elegido);
            if(consultar_pasado(fisher_elegido)==true ){
                ordenar_permutacion(fisher_elegido);
                permutado_pasado.push(fisher_elegido);
            }    
            
            
//            console.log("Combinaciones pendiente");
//            console.log(permutacion);
            //console.log(permutado_pasado.length+'='+conta);
            if(permutado_pasado.length==conta){
                console.log("Combinaciones ya realizadas");
                console.log(permutado_pasado);
            }
        }
        
    
    
        
    // ANALIZADOR DE AUDIO    
        function frameLooper(){
            window.requestAnimationFrame(frameLooper);
            fbc_array = new Uint8Array(analyser.frequencyBinCount);
            analyser.getByteFrequencyData(fbc_array);
            ctx.clearRect(0, 0, canvas.width, canvas.height); // Clear the canvas
            ctx.fillStyle = color_barritas; // Color of the bars
            bars = 100;
            for (var i = 0; i < bars; i++) {
                bar_x = i * 7;
                bar_width = 5;
                bar_height = -(fbc_array[i] / 2);
                ctx.fillRect(bar_x, canvas.height, bar_width, bar_height);
            }
        }
        
        // PRINCIPIO PARA MEZCLA, CREACION Y REPRODUCCION DE LOS AUDIOS
        function principio(){
        //console.log(comerciales);
        //if(RANDOM!=3){
            mezclarComercialesLista(); // MEZCLA LA LISTA DE LOS COMERCIALES POR FISHER YATES Y SATTOLO 
            mezclarGenerosLista(); // MEZCLA LA LISTA DE LOS GENEROS POR FISHER YATES Y SATTOLO 
        //}
            crear_listas();
    
            //mezclar_generos_RANDOM(); // MEZCLA LOS GENEROS POR EL RANDOM CORRESPONDIENTE
            //reproducir(generos_A_P); // CREA LA LISTA DE REPRODUCCION
            //guardar_variables_lista();
        //    setTimeout( inicio(), 3000);

        }

    // EVENTO PARA EL BOTON NEXT
        next.addEventListener("click", siguiente, false);
        
    // EVENTO PARA EL FIN DEL AUDIO
        player.addEventListener("ended", function(){
            //verificar_cambios_configuracion();
            siguiente();    
        });


    // CARGA LOS AUDIOS CON EXTENSIONES LOG, TXT, INI, JSON, JS, PHP3, PH5, PHP7, PHTML
        function cargar_audio(extension , ruta){
            var audio;
            var req = new XMLHttpRequest();
            req.open('GET', "php/val_audio.php?extension="+extension+"&ruta="+ruta, false);
            req.onload = onLoad;
            req.send(null); 
            function onLoad(e) {
                if(e.target.readyState == 4 && e.target.status == 200) {
                        audio =  decodeURIComponent(e.target.responseText);
                        //console.log(audio.substring(0,50));
                        player.src = audio;
                        //player.play();    
                          var playPromise = player.play();
                         
                          if (playPromise !== undefined) {
                            playPromise.then(_ => {
                              // Automatic playback started!
                              // Show playing UI.
                              // We can now safely pause video...
                              player.play();
                            })
                            .catch(error => {
                              // Auto-play was prevented
                              // Show paused UI.
                            });
                          }
                }
            }
        }