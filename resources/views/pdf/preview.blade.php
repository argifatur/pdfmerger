<!DOCTYPE html>
<html>
<head>
    <title>Preview PDF</title>
</head>
<body>
    <h1>Preview PDF</h1>
    <iframe src="{{ $filePath }}" width="100%" height="600px"></iframe>
    <br>
    <a href="{{ $filePath }}" download>Download PDF</a>
</body>
</html>
