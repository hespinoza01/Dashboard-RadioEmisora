//var audioListaGenero=lista[current_track];
//alert(audioListaGenero);


var datavolumen, datarbtimes, datarbdecay, datarbmix, datappdfeedback, dataqdlow, dataqdhigh, datasppan;
var datafrequency, datalpfpeak, datadgain, datarmspeed, datarmdistortion, datacssthreshold, datacssknee;
var datacssattack, datacssrelease, datacssratio;
var dataefectoreverb, dataefectopingPongDelay, dataefectoquadrafuzz, dataefectostereoPanner,
	dataefectolowPassFilter, dataefectodistortion, dataefectoringModulator, dataefectocompressor;

// Dolby format detection - taken from https://s3-us-west-1.amazonaws.com/dolbydeveloper/1.1.0/js/dolby.min.js
var Dolby=Dolby||{};!function(){"use strict";Dolby.supportDDPlus=!1;var e=new Audio;""!=e.canPlayType('audio/mp4;codecs="ec-3"')&&(-1==navigator.userAgent.indexOf("CPU iPhone OS 9_3")&&-1==navigator.userAgent.indexOf("CPU OS 9_3")||-1==navigator.userAgent.indexOf("Safari")||-1==navigator.userAgent.indexOf("Version/9")||(Dolby.supportDDPlus=!0),-1!=navigator.userAgent.indexOf("Mac OS X 10_1")&&-1!=navigator.userAgent.indexOf("Safari")&&-1!=navigator.userAgent.indexOf("Version/9")&&(Dolby.supportDDPlus=!0),-1!=navigator.userAgent.indexOf("Edge")&&(Dolby.supportDDPlus=!0),-1!=navigator.userAgent.indexOf("Windows Phone 10")&&(Dolby.supportDDPlus=!1)),Dolby.checkDDPlus=function(){return Dolby.supportDDPlus}}();
var dolbySupported = Dolby.checkDDPlus();

var req = new XMLHttpRequest();
	req.open('GET', "valores.php?validator="+2, false);
	req.onload = onLoad;
	req.send(null); 
	
	function onLoad(e) {
		if(e.target.readyState == 4 && e.target.status == 200) {
			var responjson =  JSON.parse(e.target.responseText);
			
			datavolumen = responjson.volumen;
			document.getElementById("volume-cavaquinho").value = responjson.volumen;
			document.getElementById("html-volume").innerHTML = responjson.volumen;

			datarbtimes=parseFloat(responjson.rbtimes);
			document.getElementById("reverb-time").value = responjson.rbtimes;
			document.getElementById("html-time").innerHTML = responjson.rbtimes;

			datarbdecay=parseFloat(responjson.rbdecay);
			document.getElementById("reverb-decay").value = responjson.rbdecay;
			document.getElementById("html-decay").innerHTML = responjson.rbdecay;

			datarbmix=parseFloat(responjson.rbmix);
			document.getElementById("reverb-mix").value = responjson.rbmix;
			document.getElementById("html-mix").innerHTML = responjson.rbmix;
			
			datappdfeedback = parseFloat(responjson.ppdfeedback);
			document.getElementById("ping-pong-delay-feedback").value = responjson.ppdfeedback;
			document.getElementById("html-feedbck").innerHTML = responjson.ppdfeedback;
			
			dataqdlow= parseFloat(responjson.qdlow);
			document.getElementById("quadrafuzz-low").value = responjson.qdlow;
			document.getElementById("html-qdlow").innerHTML = responjson.qdlow;

			dataqdhigh=parseFloat(responjson.qdhigh);
			document.getElementById("quadrafuzz-high").value = responjson.qdhigh;
			document.getElementById("html-qdhigh").innerHTML = responjson.qdhigh;

			datasppan=parseFloat(responjson.sppan);
			document.getElementById("stereo-panner-pan").value = responjson.sppan;
			document.getElementById("html-pan").innerHTML = responjson.sppan;
			
			datafrequency = parseInt(responjson.lpffrequency);
			document.getElementById("low-pass-filter-frequency").value = responjson.lpffrequency;
			document.getElementById("html-frequency").innerHTML = responjson.lpffrequency;
			
			datalpfpeak=parseFloat(responjson.lpfpeak);
			document.getElementById("low-pass-filter-peak").value = responjson.lpfpeak;
			document.getElementById("html-lpfpeak").innerHTML = responjson.lpfpeak;
			
			datadgain=parseFloat(responjson.dgain);
			document.getElementById("distortion-gain").value = responjson.dgain;
			document.getElementById("html-gain").innerHTML = responjson.dgain;
			
			datarmspeed=parseInt(responjson.rmspeed);
			document.getElementById("ringmod-speed").value = responjson.rmspeed;
			document.getElementById("html-speed").innerHTML = responjson.rmspeed;
			
			datarmdistortion=parseFloat(responjson.rmdistortion);
			document.getElementById("ringmod-distortion").value = responjson.rmdistortion;
			document.getElementById("html-distortion").innerHTML = responjson.rmdistortion;
			
			datacssthreshold=parseInt(responjson.cssthreshold);
			document.getElementById("compressor-threshold").value = responjson.cssthreshold;
			document.getElementById("html-threshold").innerHTML = responjson.cssthreshold;
			
			datacssknee=parseInt(responjson.cssknee);
			document.getElementById("compressor-knee").value = responjson.cssknee;
			document.getElementById("html-knee").innerHTML = responjson.cssknee;
			
			datacssattack=parseFloat(responjson.cssattack);
			document.getElementById("compressor-attack").value = responjson.cssattack;
			document.getElementById("html-attack").innerHTML = responjson.cssattack;
			
			datacssrelease=parseFloat(responjson.cssrelease);
			document.getElementById("compressor-release").value = responjson.cssrelease;
			document.getElementById("html-release").innerHTML = responjson.cssrelease;
			
			datacssratio=parseInt(responjson.cssratio);
			document.getElementById("compressor-ratio").value = responjson.cssratio;
			document.getElementById("html-ratio").innerHTML = responjson.cssratio;

			// ** CheckBox de efectos
			dataefectoreverb=responjson.efectoreverb;
			dataefectopingPongDelay=responjson.efectopingPongDelay;
			dataefectoquadrafuzz=responjson.efectoquadrafuzz;
			dataefectostereoPanner=responjson.efectostereoPanner;
			dataefectolowPassFilter=responjson.efectolowPassFilter;
			dataefectodistortion=responjson.efectodistortion;
			dataefectoringModulator=responjson.efectoringModulator;
			dataefectocompressor=responjson.efectocompressor;
		}
	}

// Efectos de Pizzicato
var pingPongDelay = new Pizzicato.Effects.PingPongDelay({
	feedback: datappdfeedback
});
var compressor = new Pizzicato.Effects.Compressor({
	threshold: datacssthreshold,
	knee: datacssknee,
	attack: datacssattack,
	release: datacssrelease,
	ratio: datacssratio
});
var lowPassFilter = new Pizzicato.Effects.LowPassFilter({
	frequency: datafrequency,
	peak: datalpfpeak
});
var distortion = new Pizzicato.Effects.Distortion({
	gain: datadgain
});
var quadrafuzz = new Pizzicato.Effects.Quadrafuzz({
	lowGain: dataqdlow,
	highGain: dataqdhigh
});
var stereoPanner = new Pizzicato.Effects.StereoPanner({
	pan: datasppan
});
var reverb = new Pizzicato.Effects.Reverb({
	time: datarbtimes,
	decay: datarbdecay,
	mix: datarbmix
});
var ringModulator = new Pizzicato.Effects.RingModulator({
	speed: datarmspeed,
	distortion: datarmdistortion
});


// Sounds

var cavaquinho = new Pz.Sound({ 
	source: 'file', 
	options: { 
		path: './music/005.mp3', 
		loop: true 
	}
}, function() { 
	// cavaquinho.addEffect(reverb);
	// cavaquinho.addEffect(pingPongDelay); 
	// cavaquinho.addEffect(quadrafuzz);
	// cavaquinho.addEffect(stereoPanner);
	// cavaquinho.addEffect(lowPassFilter);
});


if(dataefectoreverb == "true") {
	cavaquinho.removeEffect(reverb);
	document.getElementById("check-time").checked = true;
	document.getElementById("reverb-time").removeAttribute('disabled');
	document.getElementById("reverb-decay").removeAttribute('disabled');
	document.getElementById("reverb-mix").removeAttribute('disabled');
	cavaquinho.addEffect(reverb);
}
if(dataefectopingPongDelay == "true") {
	cavaquinho.removeEffect(pingPongDelay);
	document.getElementById("check-feedback").checked = true;
	document.getElementById("ping-pong-delay-feedback").removeAttribute('disabled');
	cavaquinho.addEffect(pingPongDelay);
}
if(dataefectoquadrafuzz == "true"){
	cavaquinho.removeEffect(quadrafuzz);
	document.getElementById("check-low").checked = true;
	document.getElementById("quadrafuzz-low").removeAttribute('disabled');
	document.getElementById("quadrafuzz-high").removeAttribute('disabled');
	cavaquinho.addEffect(quadrafuzz);
}				
if(dataefectostereoPanner == "true"){
	cavaquinho.removeEffect(stereoPanner);
	document.getElementById("check-pan").checked = true;
	document.getElementById("stereo-panner-pan").removeAttribute('disabled');
	cavaquinho.addEffect(stereoPanner);
}				
if(dataefectolowPassFilter == "true"){
	cavaquinho.removeEffect(lowPassFilter);
	document.getElementById("check-frequency").checked= true;
	document.getElementById("low-pass-filter-frequency").removeAttribute('disabled');
	document.getElementById("low-pass-filter-peak").removeAttribute('disabled');
	cavaquinho.addEffect(lowPassFilter);
}	
if(dataefectodistortion == "true"){
	cavaquinho.removeEffect(distortion);
	document.getElementById("check-gain").checked = true;
	document.getElementById("distortion-gain").removeAttribute('disabled');
	cavaquinho.addEffect(distortion);
}	
if(dataefectoringModulator == "true"){
	cavaquinho.removeEffect(ringModulator);
	document.getElementById("check-ring").checked = true;
	document.getElementById("ringmod-speed").removeAttribute('disabled');
	document.getElementById("ringmod-distortion").removeAttribute('disabled');
	cavaquinho.addEffect(ringModulator);
}
if(dataefectocompressor == "true"){
	cavaquinho.removeEffect(compressor);
	document.getElementById("check-compressor").checked = true;
	document.getElementById("compressor-threshold").removeAttribute('disabled');
	document.getElementById("compressor-knee").removeAttribute('disabled');
	document.getElementById("compressor-attack").removeAttribute('disabled');
	document.getElementById("compressor-release").removeAttribute('disabled');
	document.getElementById("compressor-ratio").removeAttribute('disabled');
	cavaquinho.addEffect(compressor);
}
				


// CheckBox -- Activar - Desactivar

function CheckReverd () {
	if (document.getElementById("check-time").checked == true) {
		cavaquinho.addEffect(reverb);
		document.getElementById("reverb-time").removeAttribute('disabled');
		document.getElementById("reverb-decay").removeAttribute('disabled');
		document.getElementById("reverb-mix").removeAttribute('disabled');
	}else {
		cavaquinho.removeEffect(reverb);
		document.getElementById("reverb-time").setAttribute('disabled', '');
		document.getElementById("reverb-decay").setAttribute('disabled', '');
		document.getElementById("reverb-mix").setAttribute('disabled', '');	
	}
}
function CheckPingpong () {
	if (document.getElementById("check-feedback").checked == true) {
		cavaquinho.addEffect(pingPongDelay);
		document.getElementById("ping-pong-delay-feedback").removeAttribute('disabled');
	}else {
		cavaquinho.removeEffect(pingPongDelay);
		document.getElementById("ping-pong-delay-feedback").setAttribute('disabled', '');	
	}
}
function CheckQuadrafuzz () {
	if (document.getElementById("check-low").checked == true) {
		cavaquinho.addEffect(quadrafuzz);
		document.getElementById("quadrafuzz-low").removeAttribute('disabled');
		document.getElementById("quadrafuzz-high").removeAttribute('disabled');
	}else {
		cavaquinho.removeEffect(quadrafuzz);
		document.getElementById("quadrafuzz-low").setAttribute('disabled', '');
		document.getElementById("quadrafuzz-high").setAttribute('disabled', '');
	}
}
function CheckPanner () {
	if (document.getElementById("check-pan").checked == true) {
		cavaquinho.addEffect(stereoPanner);
		document.getElementById("stereo-panner-pan").removeAttribute('disabled');
	}else {
		cavaquinho.removeEffect(stereoPanner);
		document.getElementById("stereo-panner-pan").setAttribute('disabled', '');	
	}
}
function LowPass () {
	if (document.getElementById("check-frequency").checked == true) {
		cavaquinho.addEffect(lowPassFilter);
		document.getElementById("low-pass-filter-frequency").removeAttribute('disabled');
		document.getElementById("low-pass-filter-peak").removeAttribute('disabled');
	}else {
		cavaquinho.removeEffect(lowPassFilter);
		document.getElementById("low-pass-filter-frequency").setAttribute('disabled', '');
		document.getElementById("low-pass-filter-peak").setAttribute('disabled', '');
	}
}
function CheckGain () {
	if (document.getElementById("check-gain").checked == true) {
		cavaquinho.addEffect(distortion);
		document.getElementById("distortion-gain").removeAttribute('disabled');
	}else {
		cavaquinho.removeEffect(distortion);
		document.getElementById("distortion-gain").setAttribute('disabled', '');	
	}
}
function CheckRingModulator () {
	if (document.getElementById("check-ring").checked == true) {
		cavaquinho.addEffect(ringModulator);
		document.getElementById("ringmod-speed").removeAttribute('disabled');
		document.getElementById("ringmod-distortion").removeAttribute('disabled');
	}else {
		cavaquinho.removeEffect(ringModulator);
		document.getElementById("ringmod-speed").setAttribute('disabled', '');
		document.getElementById("ringmod-distortion").setAttribute('disabled', '');
	}
}
function CheckCompressor () {
	if (document.getElementById("check-compressor").checked == true) {
		cavaquinho.addEffect(compressor);
		document.getElementById("compressor-threshold").removeAttribute('disabled');
		document.getElementById("compressor-knee").removeAttribute('disabled');
		document.getElementById("compressor-attack").removeAttribute('disabled');
		document.getElementById("compressor-release").removeAttribute('disabled');
		document.getElementById("compressor-ratio").removeAttribute('disabled');
	}else {
		cavaquinho.removeEffect(compressor);
		document.getElementById("compressor-threshold").setAttribute('disabled', '');
		document.getElementById("compressor-knee").setAttribute('disabled', '');
		document.getElementById("compressor-attack").setAttribute('disabled', '');
		document.getElementById("compressor-release").setAttribute('disabled', '');
		document.getElementById("compressor-ratio").setAttribute('disabled', '');
	}
}

var segments = [
	
	{
		audio: cavaquinho,
		playButton: document.getElementById('play-cavaquinho'),
		stopButton: document.getElementById('stop-cavaquinho'),
		volumeSlider: document.getElementById('volume-cavaquinho'),
		effects: [
			{
				instance: reverb,
				parameters: {
					time: document.getElementById('reverb-time'),
					decay: document.getElementById('reverb-decay'),
					mix: document.getElementById('reverb-mix'),
				}
			},
			{
				instance: pingPongDelay,
				parameters: {
					feedback: document.getElementById('ping-pong-delay-feedback'),
				}
			},
			{
				instance: quadrafuzz,
				parameters: {
					lowGain: document.getElementById('quadrafuzz-low'),
					highGain: document.getElementById('quadrafuzz-high'),
				}
			},
			{
				instance: stereoPanner,
				parameters: {
					pan: document.getElementById('stereo-panner-pan')
				}
			},
			{
				instance: lowPassFilter,
				parameters: {
					frequency: document.getElementById('low-pass-filter-frequency'),
					peak: document.getElementById('low-pass-filter-peak')
				}
			},
			{
				instance: distortion,
				parameters: {
					gain: document.getElementById('distortion-gain')
				}
			},
			{
				instance: ringModulator,
				parameters: {
					speed: document.getElementById('ringmod-speed'),
					distortion: document.getElementById('ringmod-distortion')
				}
			},
			{
				instance: compressor,
				parameters: {
					threshold: document.getElementById('compressor-threshold'),
					knee: document.getElementById('compressor-knee'),
					attack: document.getElementById('compressor-attack'),
					release: document.getElementById('compressor-release'),
					ratio: document.getElementById('compressor-ratio')
				}
			}

		]
	}
]

for (var i = 0; i < segments.length; i++) {
	(function(segment) {

		segment.audio.on('play', function() {
			segment.playButton.classList.add('pause');
		});

		segment.audio.on('stop', function() {
			segment.playButton.classList.remove('pause');
		});

		segment.audio.on('pause', function() {
			segment.playButton.classList.remove('pause');
		});

		segment.playButton.addEventListener('click', function(e) {
			if (segment.playButton.classList.contains('pause'))
				segment.audio.pause();
			else
				segment.audio.play();
		});

		segment.stopButton.addEventListener('click', function(e) {
			segment.audio.stop();
		});

		segment.volumeSlider.addEventListener('input', function(e) {
			var volumeDisplay = segment.volumeSlider.parentNode.getElementsByClassName('slider-value')[0];
			volumeDisplay.innerHTML = segment.audio.volume = e.target.valueAsNumber;
		});

		if (segment.releaseSlider) {
			segment.releaseSlider.addEventListener('input', function(e) {
				var releaseDisplay = segment.releaseSlider.parentNode.getElementsByClassName('slider-value')[0];
				releaseDisplay.innerHTML = segment.audio.release = e.target.valueAsNumber;
			});
		}

		if (segment.attackSlider) {
			segment.attackSlider.addEventListener('input', function(e) {
				var attackDisplay = segment.attackSlider.parentNode.getElementsByClassName('slider-value')[0];
				attackDisplay.innerHTML = segment.audio.attack = e.target.valueAsNumber;
			});
		}

		if (!segment.effects || !segment.effects.length)
			return;

		for (var i = 0; i < segment.effects.length; i++) {
			var effect = segment.effects[i];

			for (var key in effect.parameters) {
				(function(key, slider, instance){

					var display = slider.parentNode.getElementsByClassName('slider-value')[0];

					slider.addEventListener('input', function(e) {
						display.innerHTML = instance[key] = e.target.valueAsNumber;
					});

				})(key, effect.parameters[key], effect.instance);	
			}
		}

	})(segments[i]);
}

document.getElementById("guardarjson").addEventListener('click', function () {
	var volumen = document.getElementById("volume-cavaquinho").value;
	var rbtimes = document.getElementById("reverb-time").value;
	var rbdecay = document.getElementById("reverb-decay").value;
	var rbmix = document.getElementById("reverb-mix").value;
	var ppdfeedback = document.getElementById("ping-pong-delay-feedback").value;
	var qdlow = document.getElementById("quadrafuzz-low").value;
	var qdhigh = document.getElementById("quadrafuzz-high").value;
	var sppan = document.getElementById("stereo-panner-pan").value;
	var lpffrequency = document.getElementById("low-pass-filter-frequency").value;
	var lpfpeak = document.getElementById("low-pass-filter-peak").value;
	var dgain = document.getElementById("distortion-gain").value;
	var rmspeed = document.getElementById("ringmod-speed").value;
	var rmdistortion = document.getElementById("ringmod-distortion").value;
	var cssthreshold = document.getElementById("compressor-threshold").value;
	var cssknee = document.getElementById("compressor-knee").value;
	var cssattack = document.getElementById("compressor-attack").value;
	var cssrelease = document.getElementById("compressor-release").value;
	var cssratio = document.getElementById("compressor-ratio").value;
	var checkreverb = document.getElementById("check-time").checked;
	var checkpingPongDelay = document.getElementById("check-feedback").checked;
	var checkquadrafuzz = document.getElementById("check-low").checked;
	var checkstereoPanner = document.getElementById("check-pan").checked;
	var checklowPassFilter = document.getElementById("check-frequency").checked;
	var checkdistortion = document.getElementById("check-gain").checked;
	var checkringModulator = document.getElementById("check-ring").checked;
	var checkcompressor = document.getElementById("check-compressor").checked;

	var xhttp = new XMLHttpRequest();
	xhttp.open("GET", "valores.php?validator="+1+"&volumen=" + volumen+"&rbtimes="+rbtimes+"&rbdecay="+rbdecay+"&rbmix="+rbmix+
	"&ppdfeedback="+ppdfeedback+"&qdlow="+qdlow+"&qdhigh="+qdhigh+"&sppan="+sppan+"&lpffrequency="+lpffrequency+
	"&lpfpeak="+lpfpeak+"&dgain="+dgain+"&rmspeed="+rmspeed+"&rmdistortion="+rmdistortion+
	"&cssthreshold="+cssthreshold+"&cssknee="+cssknee+"&cssattack="+cssattack+"&cssrelease="+cssrelease+"&cssratio="+cssratio+
	"&efectoreverb="+checkreverb+"&efectopingPongDelay="+checkpingPongDelay+"&efectoquadrafuzz="+checkquadrafuzz+"&efectostereoPanner="+checkstereoPanner+
	"&efectolowPassFilter="+checklowPassFilter+"&efectodistortion="+checkdistortion+"&efectoringModulator="+checkringModulator+"&efectocompressor="+checkcompressor, true);
	xhttp.send(); 

	document.getElementById("label-message").removeAttribute("class", "labelmessageclose");
	document.getElementById("label-message").setAttribute("class", "labelmessageopen");

	setTimeout(function(){ 
		document.getElementById("label-message").removeAttribute("class", "labelmessageopen");
		document.getElementById("label-message").setAttribute("class", "labelmessageclose");
	}, 3000);

});