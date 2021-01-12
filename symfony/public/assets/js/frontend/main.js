console.log('main.js')

$(document).ready(function () {

    var carForm = $('form[name="car"]');
    var carBrand = carForm.find('select[name="car[brand]"]');
    var carBrandModel = carForm.find('select[name="car[brand_model]"]');
    carBrand.change(onCarBrandChange);
    carBrand.change();

    function onCarBrandChange(event) {
        let defaultLabel = carBrandModel.find('option').eq(0).text();
        carBrandModel.empty();
        carBrandModel.append($("<option />").val('').text(defaultLabel));

        let brandId = $(this).val();
        if (!brandId) {
            return;
        }
        let url = window.api.urls.cars.brandModels.replace('%s', brandId);
        $.get(url, function (models) {
            for (var i in models) {
                let model = models[i];
                carBrandModel.append(
                    $("<option></option>").attr("value", model.id).text(model.title)
                );
            }
        });
    }

    $('#my-awesome-dropzone').dropzone({
        url: '/api/files/cars',
        // uploadMultiple: true,
        // parallelUploads: 5,
        // maxFiles: 5,
        // maxFilesize: 3,
        acceptedFiles: 'image/*',
        addRemoveLinks: true,
        success: function (file, response) {
            console.log('success', file, response);
            var imgName = response;
            file.previewElement.classList.add("dz-success");

            var $preview = $(file.previewElement);
            $preview.attr('data-file_id', response.file_id);

            var $carFiles = $('.car_files');
            var $file = $('<input />')
                .attr('type', 'text')
                .attr('name', 'car[file_id]')
                .attr('value', response.file_id)
                .attr('id', 'car_file_id_' + response.file_id);
            $carFiles.append($file);
        },
        error: function (file, response) {
            console.log('error', file, response, file.previewElement);
            file.previewElement.classList.add("dz-error");
            $(file.previewElement).find('.dz-error-message span').eq(0).text(response.errors.join("\n"));
        },
        removedfile: function (file) {
            console.log('removedfile', file);
            $('.car_files #car_file_id_' + $(file.previewTemplate).attr('data-file_id')).remove();
            $(file.previewTemplate).remove();
        },
    });
});
