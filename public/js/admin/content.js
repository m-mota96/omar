var myDropzone = '';
$('#upload').dropzone({
    url: $('#URL').val()+'uploadImagesIndex',
    method: 'post',
    paramName: 'files', // The name that will be used to transfer the file
    maxFilesize: 5, // MB
    uploadMultiple: true,
    createImageThumbnails: false,
    // thumbnailWidth: 400,
    // thumbnailMethod: 'contain',
    acceptedFiles: '.png',
    autoProcessQueue: true,
    dataType: 'json',
    accept: function(file, done) {
        $('#modalEditImage .dz-success-mark').hide();
        $('#modalEditImage .dz-error-mark').hide();
        $('#modalEditImage .text-upload').hide();
        done();
    },
    error: function(data, xhr) {
        console.log(data.size);
        if(data.size > 5120) {
            this.removeAllFiles();
            Swal.fire({
                title: 'Las imágenes deben pesar menos de 5MB cada una.',
                icon: 'error'
            });
        }
    },
    init: function() {
        myDropzone = this;
        this.on("sending", function(file, xhr, formData) {
            formData.append("_token", $("meta[name='csrf-token']").attr("content"));
            // formData.append("event_id", $("#eventId").val());
            // formData.append("type", "index");
        });
        this.on('success', function(file, response) {
            Swal.fire({
                position: 'bottom-end',
                icon: 'success',
                text: 'Imagenes guardadas exitosamente',
                showConfirmButton: false,
                timer: 1500
            });
            myDropzone.removeFile(file);
        });
    },
});