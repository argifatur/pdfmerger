<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.7.0/min/dropzone.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.css">

<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h4 class="mb-0">Drop Multiple Images</h4>
                </div>
                <div class="card-body">
                    <label>Drag and Drop Multiple Images (JPG, JPEG, PNG, .webp)</label>
                    <form action="{{route('proses-upload')}}" method="POST" enctype="multipart/form-data" class="dropzone" id="myDragAndDropUploader">
                        @csrf
                        <div id="sortableContainer" class="sortable-container"></div>
                        <button type="submit" class="btn btn-primary mt-3">Submit</button>
                    </form>
                    <h5 id="message"></h5>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.7.0/min/dropzone.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script type="text/javascript">
    var maxFilesizeVal = 12;
    var maxFilesVal = 10;

    Dropzone.options.myDragAndDropUploader = {
        url: '{{ route("proses-upload") }}',
        paramName: "file",
        maxFilesize: maxFilesizeVal, // MB
        maxFiles: maxFilesVal,
        resizeQuality: 1.0,
        acceptedFiles: ".pdf",
        addRemoveLinks: false,
        timeout: 60000,
        dictDefaultMessage: "Drop your files here or click to upload",
        dictFallbackMessage: "Your browser doesn't support drag and drop file uploads.",
        dictFileTooBig: "File is too big. Max filesize: " + maxFilesizeVal + "MB.",
        dictInvalidFileType: "Invalid file type. Only JPG, JPEG, PNG and GIF files are allowed.",
        dictMaxFilesExceeded: "You can only upload up to " + maxFilesVal + " files.",
        init: function() {
            var myDropzone = this;

            // Add uploaded files to sortable container
            this.on("addedfile", function(file) {
                var sortableContainer = document.getElementById('sortableContainer');
                var sortableItem = document.createElement('div');
                sortableItem.className = 'sortable-item position-relative';

                // Create remove button
                var removeButton = document.createElement('button');
                removeButton.className = 'btn btn-danger btn-sm position-absolute top-0 end-0';
                removeButton.innerHTML = '&times;';
                removeButton.onclick = function(e) {
                    e.preventDefault(); // Prevent form submission
                    myDropzone.removeFile(file);
                    sortableItem.remove(); // Remove the item from the DOM
                };

                sortableItem.appendChild(file.previewElement);
                sortableItem.appendChild(removeButton);
                sortableContainer.appendChild(sortableItem);
            });

            // Initialize Sortable
            new Sortable(document.getElementById('sortableContainer'), {
                animation: 150,
                ghostClass: 'sortable-ghost',
                onEnd: function(evt) {
                    console.log('Sorted:', evt.oldIndex, '->', evt.newIndex);
                }
            });
        },
        sending: function(file, xhr, formData) {
            $('#message').text('Image Uploading...');
        },
        success: function(file, response) {
            $('#message').text(response.success);
            console.log(response.success);
            console.log(response);
        },
        error: function(file, response) {
            $('#message').text('Something Went Wrong! ' + response);
            console.log(response);
            return false;
        }
    };
</script>

<style>
    .sortable-container {
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
        gap: 10px;
    }

    .sortable-item {
        margin-bottom: 10px;
        display: inline-block;
        position: relative;
    }

    .sortable-ghost {
        opacity: 0.4;
    }
</style>
