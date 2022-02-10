$(function () {
	// Create the QuaggaJS config object for the live stream
	var liveStreamConfig = {
		inputStream: {
			type: "LiveStream",
			constraints: {
				width: { min: 640 },
				height: { min: 480 },
				aspectRatio: { min: 1, max: 1 },
				facingMode: "environment" // or "user" for the front camera
			}
		},
		locator: {
			patchSize: "medium",
			halfSample: true
		},
		numOfWorkers: (navigator.hardwareConcurrency ? navigator.hardwareConcurrency : 4),
		decoder: {
			"readers": ['upc_reader', 'upc_e_reader', 'code_128_reader', 'ean_reader', 'ean_8_reader', 'code_39_reader', 'code_39_vin_reader', 'codabar_reader', 'i2of5_reader', '2of5_reader', 'code_93_reader'],
			multiple: false
		},
		locate: false
	};

	var resultCollector = Quagga.ResultCollector.create({
		capture: true, // keep track of the image producing this result
		capacity: 20,  // maximum number of results to store
		blacklist: [   // list containing codes which should not be recorded
			{ code: "3574660239843", format: "upc_e" }],
		filter: function (codeResult) {
			// only store results which match this constraint
			// returns true/false
			// e.g.: return codeResult.format === "ean_13";
			return true;
		}
	});

	$('#barcodeScanner').on('shown.bs.modal', function (e) {
		Quagga.init(
			liveStreamConfig,
			function (err) {
				if (err) {
					$('#barcodeScanner .modal-body .error').html('<div class="alert alert-danger"><strong><i class="fa fa-exclamation-triangle"></i> ' + err.name + '</strong>: ' + err.message + '</div>');
					Quagga.stop();
					return;
				}
				Quagga.registerResultCollector(resultCollector);
				Quagga.start();
			}
		);
	});

	// Make sure, QuaggaJS draws frames an lines around possible
	// barcodes on the live stream
	Quagga.onProcessed(function (result) {
		var drawingCtx = Quagga.canvas.ctx.overlay,
			drawingCanvas = Quagga.canvas.dom.overlay;

		if (result) {
			if (result.boxes) {
				drawingCtx.clearRect(0, 0, parseInt(drawingCanvas.getAttribute("width")), parseInt(drawingCanvas.getAttribute("height")));
				result.boxes.filter(function (box) {
					return box !== result.box;
				}).forEach(function (box) {
					Quagga.ImageDebug.drawPath(box, { x: 0, y: 1 }, drawingCtx, { color: "green", lineWidth: 2 });
				});
			}

			if (result.box) {
				Quagga.ImageDebug.drawPath(result.box, { x: 0, y: 1 }, drawingCtx, { color: "#00F", lineWidth: 2 });
			}

			if (result.codeResult && result.codeResult.code) {
				Quagga.ImageDebug.drawPath(result.line, { x: 'x', y: 'y' }, drawingCtx, { color: 'red', lineWidth: 3 });
			}
		}
	});
	// Once a barcode had been read successfully, stop quagga and
	// close the modal after a second to let the user notice where
	// the barcode had actually been found.
	Quagga.onDetected(function (result) {
		console.log(result);
		//if (result.codeResult.code) {
		//	$('#scanner_input').val(result.codeResult.code);
		//	Quagga.stop();
		//	setTimeout(function () { $('#barcodeScanner').modal('hide'); }, 1000);
		//}
		var countDecodedCodes = 0, err = 0;
		$.each(result.codeResult.decodedCodes, function (id, error) {
			if (error.error != undefined) {
				countDecodedCodes++;
				err += parseFloat(error.error);
			}
		});
		if (err / countDecodedCodes < 0.1) {
			if (result.codeResult.format == 'upc_e' || result.codeResult.format == 'upc_a') {
				if (result.codeResult.code) {
					$('#scanner_input').val(result.codeResult.code);
					Quagga.stop();
					setTimeout(function () { $('#barcodeScanner').modal('hide'); }, 1000);
				}
				else {
					alert("Unable to detect correct code. Please try again.");
					Quagga.stop();
					setTimeout(function () { $('#barcodeScanner').modal('hide'); }, 1000);
				}
			}
			else {
				document.getElementById('floatingInputPayment').value = result.codeResult.code;
				// Quagga.stop();
				// setTimeout(function () { $('#barcodeScanner').modal('hide'); }, 1000);
				codeOrder = result.codeResult.code.substring(1);
				window.location.replace("./PaymentPage.php?code_or=%23"+codeOrder);
			}
			console.log(resultCollector.getResults());
			var results = resultCollector.getResults();
			window.location.href = "./PaymentPage.php?code_or=" + esult.codeResult.code + "&format_code=" + result.codeResult.format;
			$ul = $("#result_strip");

			results.forEach(function (result) {
				var $li = $('<li><div class="thumbnail"><div class="imgWrapper"><img /></div><div class="caption"><h4 class="code"></h4></div></div></li>');

				$li.find("img").attr("src", result.frame);
				$li.find("h4.code").html(result.codeResult.code + " (" + result.codeResult.format + ")");
				$ul.prepend($li);
			});
		}

	});

	// Stop quagga in any case, when the modal is closed
	$('#barcodeScanner').on('hide.bs.modal', function () {
		if (Quagga) {
			Quagga.stop();
		}
	});

	$('#clear-page').click(function () {
		location.reload();
	});

	// Call Quagga.decodeSingle() for every file selected in the
	// file input

	//	$("#barcodeScanner input:file").on("change", function (e) {
	//		if (e.target.files && e.target.files.length) {
	//			Quagga.decodeSingle($.extend({}, fileConfig, { src: URL.createObjectURL(e.target.files[0]) }), function (result) { alert(result.codeResult.code); });
	//		}
	//	});
});