<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.7.0/min/dropzone.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.css">
    <link rel="stylesheet" href="https://getbootstrap.com/docs/4.0/examples/sign-in/signin.css">
</head>

<body class="text-center">
    <form class="form-signin" method="POST" action="{{route('proses-pdf-to-text')}}" enctype="multipart/form-data">
        @csrf
        <h1 class="h3 mb-3 font-weight-normal">PDF To Text</h1>
        <div id="pdf-inputs" class="sortable-container mb-3">
            <div class="input-group mb-2 sortable-item">
                <input type="file" class="form-control" required accept=".pdf" name="pdf">
            </div>
        </div>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Submit</button>
    </form>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.7.0/min/dropzone.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

    <style>
        .sortable-container {
            display: flex;
            flex-direction: column;
        }

        .sortable-item {
            margin-bottom: 10px;
            display: flex;
            align-items: center;
        }

        .sortable-ghost {
            opacity: 0.4;
        }

        .btn-remove {
            margin-left: 10px;
        }
    </style>
</body>
</html>
