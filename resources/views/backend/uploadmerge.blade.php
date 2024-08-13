<!DOCTYPE html>
<html>
<head>
    <title>Laravel File Upload</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>
    <style>
        .dz-preview {
            cursor: move;
        }
    </style>
</head>
<body>
    <div class="container">
        <h3 class="jumbotron">Laravel File Upload</h3>
        <form method="POST" action="{{ route('upload') }}" enctype="multipart/form-data" class="dropzone" id="file-dropzone">
            @csrf
        </form>
        <button type="button" id="submit-all">Submit</button>
    </div>

    <script>
        Dropzone.autoDiscover = false;

        var myDropzone = new Dropzone("#file-dropzone", {
            url: "{{ route('upload') }}",
            autoProcessQueue: false,
            addRemoveLinks: true,
            parallelUploads: 10,
            uploadMultiple: true,
        });

        document.getElementById("submit-all").addEventListener("click", function() {
            myDropzone.processQueue(); // Process the files in the dropzone
        });

        var sortable = new Sortable(myDropzone.element.querySelector('.dz-preview-container'), {
            animation: 150,
            draggable: ".dz-preview",
            onEnd: function (/**Event*/evt) {
                var items = evt.from.children;
                for (var i = 0; i < items.length; i++) {
                    items[i].querySelector('.dz-filename span').textContent = (i + 1) + ". " + items[i].querySelector('.dz-filename span').textContent.replace(/^\d+\.\s*/, '');
                }
            }
        });

        myDropzone.on("addedfile", function(file) {
            sortable.options.draggable = ".dz-preview";
        });
    </script>
</body>
</html>
