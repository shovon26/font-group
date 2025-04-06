<?php
require_once __DIR__ . '/includes/config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FontGroup System</title>
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/styles.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">
    <h1 class="mb-4">FontGroup System</h1>

    <div class="card-body">
        <form id="uploadForm" enctype="multipart/form-data" class="simple-upload">
            <div class="form-group">
                <input type="file" id="fontFile" name="font" accept=".ttf" class="form-control-file" required>
                <small class="form-text text-muted" style="display: flex; margin-top: 3px !important; align-items: flex-start">Only TTF File Allowed</small>
            </div>
            <button type="submit" class="btn btn-primary mt-2">Upload Font</button>
        </form>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <h5>Our Fonts</h5>
            <p class="mb-0">Browse a list of fonts to build your font group</p>
        </div>
        <div class="card-body">
            <div id="fontList">
                <p class="text-muted">Loading fonts...</p>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5>Create Font Group</h5>
            <p class="text-muted mb-0">You have to select at least two fonts</p>
        </div>
        <div class="card-body">
            <form id="groupForm">
                <div class="form-group">
                    <label for="groupName">Group Title</label>
                    <input type="text" class="form-control" id="groupName" required>
                </div>

                <div id="groupContainer">
                    <div class="font-row mb-3 row align-items-center">
                        <div class="col-md-4 mb-2 mb-md-0">
                            <input type="text" class="form-control font-title"
                                   placeholder="Font Name" required>
                        </div>
                        <div class="col-md-8">
                            <div class="input-group">
                                <select class="form-control font-select" required>
                                    <option value="">Select a Font</option>
                                </select>
                                <div class="input-group-append" style="margin-left: 5px !important;">
                                    <button class="btn btn-outline-danger remove-row" type="button">✗</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-3">
                    <button type="button" id="addRow" class="btn btn-secondary">Add Row</button>
                    <button type="submit" class="btn btn-primary">Create Group</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5>Font Groups</h5>
        </div>
        <div class="card-body">
            <table class="table" id="groupsTable">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Fonts</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="assets/js/jquery-3.7.1.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/mainScript.js"></script>
</body>
</html>