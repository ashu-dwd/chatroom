<?php
$uploadDir = 'user-uploads/';
foreach ($_FILES['files']['name'] as $key => $name) {
    if ($_FILES['files']['error'][$key] === UPLOAD_ERR_OK) {
        $tmpName = $_FILES['files']['tmp_name'][$key];
        $filePath = $uploadDir . basename($name);
        move_uploaded_file($tmpName, $filePath);
    }
}
echo json_encode(['files' => $_FILES['files']['name']]);
?>

<form id="fileupload" action="" method="POST" enctype="multipart/form-data">
    <input type="file" name="files" id="file">
    <button type="submit" class="upload-btn" id="btn">Upload</button>
</form>
<script src="https://cdnjs.cloudflare.com/ajax/libs/blueimp-file-upload/10.31.0/js/jquery.fileupload.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
</script>
<script>
$(function() {
    $('#fileupload').fileupload({
        dataType: 'json',
        done: function(e, data) {
            $.each(data.result.files, function(index, file) {
                $('<p/>').text(file.name).appendTo(document.body);
            });
        },
        progressall: function(e, data) {
            var progress = parseInt(data.loaded / data.total * 100, 10);
            console.log(progress + '%');
        }
    });
});
</script>