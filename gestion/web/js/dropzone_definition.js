function init_dropzone(endpoint) {

    var mprogress = new Mprogress();

    var previewNode = document.querySelector("#template");
    previewNode.id = "";
    var previewTemplate = previewNode.parentNode.innerHTML;
    previewNode.parentNode.removeChild(previewNode);

    var processing = false;


    var myDropzone = new Dropzone(document.body, {
        url: endpoint,
        paramName: "file", // The name that will be used to transfer the file
        maxFilesize: 5, // MB
        clickable: ".fileinput-button",
        autoProcessQueue: false,
        parallelUploads: 50,
        dictFileTooBig: 'Le fichier est trop lourd, veuillez rentrer des fichiers de moins de 5 Mo',
        previewTemplate: previewTemplate,
        previewsContainer: "#previews",
        accept: function (file, done) {
            var splitted = file.name.split('.');

            if (splitted[1] == 'stl' || splitted[1] == 'STL' || splitted[1] == 'pdf' || splitted[1] == 'PDF' || splitted[1] == 'png' || splitted[1] == 'PNG' || splitted[1] == 'JPEG' || splitted[1] == 'jpeg' || splitted[1] == 'JPG' || splitted[1] == 'jpg') {
                done();
            }
            else {
                done("Vous ne pouvez pas importer des fichiers de type " + splitted[1]);
            }
        }
    });

    // Update the total progress bar
    myDropzone.on("totaluploadprogress", function (progress) {
        mprogress.set(progress / 100);
        //document.querySelector("#total-progress .progress-bar").style.width = progress + "%";
    });

    myDropzone.on("processing", function () {
        //set autoProcessQueue to true, so every file gets uploaded
        document.querySelector('.whirly-loader').style.display = "block";
        mprogress.start();
        processing = true;
    });

    // Hide the total progress bar when nothing's uploading anymore
    myDropzone.on("queuecomplete", function (progress, a, b) {
        document.querySelector('.whirly-loader').style.display = "none";
        if (processing) {
            document.querySelector("#no-content").style.display = "block";
            var req = new XMLHttpRequest();
            req.open('GET', '/notify', true);
            req.send();
            myDropzone.removeAllFiles(true);
            mprogress.end();
            swal({
                title: "Bien joué !",
                text: "Vos fichiers ont bien été envoyé sur le serveur",
                timer: 2000,
                type: "success",
                showConfirmButton: false
            });
        }
        processing = false;
    });

    myDropzone.on('addedfile', function () {
        document.querySelector("#no-content").style.display = "none";
    });

    myDropzone.on('removedfile', function () {
        if (this.getQueuedFiles().length == 0) {
            document.querySelector("#no-content").style.display = "block";
        }
    });


    myDropzone.on("error", function (a, b) {
        console.log(a, b);
        var btest = b.search('mporter des fichiers');
        if (b != 'Le fichier est trop lourd, veuillez rentrer des fichiers de moins de 5 Mo' && btest == -1) {
            swal({
                title: "Oups !",
                text: "Une erreur est survenue l'ors de l'envoi des fichiers, veuillez réessayer.",
                type: "error"
            });
        }

    });

    myDropzone.on("sending", function (file, xhr, formData) {
        var delivery = file.previewElement.querySelector('#delivery').value;
        var deliverySplit = delivery.split('-');
        console.log(deliverySplit);

        // Will send the filesize along with the file as POST data.
        formData.append("filename", file.name);
        formData.append("quantite", file.previewElement.querySelector('#quantity').value.toString());
        formData.append("day", deliverySplit[0]);
        formData.append("method", deliverySplit[1]);
    });

    // Setup the buttons for all transfers
    // The "add files" button doesn't need to be setup because the config
    // `clickable` has already been specified.
    document.querySelector("#actions .start").onclick = function () {
        myDropzone.processQueue();
    };
    document.querySelector("#actions .cancel").onclick = function () {
        myDropzone.removeAllFiles(true);
        document.querySelector("#no-content").style.display = "block";
    };
}