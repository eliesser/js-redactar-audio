var afinn = $.extend(afinn_es, afinn_emoticon);

$(document).ready(function () {

	function blink(selector) {
		$(selector).animate({
			opacity: 0.4
		}, 50, "linear", function () {
			$(this).delay(400);
			$(this).animate({
				opacity: 1
			}, 50, function () {
				blink(this);
			});
			$(this).delay(250);
		});
	}

	/*
	blink('#titulo-principal');
	setTimeout(function() {
		$("#titulo-principal").finish();
		$("#titulo-principal").stop(true);
	}, 3000);

	setTimeout(function() {
		$('html, body').animate({scrollTop: '150px'}, 800);
	}, 1500 );
	*/

	// Cronometro para el contador de tiempo transcurrido 
	function init() {
		h = 0;
		m = 0;
		s = 0;
		//document.getElementById("hms").innerHTML="00:00:00";
		document.getElementById('time_trans').innerHTML = "00:00:00";
	}

	function cronometrar() {
		escribir();
		id = setInterval(escribir, 1000);
		document.querySelector(".start").removeEventListener("click", cronometrar);
	}

	function escribir() {
		var hAux, mAux, sAux;
		s++;
		if (s > 59) {
			m++;
			s = 0;
		}
		if (m > 59) {
			h++;
			m = 0;
		}
		if (h > 24) {
			h = 0;
		}

		if (s < 10) {
			sAux = "0" + s;
		} else {
			sAux = s;
		}
		if (m < 10) {
			mAux = "0" + m;
		} else {
			mAux = m;
		}
		if (h < 10) {
			hAux = "0" + h;
		} else {
			hAux = h;
		}

		//document.getElementById("hms").innerHTML = hAux + ":" + mAux + ":" + sAux; 
		document.getElementById('time_trans').innerHTML = hAux + ":" + mAux + ":" + sAux;
	}

	function parar() {
		clearInterval(id);
		document.querySelector(".start").addEventListener("click", cronometrar);

	}

	function reiniciar() {
		clearInterval(id);
		document.getElementById("hms").innerHTML = "00:00:00";
		h = 0;
		m = 0;
		s = 0;
		document.querySelector(".start").addEventListener("click", cronometrar);
	}


	//$("#div-procesamiento").css('display','block');
	//$('html, body').animate({scrollTop: '150px'}, 600);


	$("input[name='file']").on("change", function () {
		let archivo = $('#file').val();
		archivo = archivo.replace('C:\\fakepath\\', '');
		let nameonly = archivo.split('.');
		nameonly = nameonly[0];
		let tipo = (archivo.toLowerCase().indexOf('.mp3') != -1) ? 'audio/mp3' : 'audio/wav';
		let duracion;

		if ((archivo.toLowerCase().indexOf('.wav') != -1) || (archivo.toLowerCase().indexOf('.mp3') != -1)) {
			var formData = new FormData();
			var files = $('#file')[0].files[0];
			formData.append('file', files);
			//formData.append('nombre',nameonly+'.wav');

			$.ajax({
				url: "file-ajax.php",
				type: 'post',
				data: formData,
				contentType: false,
				processData: false,
				beforeSend: function () {
					$(".loader").css('display', 'block');
					$("#input-file").css('display', 'none');
					$('#titulo-principal').text('Subiendo archivo de audio al servidor por favor espere...');
				},
				success: function (response) {
					if (response != 0) {
						//alert(response);
						var nombre_archivo = response; // Nombre nuevo que viene del servidor
						var nombre_archivo2 = nombre_archivo.split('.'); // sin extension
						nombre_archivo2 = nombre_archivo2[0];

						if (response.toLowerCase().indexOf('audio') != -1) {
							var miAudio = document.getElementById("miAudio");
							miAudio.src = nombre_archivo;
							miAudio.onloadeddata = function () {
								duracion = miAudio.duration;
								duracion = duracion / 60;
								$("#metadatos").show("fast");
								$('#label4').html('Nombre: <b>' + archivo + '</b>');
								$('#label5').html('Tipo: <b>' + tipo + '</b>');
								$('#label6').html('Duración: <b>' + duracion.toFixed(2) + 'min</b>');
								$('#label7').html('Tasa de rateo: <b>' + 16000 + '</b>');
								//Iniciamos el cronometro
								init();
								cronometrar();
							};

							$("#file-upload-audio").css('display', 'none');
							$('#titulo-principal').text('Procesando archivo de audio ' + archivo + ' por favor espere...');
							$("#div-procesamiento").show("slow");
							$("#audio-original").show("slow");

							setTimeout(function () {
								$('#icono0').fadeIn().html('<i class="fa fa-check-square-o" aria-hidden="true" style="color:green;"></i>');
								$("#label0").css('opacity', '1');
							}, 300);

							setTimeout(function () {
								$('#icono1').fadeIn().html('<i class="fa fa-check-square-o" aria-hidden="true" style="color:green;"></i>');
								$("#label1").css('opacity', '1');
							}, 500);

							var formData2 = new FormData();
							//formData2.append('nombre',archivo);
							formData2.append('nombre', nombre_archivo);

							$.ajax({
								url: "convertidor_new.php",
								type: 'POST',
								data: formData2,
								contentType: false,
								processData: false,
								beforeSend: function () {
									blink('#label1');
								},
								success: function (response2) {
									if (response != 0) {
										$('#icono1').fadeIn().html('<i class="fa fa-check-square-o" aria-hidden="true" style="color:green;"></i>');
										$("#label1").css('opacity', '1');
										$("#label1").finish();
										$("#label1").stop(true);

										if (response2.toLowerCase().indexOf('1') != -1) {

											var formData3 = new FormData();
											formData3.append('nombre', nombre_archivo2 + '.wav');

											$('#titulo-principal').text('Procesando archivo de audio ' + nameonly + '.wav por favor espere...');

											$.ajax({
												url: "google-cloud-storage/upload_storage.php",
												type: 'POST',
												data: formData3,
												contentType: false,
												processData: false,
												beforeSend: function () {
													blink('#label2');
												},
												success: function (response3) {
													if (response3 != 0) {
														//alert(response3);	

														$.ajax({
															url: "google-api-speechtotext/index_async.php",
															//url: "google-api-speechtotext/index.php",
															type: 'POST',
															data: formData3,
															contentType: false,
															processData: false,
															success: function (response4) {
																if (response4 != 0) {
																	//alert(response4);

																	$('#icono2').fadeIn().html('<i class="fa fa-check-square-o" aria-hidden="true" style="color:green;"></i>');
																	$("#label2").css('opacity', '1');
																	$("#label2").finish();
																	$("#label2").stop(true);


																	$("#resultado2").html(response4);
																	$("#resultados-container").css('display', 'block');
																	blink('#label3');

																	//Analisis de sentimientos
																	setTimeout(function () {

																		$('#icono3').fadeIn().html('<i class="fa fa-check-square-o" aria-hidden="true" style="color:green;"></i>');
																		$("#label3").css('opacity', '1');
																		$("#label3").finish();
																		$("#label3").stop(true);

																		var sentiment_result = sentiment($('#resultado2').text());
																		var resultado = sentiment_result.opinion;
																		$('#veredicto').text('Análisis de sentimiento: Opinión ' + resultado);
																		$('#resultado3').text(JSON.stringify(sentiment_result, undefined, 2));
																		$("#analisis-opinion").css('display', 'block');
																		$('#texto-procesamiento').html('<h3 class="card-title">Proceso Finalizado</h3>');
																		$('#titulo-principal').hide("fast");
																		$('#texto-info').text('Tiempo total: ');

																		$('html, body').animate({
																			scrollTop: '450px'
																		}, 400);

																		parar(); //Detener cronometro

																	}, 500);

																} else {
																	alert('Error al conectarse a API GoogleSpeechToText');
																}
															},
															error: function () {
																alert("No se pudo conectar con API GoogleSpeechToText ");

																$('#icono0').fadeIn().html('<i class="fa fa-square-o" aria-hidden="true"></i>');
																$("#label0").css('opacity', '0.4');
																$('#icono1').fadeIn().html('<i class="fa fa-square-o" aria-hidden="true"></i>');
																$("#label1").css('opacity', '0.4');
																$('#icono2').fadeIn().html('<i class="fa fa-square-o" aria-hidden="true"></i>');
																$("#label2").css('opacity', '0.4');
																$('#icono3').fadeIn().html('<i class="fa fa-square-o" aria-hidden="true"></i>');
																$("#label3").css('opacity', '0.4');
																$("#div-procesamiento").hide("fast");
																$("#audio-original").hide("fast");

																$('#titulo-principal').text('Sube tu audio aqui');
																$("#file-upload-audio").css('display', 'block');

																$(".loader").css('display', 'none');
																$("#input-file").css('display', 'block');
																$('#file').val('');
															}
														});
														return false;

													} else {
														alert('Error al guardar en GoogleStorage');
													}
												},
												error: function () {
													alert("No se pudo conectar con GoogleStorage");
												}
											});
											return false;

										} else {
											alert('Error de conversión de audio');
										}

									} else {
										alert('Error en conversión de audio');
									}
								},
								error: function () {
									alert("No se pudo conectar con el Convertidor");
								}
							});
							return false;

						} else {
							alert('Error al subir archivo de audio');
						}

					} else {
						alert('Formato de audio incorrecto.');
					}
				},
				error: function () {
					alert("No se pudo conectar con el servidor UPLOAD");
					$(".loader").css('display', 'none');
					$("#input-file").css('display', 'block');
					$('#titulo-principal').text('Sube tu audio aquí');
					$('#file').val('');
				}
			});
			return false;

		} else {
			alert('Solo archivos de audio .mp3 o .wav');
			$('#file').val('');
		}
	});
});