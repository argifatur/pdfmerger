<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Files</title>
    <link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet">
    <style>
        .filepond--root {
            width: 100%;
            max-width: 600px;
            margin: auto;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Upload Files</h1>

        @if(session('success'))
            <div>{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div>{{ session('error') }}</div>
        @endif

        <form action="{{ route('proses-merge-filepond') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="file" class="filepond" name="files[]" multiple data-max-file-size="3MB" data-max-files="5">
            <button type="submit">Upload and Merge</button>
        </form>
    </div>

    <script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>
    <script src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.js"></script>
    <script src="https://unpkg.com/filepond-plugin-file-encode/dist/filepond-plugin-file-encode.js"></script>
    <script src="https://unpkg.com/filepond/dist/filepond.js"></script>
    <script>
        FilePond.registerPlugin(
            FilePondPluginFileValidateType,
            FilePondPluginFileValidateSize,
            FilePondPluginFileEncode
        );

        FilePond.create(document.querySelector('.filepond'), {
            acceptedFileTypes: ['application/pdf'],
            maxFileSize: '3MB',
            maxFiles: 5,
            allowReorder: true,
            itemInsertLocation: 'after'
        });
    </script>
</body>
</html>
