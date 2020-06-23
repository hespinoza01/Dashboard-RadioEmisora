// Variables
// VARIABLES GLOBALES
let RANDOM = 0, // 0- SIN RANDOM (AUSENTE PRESENTE), 1- FISHER YATES (AUSENTE PRESENTE), 2- SATTOLO (AUSENTE PRESENTE), 3- PERMUTACION
	PIZZICATO = 0, // 0- SIN EFECTO , 1- EFECTO LOS AUDIOS, 2- EFECTOS LOS COMERCIALES, 3- EFECTOS AMBOS 
	SEPARAR_GENERO = 1, // 1.- LIBRE , 2.- SEPARAR (NOTA: APLICA A 5 O MAS GENEROS CON RANDOM 1 Y 2)
	nronda = 0; // CANTIDAD DE RONDAS A GENERAR

// VARIABLES DE DATOS EMISORA (LOGO, NOMBRE Y SLOGAN)
let nombre_emisora, color_emisora, letra_emisora, slogan_emisora, color_slogan, letra_slogan, url_logo, ancho_logo, largo_logo, redondeo;

// VARIABLES DE FUNCIONAMIENTO
//var debug = false;
let temporal,
	escalar = -1, 
	cont_A_P= -1, 
	temporal_A_P=0, 
	generos_A_P = new Array(), // VARIABLE QUE GUARDA LOS GENEROS ACTIVOS AL APLICAR AUSENTE PRESENTE
	permutacion = [], // guarda las combinaciones de permutacion
	permutado_pasado = [], // guarda la combinacion ya recorrida
	activar_permutacion = false, // ACTIVA EL USO DE LA PERMUTACION
	comerciales_generos = false, // 
	conta = 0,    // VARIABLE PARA LAS FUNCIONES PERMUTA Y PERMUTACION
	iniciar_R_2 = false; // VARIABLE PARA LA FUNCION MEZCLAR_GENEROS_RANDOM

// VARIABLES DE CANCION Y TIEMPO
let version = 0, // Control de versiones del programa [0]. Desactivado (Sincroniza canciones) [1]. Activado (Sincroniza cancion y tiempo)
	current_lista = 0, // CONTROLA LA LISTA EN QUE VA EL REPRODUCTOR
	current_times = 0, // CONTROLA EL TIEMPO EN QUE VA EL TRACKS
	current_track = 0, // CONTROLA EL TRACKS DE LA LISTA DE REPRODUCCION
	audio_track = '',
	time_delete = 0; // Tiempo en que borrará lista generada, luego de que no haya conexión con ningún oyente.


// VARIABLES DE CONTROL
let player = document.getElementById('Playing'), // CONTROLA EL ELEMENTO AUDIO
	next = document.getElementById('next'), // CONTROLA EL BOTON NEXT
	btnPlay = document.getElementById("btnplay"); // CONTROLA EL BOTON PLAY

// VARIABLES DE DISEÑO
let holding = false,
	track = document.getElementById('track'),
	progress = document.getElementById('progress'),
	song, audio, duration, porcencap,
	playing = false,
	MEDIA_ELEMENT_NODES = new WeakMap(),
	ANALIZER_NOTE = new WeakMap(),
	o1 = {}, 
	analizador;

// VARIABLES DE ANALIZADOR DE AUDIO
let canvas, ctx, source, context, analyser, fbc_array, bars, bar_x, bar_width, bar_height, padre;
let encipa = true;

// LISTAS 
let LISTA = []; // lista de reproduccion
let COMERCIALES = []; // lista de comerciales
let GENEROS = []; // lista de generos

function getJson(fromData, errorMessage){
	try{
        if (fromData === null) { return {};}
        if ( (typeof fromData === 'function') || (typeof fromData === 'object') ) return fromData;

        return JSON.parse(fromData); 
    }
	catch(err){ console.error(errorMessage, err); return {}; }
}


document.onkeypress = function (elEvento){
    var evento = elEvento || window.event;
    var codigo = evento.charCode || evento.keyCode;
    var caracter = String.fromCharCode(codigo);
    if(caracter=='1'){
        console.log("Lista Actual:");
        console.log("Lista "+current_lista+":["+lista.toString()+"]");
    }
    if(caracter=='2'){
        console.log("Listas Generadas:");
        fetch('php/mostrar_listas.php')
        	.then(res => res.json())
        	.then(data => {
        		var lista_rep=JSON.parse(data.lista_reproduccion); // MUESTRA LA LISTA DE REPRODUCCION
                for(let i=0; i<lista_rep.length ; i++){
                    console.log("Lista "+i+':['+lista_rep[i].lista+"]");
                }
        	})
        	.catch(error => console.error("Error fetch into 'manejador': ", error));
    }
}

// SINCRONIZAR CON DATOS GUARDADOS EN JSON
function sincronizar() {
	fetch('php/obtener_variables.php')
		.then(res => res.json())
		.then(data => {
			console.log(new Date(), data); // fecha y version del programa

			cargar_variables(data);

			let revolver = getJson(data.revolver, "error al cargar data.revolver into 'sincronizar'");
			
			if(revolver == false){ //|| sessionStorage.getItem('primer_usuario') != null) {
				if(GENEROS.length != 0){
					update_configuracion();
					inicializar_variables();
					principio();
                    //sessionStorage.setItem('primer_usuario', 'true');
				}
			}else {
				if(version != 0 && time_delete != 0) {
					player.currentTime = parseInt(current_times);
				}

				inicio();
			}
		})
		.catch(error => console.error("error on fetch into sincronizar: ", error));
}


// INICIO DE LA REPRODUCCION
function inicio(){
    if(RANDOM==0){
        generos_A_P = burbuja_generos(generos_A_P);
    }

    mostrarListaAudio();
    document.getElementById("txtCancion").innerHTML = "Audio Activo: "+LISTA[current_track];

    // muestra en html el genero activo y la cantidad de tracks
    for(var i=0;i<GENEROS.length; i++){
        if(GENEROS[i].lista.indexOf(LISTA[current_track])!= -1){
            document.getElementById("txtGeneroActivo").innerHTML = "Genero Activo: "+GENEROS[i].Name+" Ntracks:"+GENEROS[i].Ntracks;
            break;    
        }
    }

    let extension;//, indice;
    if(LISTA.length!=0){
        //indice = LISTA[current_track].lastIndexOf(".");
        extension =  LISTA[current_track].split(".").pop();;
        // EXTENSIONES PERMITIDAS
        if(extension == "js" || extension == "json" || extension == "txt"){
            cargar_audio(extension,LISTA[current_track]);//.substring(3));
        }
        else 
        if(extension == "mp3" || extension == "ogg" || extension == "opus" || extension == "aac" || extension == "m4a" ){ // aac mp3 ogg opus m4a
            audio_track = getAbsolutePath()+LISTA[current_track].substring(3);
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
        else if(extension == "ini" || extension == "log" || extension == "pdf" || extension == "rtf"){
             cargar_audio(extension,LISTA[current_track]);//.substring(3));
        }
    }
    else{
        // sino hay nada en la lista
        alert("No hay lista para reproducir...");
    }        
}

// PASA AL SIGUIENTE AUDIO
function siguiente(){
    current_track++;

    if(current_lista==nronda-1 && current_track == LISTA.length-1){
        procesar_listas();
        inicializar_lista();
        current_lista = 0;
        current_track = 0;
    }
    if(current_track == LISTA.length){
        current_track=0;
        current_lista++;

        if(current_lista==nronda){
            current_lista=0; 
            inicializar_lista();
        }
        else{
            obtener_lista();
        }
    }
    else{
        inicio();
    }
}

// OBTIENE LA LISTA CORRESPONDIENTE DE LIST1.JSON
function obtener_lista(){
    let valor;
    fetch('php/change_list.php?current_lista='+current_lista)
    	.then(res => res.json())
    	.then(data => {
    		LISTA = getJson(data.lista, "Error on get json from 'obtener_lista'");
            current_lista = data.current_lista;
            inicio();   
    	})
    	.catch(error => console.log("Error fetch into 'obtener_lista': ", error));
}

// OBTIENE LAS VARIABLES DE LOS JSON
function obtener_variables(){
    let valor;
    fetch('php/obtener_variables.php')
    	.then(res => res.json())
    	.then(data => {
                console.log(new Date()); // FECHA Y VERSION DEL PROGRAMA
                cargar_variables(data);
    	})
    	.catch(error => console.error("Error fetch into 'obtener_variables': ", error));
}


function procesar_listas(){
    fetch('php/obtener_variables.php')
    	.then(res => res.json())
    	.then(data => {
    		console.log(new Date()); // FECHA Y VERSION DEL PROGRAMA
            cargar_variables(data);
            update_configuracion();
            inicializar_variables();
            mezclarComercialesLista(); // MEZCLA LA LISTA DE LOS COMERCIALES POR FISHER YATES Y SATTOLO 
            mezclarGenerosLista(); // MEZCLA LA LISTA DE LOS GENEROS POR FISHER YATES Y SATTOLO 
            crear_listas();
    	})
    	.catch(error => console.error("Error fetch into 'procesar_listas': ", error));
}


function conf_peticion_servidor(){ // por finalizar
    fetch('php/obtener_current.php')
    	.then(res => res.json())
    	.then(data => {
    		if(data.conectividad=='SI' && data.current_lista==0 && data.current_track==0 && data.current_times<5){    // obtiene la nueva lista    
                current_lista=data.current_lista;
                current_track=0;
                obtener_lista();
            }
            if(data.conectividad=='NO'){    // finaliza todas las conecciones    
                current_track=0;
                current_lista=0;
                if( data.crear=='SI'){    // crea las siguientes listas1
                    update_configuracion();
                    obtener_variables();
                    inicializar_variables();    // inicializa todas las variables y va al principio
                    principio();
                }
                else{    // obtiene la nueva lista
                    setTimeout(function(){ conf_peticion_servidor(); },5000);
                }
            }
            if(data.conectividad=='SI' && data.current_times>5){    // verifica que termine todas las conecciones
                setTimeout(function(){ conf_peticion_servidor(); },4000);
            }
    	})
    	.catch(error => console.error("Error fetch into 'conf_peticion_servidor': ", error));
}

// CARGA LAS VARIABLES DE LOS JSON
function cargar_variables(data) {
	// sincronizar listas generos y comerciales
	GENEROS = getJson(data.lista_generos, "Error al cargar data.lista_generos into 'cargar_variables'");
	COMERCIALES = getJson(data.lista_comerciales, "Error al cargar data.lista_comerciales into 'cargar_variables'");

	// sicroniza la lista de variables
	let lista_variables = getJson(data.lista_variables, "Error al cargar data.lista_variables into 'cargar_variables'");

	// INICIALIZA LOS VALORES OBTENIDOS DEL JSON
    RANDOM = lista_variables.RANDOM;
    PIZZICATO = lista_variables.PIZZICATO; 
    SEPARAR_GENERO = lista_variables.SEPARAR_GENERO;
    time_delete = lista_variables.tiempo_inactividad;
    version = lista_variables.version;
    nronda = lista_variables.nronda;

    let lista_reproduccion = getJson(data.lista_reproduccion, "Error al cargar data.lista_reproduccion into 'cargar_variables'"); // SINCRONIZA LA LISTA DE REPRODUCCION
    let lista_current = getJson(data.lista_current, "Error al cargar data.lista_current into 'cargar_variables'"); // SINCRONIZA LOS CURRENT LISTA, AUDIO Y POR DONDE VA
    let lista_generos_AP = getJson(data.lista_generos_A_P, "Error al cargar data.lista_generos_A_P into 'cargar_variables'"); // SINCRONIZA EL GENERO PRESENTE

    // VARIABLES DE DATOS EMISORA (LOGO, NOMBRE Y SLOGAN)
    nombre_emisora = lista_variables.nombre_emisora;
    color_emisora = lista_variables.color_emisora;
    letra_emisora = lista_variables.letra_emisora;
    slogan_emisora = lista_variables.slogan_emisora;
    color_slogan = lista_variables.color_slogan;
    letra_slogan = lista_variables.letra_slogan;
    url_logo = lista_variables.url_logo;
    ancho_logo = lista_variables.ancho_logo;
    largo_logo = lista_variables.largo_logo;
    redondeo = lista_variables.redondeo;

    // SINCRONIZANDO VARIABLES
    temporal = lista_variables.temporal;  
    escalar = lista_variables.escalar;  
    cont_A_P = lista_variables.cont_A_P;  
    temporal_A_P = lista_variables.temporal_A_P; 
    comerciales_generos = lista_variables.comerciales_generos;
    conta = lista_variables.conta;
    iniciar_R_2 = lista_variables.iniciar_R_2;

    if(RANDOM == 3){
        permutacion = lista_variables.permutacion;
        permutado_pasado = lista_variables.permutado_pasado;
        activar_permutacion = lista_variables.activar_permutacion;
    }

    // SINCRONIZANDO LA VARIABLE DEL GENERO PRESENTE
    generos_A_P = lista_generos_AP.generos_A_P;
    
    // SINCRONIZANDO LOS CURRENT LISTA, AUDIO Y POR DONDE VA
    current_lista = lista_current.current_lista;
    current_track = lista_current.current_tracks;    
    current_times = lista_current.current_times;

    // SINCRONIZANDO LISTA DE REPRODUCCION
    LISTA = getJson(lista_reproduccion.lista, "Error on line 326");
}


// actualizar cambios en la configuracion de los generos y comerciales
function update_configuracion() {
	fetch('php/update_configuracion.php')
		.then(res => res.text())
		.then(data => console.log(data))
		.catch(error => console.error("Error into 'update_configuracion': ", error));
}

// INICIALIZA LAS VARIABLES
function inicializar_variables() {
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
    GENEROS.sort((a, b) => {
    	if(a.ID > b.ID) return 1;
    	if(a.ID < b.ID) return -1;
    	return 0;
    });

    COMERCIALES.sort((a, b) => {
    	if(a.ID > b.ID) return 1;
    	if(a.ID < b.ID) return -1;
    	return 0;
    });

    try {
    	GENEROS.forEach(item => {
    		item.lista.sort();
            item.reproduccion = [];
            item.contador = 0;
            item.ultima = "";
            item.posicion_Perm = 0;
            item.seleccion_pasado = [];
    	});

    	COMERCIALES.forEach(item => {
    		item.lista.sort();
            item.reproduccion = [];
            item.contador=0;
            item.ultima="";
            item.seleccion_pasado=[];
    	});

    	generos_A_P = mostrar_Ausente_Presente();
    }catch(error) { console.log("Error into inicializar_variables:", error); }
}

// DETIENE LA REPRODUCCION E INICIALIZA LA REPRODUCCION
function inicializar_lista(){
	fetch('php/inicializar_lista.php')
		.then(res => res.text())
		.then(data => data)
		.catch(error => console.log("Error into 'inicializar_lista': ", error));
}


// PRINCIPIO PARA MEZCLA, CREACION Y REPRODUCCION DE LOS AUDIOS
function principio() {
	mezclarComercialesLista();
	mezclarGenerosLista();
	crear_listas();
}


// APLICA AUSENTE PRESENTE EN LOS GENEROS
function mostrar_Ausente_Presente() {
	let generos_presente = new Array();
	temporal_A_P = cont_A_P;

	if(cont_A_P == -1){
		cont_A_P = 0;
		GENEROS.forEach((item, i) => {
			if(parseInt(RANDOM) == 3 && parseInt(item.Ntracks) != 0){
				generos_presente.push(item);
			} else if(parseInt(item.Ntracks) != 0 && parseInt(item.AUSENTE_PRESENTE[parseInt(cont_A_P)]) != 0 && item.AUSENTE_PRESENTE.length != 0) {
				generos_presente.push(item);
			}
		});

		cont_A_P = -1;
	}else {
		GENEROS.forEach((item, i) => {
			if(parseInt(RANDOM) == 3 && parseInt(item.Ntracks) != 0){
				generos_presente.push(item);
			}else if(parseInt(item.Ntracks) != 0 && parseInt(item.AUSENTE_PRESENTE[parseInt(cont_A_P)]) != 0 && item.AUSENTE_PRESENTE.length != 0){
                        generos_presente.push(item);
            }
		});
	}

	return generos_presente;
}


// MEZCLA LA LISTA DE LOS GENEROS
function mezclarComercialesLista() {
	// mezclar con fisher y sattolo
	COMERCIALES.forEach((item, i) => {
		if(item.lista.length >= 4){
			if(parseInt(item.Ntracks) != 0){
				mezclar(item.lista, i, item);
			}
		}else{
            alert(`La lista del comercial ${item.Name} debe poseer 4 o mas audios`);
            item.Ntracks=0;
        }
	});
}


// MEZCLA LA LISTA DE LOS GENEROS
function mezclarGenerosLista() {
	// mezclar con fisher y sattolo
	GENEROS.forEach((item, i) => {
		if(item.lista.length >= 4){
			if(parseInt(item.Ntracks) != 0){
				mezclar(item.lista, i, item);
			}
		}else{
            alert(`La lista del genero ${item.Name} debe poseer 4 o mas audios`);
            item.Ntracks=0;
        }
	});
}


// CREA 10 LISTA DE REPRODUCCIONES Y LAS GUARDAS EN LIST1.JSON
function crear_listas() {
	let listas_reproducciones = new Array();

	for(let i=0, j=0; i<parseInt(nronda); i++, j++) {
		mezclar_generos_RANDOM(); // MEZCLA LOS GENEROS POR EL RANDOM CORRESPONDIENTE
		reproducir(generos_A_P); // CREA LA LISTA DE REPRODUCCION

		listas_reproducciones[j] = {
			current_lista: (j),
			lista: LISTA
		}
	}

	console.log('');
    console.log('Listas Generadas:');
    console.log(listas_reproducciones);
    guardar_variables_lista();

    let data = new FormData();
	data.append('lista', JSON.stringify(listas_reproducciones));
	data.append('current_lista', current_lista);

    fetch('php/save_list.php', { method: 'POST', body: data })
    	.then(res => res.json())
		.then(data => {
			LISTA = data.lista;
			current_lista = data.current_lista;
			current_track = 0;

			inicio();

            //if(sessionStorage.getItem('primer_usuario') != null)
            //    inicializar_lista();
		})
		.catch(error => console.error("Error on fetch 'save_list' into 'crear_listas': ", error));
}


// MEZCLAR LA LISTA DE ACUERDO AL MODO DE REVOLVER
let pasado = '';
function mezclar(array, pos, generos_A_P_T) {
	let tempLista = Array.from(array),
		fisherLista = Array.from(array),
		ultimoLista,
		algoritmo;

	const len = array.length,
		  constante = shuffleArray(fisherLista);

	if(generos_A_P_T.modo_revolver == 4) {
		algoritmo = seleccionar_algoritmo_revolver(generos_A_P_T.p_eliminar);

		if(algoritmo == 0) console.log("Algoritmo Escogido: Fisher Yates");
		if(algoritmo == 1) console.log("Algoritmo Escogido: Sattolo");
	}


	if(generos_A_P_T.modo_revolver == 1 || generos_A_P_T.modo_revolver == 2 || generos_A_P_T.modo_revolver == 3) {
		for(let j=0; j<len; j++) {
			temporal = constante[j];

            if(generos_A_P_T.seleccion_pasado.length==0 && temporal==pasado && j==0){
                console.log('TEMPORAL ES IGUAL A PASADO');
                temporal = constante[j+1];
            }

            if(generos_A_P_T.seleccion_pasado.indexOf(temporal)==-1){
                generos_A_P_T.seleccion_pasado.push(temporal);

                if(tempLista.indexOf(temporal)==len-1 && generos_A_P_T.modo_revolver == 1){
                    generos_A_P_T.seleccion_pasado.pop();    
                    continue;
                }
                break;    
            }
        }

        if(generos_A_P_T.lista.length == generos_A_P_T.seleccion_pasado.length){
            pasado=generos_A_P_T.seleccion_pasado.pop();
            generos_A_P_T.seleccion_pasado = [];
        }
    
        escalar = tempLista.indexOf(temporal);

        // verificación
        console.log("");
        console.log("Audio Escogido="+temporal+" posicion="+escalar);
	}


	console.log("Array Antes=["+tempLista+"]");
    ultimoLista = tempLista[len-1];

    if(generos_A_P_T.modo_revolver == 2){    
        insertSattolo(array, temporal, escalar);
    }

    if(generos_A_P_T.modo_revolver == 1){
    	insertSattolo(array, temporal, 0);
        let validacion = false;

        while(validacion == false){    
	        validacion=true;

	        if(array.length-1 == generos_A_P_T.seleccion_pasado.length){
	            if(generos_A_P_T.seleccion_pasado.indexOf(LISTA[array.length-1]) == -1){
	                insertSattolo(array, temporal, 0);

	                if(generos_A_P_T.seleccion_pasado.indexOf(LISTA[array.length-1]) != -1){
	                }
	                else
	                    validacion=false;                        
	            }
	        }  
        }
    }


    if(generos_A_P_T.modo_revolver == 3){
        insertSattolo(array, temporal, array.length-1);
    }

    if(generos_A_P_T.modo_revolver == 4){ 
        if(algoritmo == 0){
            LISTA = shuffleArray(array);
        }else if(algoritmo == 1){
            LISTA = sattolo(array);
        }
    }

    let encontrar = false,
    	contador,
    	contador2;

	while(encontrar == false){
		encontrar = true;
		contador = 0;
		contador2 = 0;

		LISTA.forEach((item, i) => {
			if(tempLista[i] != item)
				return;
			contador++;
		});

		//Paso 1.- verificacion de igualdad
        if(contador == LISTA.length){
            encontrar = false;
            console.log("son iguales");
            if(generos_A_P_T.modo_revolver == 2){    
                insertSattolo(array, temporal, escalar);
            }
            if(generos_A_P_T.modo_revolver == 1){
                insertSattolo(array, temporal, 0);
            }
            if(generos_A_P_T.modo_revolver == 3){ 
                insertSattolo(array, temporal, array.length-1);
            }
            if(generos_A_P_T.modo_revolver == 4){ 
                if(algoritmo == 0){
                    LISTA = shuffleArray(array);
                }else if(algoritmo == 1){
                    LISTA = sattolo(array);
                }
            }
            contador = 0;
        }

        //Paso 2.- verificacion de ultimo con primero
        if(ultimoLista == array[0]){
            encontrar = false;
            if(generos_A_P_T.modo_revolver == 2){    
                insertSattolo(array, temporal, escalar);
            }
            if(generos_A_P_T.modo_revolver == 1){
                console.log("ultimo igual a primero 2");
                insertSattolo(array, temporal, 0);
            }
            if(generos_A_P_T.modo_revolver == 3){
                console.log("ultimo igual a primero 3");
                insertSattolo(array, temporal, array.length-1);
            }
            if(generos_A_P_T.modo_revolver == 4){ 
                console.log("ultimo igual a primero 4");
                if(algoritmo == 0){
                    LISTA = shuffleArray(array);
                }else if(algoritmo == 1){
                    LISTA = sattolo(array);
                }
            }
        }

        //Paso 3.- verificacion de posicion repetida
        if(generos_A_P_T.modo_revolver == 3){
            var t_lista = tempLista;
            removeItemFromArr(t_lista, LISTA[LISTA.length-1]);

            for(let i=0, j=0; i<LISTA.length-1; i++){
                if(t_lista[i] == LISTA[i])
                    contador2++;
            }
            if(contador2>0){
                encontrar = false;
                console.log("hay una posicion repetida3");
                insertSattolo(array, temporal, array.length-1);    
            }
            contador2=0;
        }

        if(generos_A_P_T.modo_revolver == 2){
            for(let i=0; i<LISTA.length; i++){
            	if(tempLista[i] == LISTA[i])
                	contador2++;
            }
            if(contador2>1){
                encontrar = false;
                console.log("hay una posicion repetida1");
                insertSattolo(array, temporal, escalar);
            }
            contador2 = 0;
        }

        if(generos_A_P_T.modo_revolver == 1){
            let t_lista=tempLista;
            removeItemFromArr(t_lista, LISTA[0]);

            for(i=1,j=0; i<LISTA.length; i++,j++){
                if(t_lista[j] == LISTA[i]){
                    contador2++;
                }
            }

            if(contador2>0){
                encontrar = false;
                console.log("hay una posicion repetida2");
                insertSattolo(array,temporal,0);
                
                for(let i=0; i<array.length; i++){
                    if(generos_A_P_T.seleccion_pasado.indexOf(array[i]) == -1){
                        let faltante = array[i];
                        if(faltante == LISTA[array.length-1]){
                            insertSattolo(array, temporal, 0);
                        }
                    }
                }
            }
            contador2=0;
        }

        if(generos_A_P_T.modo_revolver == 4 && algoritmo == 1){
            let t_lista = tempLista;
            for(i=0; i<LISTA.length; i++){
                if(t_lista[i] == LISTA[i]){
                    contador2++;
                }
            }

            if(contador2>0){
                encontrar = false;
                console.log("hay una posicion repetida4");
                if(algoritmo == 0){
                    LISTA = shuffleArray(array);
                }else if(algoritmo==1){
                    LISTA = sattolo(array);
                }
            }
            contador2=0;
        }
	}

	// verificación
    console.log("Array Nuevo=["+LISTA+"]");
    document.getElementById("txtArray").innerHTML = "["+LISTA+"]";
    escalar=-1;
}


// MEZCLA LOS GENEROS SEGUN EL RANDOM ESTABLECIDO
function mezclar_generos_RANDOM() {
	let temp_genero = new Array(),
		primero_ultimo = 0,
		ultimo, primero;

	if(cont_A_P == -1) {
		temp_genero = mostrar_Ausente_Presente();
		GENEROS.forEach((item, i) => item.posicion_Perm = i); // coloca las posiciones iniciales de la lista generos
	}else {
		temp_genero = generos_A_P;
	}

	if(temp_genero.length >= 3) {
		ultimo = temp_genero[temp_genero.length -1].Name;
		mostrarGenerosConsola(temp_genero, 0);
		incrementar_A_P();

		let encontrar = false,
			detener = 0;

		while(encontrar == false){
			encontrar = true;
			generos_A_P=mostrar_Ausente_Presente();
                
            if(RANDOM == 0){ // SIN RANDOM
                generos_A_P = burbuja_generos(generos_A_P);
            }
            if(RANDOM == 1){ // MEZCLA GENEROS CON FISHER YATES
                generos_A_P = shuffleArray(generos_A_P);
            }
            else if(RANDOM == 2){ // MEZCLA GENEROS CON SATTOLO
                generos_A_P = sattolo(generos_A_P);
            }
            else if(RANDOM == 3){ // MEZCLA GENEROS CON PERMUTACION
                permutaciones(generos_A_P);
            }

            // INSTRUCCIONES PARA SEPARAR GENEROS IGUALES
            if(SEPARAR_GENERO == 2){ 
                if(RANDOM == 1 || RANDOM == 2){
                    if(generos_A_P<=4){
                        alert("Debe poseer 5 o más géneros para separar iguales generos");
                    }
                    else{
                        let cont_rep = 0;
                        for(let i=0,j=1; i<generos_A_P.length-1; i++,j++){
                            if(generos_A_P[i].Name == generos_A_P[j].Name){
                                cont_rep++;
                            }
                        }
                        if(cont_rep > 0){
                            encontrar=false;      
                        }
                        cont_rep = 0;
                    }
                }
                else if(RANDOM == 3) alert("El modo permutacion no separa los generos");
            }

            primero=generos_A_P[0].Name;
            
            if(iniciar_R_2!= false){
                if(ultimo == primero ){
                    encontrar = false;
                    primero_ultimo++;

                    mostrarGenerosConsola(generos_A_P,1);
                    console.log("Ultimo genero es igual al primero:"+detener);

                    if(RANDOM!=3){
                        generos_A_P=temp_genero;
                    }
                    if(RANDOM==3){
                        permutacion.push(permutado_pasado.pop());
                    }
                    
                }
            }else iniciar_R_2=true;
		}

		if(primero_ultimo == 0) mostrarGenerosConsola(generos_A_P, 1);
	}else{
		if(RANDOM == 1) alert("Debe poseer 3 o más generos para mezclar con fisher yates");
        else if(RANDOM ==2) alert("Debe poseer 3 o más generos para mezclar con sattolo");
        else if(RANDOM ==3) alert("Debe poseer 3 o más generos para mezclar con permutacion");
	}
}


// CREA LA LISTA DE REPRODUCCION
function reproducir(generos_AP) {
    let i=0, j=0, k=0,
    	lista_reproducir=new Array(),
    	pos_A_P=0;

    lista_reproduccion_generos(); // asigna a reproduccion los tracks de generos a reproducir

    for(i=0; i<generos_AP.length; i++) {
        limpiar_reproduccion_comerciales(); // limpia la variable reproduccion de los comerciales
        lista_reproduccion_comerciales(); // asigna a reproduccion los tracks de comerciales a reproducir

        // cargando los comerciales de los generos
        if(comerciales_generos == false){
            comerciales_generos = true;
        }
        else{
        	COMERCIALES.forEach((item) => {
        		//console.log("foreach => ", item, i, generos_AP[i])
        		if(item.ID == generos_AP[i].ID_comerciales_generos){
                    let inicio = parseInt(item.contador);
                    item.reproduccion = new Array();

                    for(let p=0; p<parseInt(item.Ntracks); p++){
                        item.reproduccion[p] = item.lista[inicio++];
                    }

                    item.contador = parseInt(inicio);
                    if(item.lista.length == inicio){
                        mezclar(item.lista, i, item);
                        item.contador = 0; // ultima posicion de la lista comerciales de reproduccion
                        inicio = 0;    
                    }

                    for(let z=0; z<item.reproduccion.length; z++){
                        lista_reproducir[k++] = item.reproduccion[z];
                    }
                }
        	});
        }

        // cargando generos
        for(j=0; j<generos_AP[i].reproduccion.length; j++){
            lista_reproducir[k++] = generos_AP[i].reproduccion[j];
        }
        
        COMERCIALES.forEach((item, i) => {
        	if(parseInt(item.tipo) == 3){
                for(let z=0; z<item.reproduccion.length; z++){
                    lista_reproducir[k++] = item.reproduccion[z];
                }
            }

            if(parseInt(item.tipo) == 1){
                for(let z=0; z<item.reproduccion.length; z++){
                    lista_reproducir[k++] = item.reproduccion[z];
                }
            }
        });
        
    }
    limpiar_reproduccion_generos();
    LISTA = lista_reproducir;    
}


// INSERTAR UN ITEM A LA LISTA
function insertSattolo(array, temporal, escalar){
    removeItemFromArr(array,temporal);
    LISTA=sattolo(array);
    LISTA.splice(escalar, 0, temporal);
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

// METODO DE ORDENACION
function burbuja_generos(miArray){
    miArray.sort((a, b) => { 
    	if(a.ID > b.ID) return 1; 
    	if(a.ID < b.ID) return -1; 
    	return 0; 
    });

    return miArray;
}


// REVOLVER CON FISHER YATES
function shuffleArray(array) {
    for (let i = array.length - 1; i > 0; i--) {
        let j = Math.floor(Math.random() * (i + 1));
        [array[i], array[j]] = [array[j], array[i]];
    }
    return array;
}


// REMOVER UN ITEM DE LA LISTA
function removeItemFromArr (arr, item) {
    let i = arr.indexOf(item);
    if ( i !== -1 ) {
        arr.splice(i, 1);
    }
}


 // OBTENER LA RUTA ABSOLUTA
function getAbsolutePath() {
    let loc = window.location;
    let pathName = loc.pathname.substring(0, loc.pathname.lastIndexOf('/') + 1);
    return loc.href.substring(0, loc.href.length - ((loc.pathname + loc.search + loc.hash).length - pathName.length));
}    

// RECORRIDO DE AUSENTE PRESENTE EN LOS GENEROS
function incrementar_A_P(){
    cont_A_P++;
    if(cont_A_P == GENEROS[0].AUSENTE_PRESENTE.length)
        cont_A_P=0;
}

// LIMPIA LA LISTA DE REPRODUCCION DE LOS GENEROS
function limpiar_reproduccion_generos(){
    GENEROS.forEach(item => item.reproduccion = new Array());
}

// LIMPIA LA LISTA DE REPRODUCCION DE LOS COMERCIALES
function limpiar_reproduccion_comerciales(){
    COMERCIALES.forEach(item => item.reproduccion = new Array());
} 


// GUARDA TODAS LAS VARIABLES EN GENEROS_A_P.JSON, COMERCIALES.JSON, GENERAL.JSON
function guardar_variables_lista() {
	let tmp_lista_reproducciones = new Array();
	let lista_variables = {
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
	}

	let _lista_variables = JSON.stringify(lista_variables),
		_lista_generos_AP = JSON.stringify(generos_A_P),
		_lista_comerciales = JSON.stringify(COMERCIALES);

	let data = new FormData();
	data.append('variables', _lista_variables);
	data.append('current_lista', current_lista);
	data.append('generos_A_P', _lista_generos_AP);
	data.append('comerciales', _lista_comerciales);

	fetch('php/guardar_variables.php', { method: 'POST', body: data })
		.then(res => res.text())
		.then(data => data)
		.catch(error => console.error("Error fetch into guardar_variables_lista: ", error));
}

function getRandomInt(min, max) {
    return Math.floor(Math.random() * (max - min)) + min;
}


function seleccionar_algoritmo_revolver(p_eliminar) {
	let arreglo = [1, 2, 3, 4, 5, 6, 7, 8],
		porcentaje = parseFloat(p_eliminar),
		n_eliminar = parseInt(arreglo.length * porcentaje / 100),
		eliminado_sattolo = new Array(),
		eliminado_fisher = new Array(),
		elegido = 0,
		total_sattolo = 0,
		total_fisher = 0;

	// ELECCION PARA FISHER YATES
    while(1){
        elegido=getRandomInt(0,8);
        if(eliminado_fisher.indexOf(elegido) == -1){
            eliminado_fisher.push(elegido);                        
        }
        if(eliminado_fisher.length == n_eliminar) break;
    }

    // ELECCION PARA SATTOLO
    while(1){
        elegido=getRandomInt(0,8);
        if(eliminado_sattolo.indexOf(elegido) == -1){
            eliminado_sattolo.push(elegido);
        }
        if(eliminado_sattolo.length == n_eliminar) break;
    }

    // ELIMINAR LOS ESCOGIDOS DE FISHER DEL ARREGLO
    let tmp_fisher = Array.from(arreglo);

    for(let i=0; i<n_eliminar; i++){
        removeItemFromArr(tmp_fisher, arreglo[eliminado_fisher[i]]);
    }
    console.log('Restantes Fisher:');
    console.log(tmp_fisher);

    // ELIMINAR LOS ESCOGIDOS DE SATTOLO DEL ARREGLO
    let tmp_sattolo = Array.from(arreglo);
    
    for(let i=0;i<n_eliminar;i++){
        removeItemFromArr(tmp_sattolo, arreglo[eliminado_sattolo[i]]);
    }
    console.log('Restantes Sattolo:');
    console.log(tmp_sattolo);
    
    
    tmp_fisher.forEach((item, i) => {    
        total_fisher += parseInt(tmp_fisher[i]);
        total_sattolo += parseInt(tmp_sattolo[i]);
    });

    if(total_fisher==total_sattolo) {
        console.log('La suma de fisher y sattollo son iguales');
        seleccionar_algoritmo_revolver(p_eliminar);
    } else {
        console.log('Total Fisher:'+total_fisher+',Total Sattolo:'+total_sattolo);
    }

    return (total_fisher > total_sattolo) ? 0 : 1;
}


// MUESTRA POR CONSOLA EL GENERO ANTES Y NUEVO LUEGO DE REVOLVER LA LISTA
function mostrarGenerosConsola(array, bandera){
    let cadena_temp="";
    if(bandera == 0){
        cadena_temp="Generos Antes=[";
    }
    else{
        cadena_temp="Generos Nuevo=[";
    }

    cadena_temp += array.join() + "]";
    console.log(cadena_temp);
}


// CONTROLA LA PERMUTACION
function permutaciones(array){
    let tam_array=array.length,
    	cadena="",
    	fisher_elegido="";

    if(activar_permutacion==false){
        for(let i=0;i<tam_array;i++){
            cadena+=generos_A_P[i].posicion_Perm;
        }
        permuta("", cadena);
        activar_permutacion = true;
    }

    if(permutado_pasado.length==conta){
        permutacion=permutado_pasado;
        permutado_pasado= [];
    }        
    
    if(permutado_pasado.length==0 && permutacion.length==conta){
        let factorial=1;            
        for(let i=1; i<=generos_A_P.length; i++) factorial = factorial * i;

        permutacion = shuffleArray(permutacion); // fisher yates a los elementos permutados

        factorial=1;
        for(let i=1;i<=generos_A_P.length;i++) factorial=factorial*i;

        let reacomodar=false;
        while(reacomodar==false){
            reacomodar=true;
            let cont_perm=0;
            for(var j=0;j<factorial;j++){    
                for(var i=1+j;i<factorial;i++){ 
                    if(permutacion[j].slice(-1) != permutacion[i].slice(0,1)){
                        var temp=permutacion[j+1];
                        permutacion[j+1]=permutacion[i];
                        permutacion[i]=temp;
                        break;
                    }
                    if(i==factorial-1 && j==i-1 && permutacion[j].slice(-1)==permutacion[i].slice(0,1)){
                        console.log('ultimo iguales');

                        permutacion=shuffleArray(permutacion);
                        reacomodar=false;
                    }
                    cont_perm++;

                    if(cont_perm>factorial*factorial){
                        permutacion=shuffleArray(permutacion);
                        reacomodar=false;
                        //i=0;
                        j=factorial;
                        break;
                    }
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

        let tmp_permutacion=[];
        for(let i=0, j=factorial-1; j>=0; j--,i++){
            tmp_permutacion[i]=permutacion[j];
        }    
        
        permutacion=tmp_permutacion;
    }

    while(1){
        fisher_elegido=permutacion.pop();
        if(fisher_elegido!=undefined) break;
    }

    if(consultar_pasado(fisher_elegido)==true ){
        ordenar_permutacion(fisher_elegido);
        permutado_pasado.push(fisher_elegido);
    }    
    
    if(permutado_pasado.length==conta){
        console.log("Combinaciones ya realizadas");
        console.log(permutado_pasado);
    }
}


// CARGA EN VARIABLE LAS DIFERENTES COMBINACIONES DE LA PERMUTACION
function permuta (cad_I, cad_D){
    if (cad_D.length == 1){
        permutacion[conta++] = cad_I + cad_D;
        return;
    }

    for (var i =0; i < cad_D.length ; i++){
        permuta (cad_I + cad_D.charAt(i), cad_D.replace(cad_D.charAt(i),""));
    } 
    return;
}

// ORDENA LA PERMUTACION
function ordenar_permutacion(fisher){
    let temp_generos_A_P; 
    for(let i=0; i<generos_A_P.length; i++){
        for(let j=0; j<generos_A_P.length; j++){
            if(generos_A_P[j].posicion_Perm == fisher.charAt(i)){
                temp_generos_A_P = generos_A_P[i];
                generos_A_P[i] = generos_A_P[j];
                generos_A_P[j] = temp_generos_A_P;
            }
        }
    }
}

// CONSULTA SI LA COMBINACION DE GENEROS EXISTENTE EN PERMUTACION YA FUE SELECCIONADO
function consultar_pasado(fisher_elegido){
    let encontrar = true;
    for(let i=0; i<permutado_pasado.length;i++){
        if(permutado_pasado[i]==fisher_elegido){
            encontrar=false;
            break;
        }
    }
    return encontrar;
}


// AGREGA A GENEROS LOS TRACKS A LA LISTA DE REPRODUCCION
function lista_reproduccion_generos(){
    let i=0, j=0, k=0, inicio=0;

    // Añandiendo a lista de reproduccion los generos
    for(i=0;i<generos_A_P.length;i++){
        inicio = parseInt(generos_A_P[i].contador);

        j = inicio;
        if((inicio+parseInt(generos_A_P[i].Ntracks)) <= generos_A_P[i].lista.length) {
            for(j=inicio, k=0; j<parseInt(generos_A_P[i].Ntracks)+inicio; j++,k++){
                generos_A_P[i].reproduccion[k] = generos_A_P[i].lista[j];
            }
        // aqui va ultima posicion y audio    
        }else if(j < generos_A_P[i].lista.length){
            for(j=inicio,k=0; j<generos_A_P[i].lista.length; j++,k++){
                generos_A_P[i].reproduccion[k]=generos_A_P[i].lista[j];
            }

            if(k < parseInt(generos_A_P[i].Ntracks)){
                mezclar(generos_A_P[i].lista,i,generos_A_P[i]);
 
                let tope = parseInt(generos_A_P[i].Ntracks) - k;

                for(var w=0; w < tope; w++,k++){
                    generos_A_P[i].reproduccion[k] = generos_A_P[i].lista[w];
                }

                j = w;
            }
        }
                                                                    
        generos_A_P[i].contador=parseInt(j); // ultima posicion de la lista genero de reproduccion
        generos_A_P[i].ultima= GENEROS[i].lista[j]; // ultimo audio de la lista de reproduccion
        if(generos_A_P[i].lista.length==parseInt(j)){
            mezclar(generos_A_P[i].lista, i, generos_A_P[i]);
            generos_A_P[i].contador=0; // ultima posicion de la lista genero de reproduccion
            generos_A_P[i].ultima= "";
            j=0;
        }
    }
}


// AGREGAR A COMERCIALES LOS TRACKS A LA LISTA DE REPRODUCCION
function lista_reproduccion_comerciales(){
    // Añandiendo a lista de reproduccion los comerciales
    let i=0, j=0, k=0, inicio=0;

    for(i=0; i< COMERCIALES.length ; i++){
        inicio = parseInt(COMERCIALES[i].contador);

        if(parseInt(COMERCIALES[i].tipo) != 2){
            if((inicio+parseInt(COMERCIALES[i].Ntracks))<=COMERCIALES[i].lista.length){
                for(j=inicio,k=0; j<parseInt(COMERCIALES[i].Ntracks)+inicio; j++,k++){
                    COMERCIALES[i].reproduccion[k] = COMERCIALES[i].lista[j];
                }
            // aqui va ultima posicion y audio    
            }else if(j < COMERCIALES[i].lista.length){
                for(j=inicio,k=0; j<COMERCIALES[i].lista.length; j++,k++){
                    COMERCIALES[i].reproduccion[k] = COMERCIALES[i].lista[j];
                }
                if(k < parseInt(COMERCIALES[i].Ntracks)){
                    mezclar(COMERCIALES[i].lista, i, COMERCIALES[i]);
                    let tope = parseInt(COMERCIALES[i].Ntracks)-k;    
                    for(var w=0; w < tope; w++,k++){
                        COMERCIALES[i].reproduccion[k] = COMERCIALES[i].lista[w];
                    }
                    j=w;
                }
            }

            COMERCIALES[i].contador = j; // ultima posicion de la lista genero de reproduccion
            COMERCIALES[i].ultima = COMERCIALES[i].lista[j]; // ultimo audio de la lista de reproduccion
            if(COMERCIALES[i].lista.length == j){
                mezclar(COMERCIALES[i].lista, i, COMERCIALES[i]);
                COMERCIALES[i].contador = 0; // ultima posicion de la lista genero de reproduccion
                COMERCIALES[i].ultima = "";
                j=0;
            }
        }
    }
}


// MUESTRA POR LA PAGINA HTML LOS GENEROS ACTIVOS
function mostrarListaGeneros(generos_A_P){
    let cadena_genero= "",
    	pos_A_P = cont_A_P;

	try {
	    // bloque de genero
	    for(var i=0; i< generos_A_P.length ; i++){
	        cadena_genero += generos_A_P[i].Name+":[";
	        cadena_genero += generos_A_P[i].lista.join();
	        cadena_genero += "]<BR>";
	    }
	    document.getElementById("txtGenero").innerHTML = cadena_genero;
	}catch(error){
		console.log("Error into mostrarListaGeneros: ", error, "Recibido: ", generos_A_P);
	}
}

// MUESTRA POR LA PAGINA HTML LOS COMERCIALES
function mostrarListaComerciales(){
    let cadena_comercial= "";

	try{
	    for(var i=0; i< COMERCIALES.length ; i++){
	        cadena_comercial += "Comerciales:[";
	        cadena_comercial += COMERCIALES[i].lista.join();
	        cadena_comercial += "]<BR>";
	    }
	    document.getElementById("txtComercial").innerHTML = cadena_comercial;
    }catch(error){
		console.log("Error into mostrarListaComerciales: ", error, "Recibido: ", COMERCIALES);
	}
}

// MUESTRA POR LA PAGINA HTML LA LISTA DE REPRODUCCION
function mostrarListaAudio(){
    let tmp_lista='';
    try{
	    for(let i=0; i<LISTA.length; i++){
	        tmp_lista=tmp_lista+LISTA[i];
	        if(i!=LISTA.length-1){
	            tmp_lista=tmp_lista+',';
	        }
	        if((i+1)%3==0 && i!=0 && i!=LISTA.length-1){
	            tmp_lista=tmp_lista+'<br>';
	        }
	    }
	    document.getElementById("txtArray").innerHTML = "["+tmp_lista+"]";   
    }catch(error){
		console.error("Error into mostrarListaComerciales: ", error, "Recibido: ", LISTA);
	}     
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

// EVENTO PARA ACTUALIZACION DEL TRACK DE REPRODUCCION
player.addEventListener("timeupdate", function(){
    let curtime = parseInt(player.currentTime, 10);
    let percent = Math.round((curtime * 100) / duration);

    progress.style.width = percent + '%';
    handler.style.left = percent + '%';
    handler.innerHTML = percent + "<span style='color: "+color_signo+"'> %</span>";

    timesControlInfo.innerHTML = "<span id='timeProgress' style='color: "+color_tiempo+"; font-size:"+tactual+"px;'>" + hora(curtime)+ "</span> / <span id='timesActual' style='color: "+color_tiempo+"; font-size:"+tduracion+"px;'>"+hora(duration) + "</span> / <span style='color: "+color_guion+"'>-</span> <span id='timesRestante' style='color: "+color_tiempo+"; font-size:"+trestante+"px;'>"+horaRestar(duration, curtime)+"</span>";

    if(percent < 68) {
        handler.style.marginLeft = -percent+'px';
        porcencap = -percent+'px';
    }
    else{
        handler.style.marginLeft = '-67px';
    }

    current_times = curtime;    
    
    fetch('php/current.php?current_times='+current_times+'&current_track='+current_track+'&current_lista='+current_lista)
    	.then(res => res.text())
    	.then(data => {
    		if(data=='true'){
                player.pause();
                current_track=0;
                current_lista=0;

                setTimeout(function(){ obtener_lista(); }, 5000);
            }
    	})
    	.catch(error => console.log("Error fetch into 'player timeupdated': ", error));
});

// EVENTO QUE GUARDA LA DURACION DEL AUDIO
player.ondurationchange = () => {
    duration = player.duration;
}

// EVENTO PARA EL BOTON NEXT
next.addEventListener("click", siguiente, false);

// EVENTO PARA EL FIN DEL AUDIO
player.addEventListener("ended", function(){
    //verificar_cambios_configuracion();
    siguiente();    
});


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


// CARGA LOS AUDIOS CON EXTENSIONES LOG, TXT, INI, JSON, JS, PHP3, PH5, PHP7, PHTML
function cargar_audio(extension , ruta){
    var audio;
    fetch('php/val_audio.php?extension='+extension+'&ruta='+ruta)
    	.then(res => res.text())
    	.then(data => {
    		audio =  decodeURIComponent(data);

            player.src = audio;
            var playPromise = player.play();
             
              if (playPromise !== undefined) {
                playPromise.then(_ => {
                  // Automatic playback started!
                  // Show playing UI.
                  // We can now safely pause video...
                  player.play();
                })
                .catch(err => {
                  // Auto-play was prevented
                  // Show paused UI.
                  console.error("Error on play promise: ", err);
                });
              }
    	})
    	.catch(error => console.error("Error fetch into 'cargar_audio': ", error));
}