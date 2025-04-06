$(document).ready(function() {
    const fontManager = {
        loadFonts: function() {
            $.get('ajax/get_fonts.php', function(data) {
                $('#fontList').empty();

                let tableHTML = `
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>FONT NAME</th>
                                                    <th>PREVIEW</th>
                                                    <th>ACTION</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                        `;

                data.forEach(font => {
                    const previewStyle = `style="font-family: '${font.name.replace(/'/g, "\\'")}'"`;

                    tableHTML += `
                                <tr>
                                    <td>${font.name}</td>
                                    <td ${previewStyle}>Example Style</td>
                                    <td><button class="btn btn-sm btn-danger delete-font" data-id="${font.id}">Delete</button></td>
                                </tr>
                                `;

                    $('.font-select').append(`<option value="${font.id}">${font.name}</option>`);
                });

                tableHTML += `</tbody></table>`;
                $('#fontList').html(tableHTML);
            });
        },

        loadGroups: function() {
            $.get('ajax/get_groups.php', function(groups) {
                $('#groupsTable tbody').empty();

                groups.forEach(group => {
                    const fontNames = group.fonts.map(f => f.name).join(', ');
                    $('#groupsTable tbody').append(`
                        <tr data-id="${group.id}">
                            <td>${group.name}</td>
                            <td>${fontNames}</td>
                            <td>
                                <button class="btn btn-sm btn-danger delete-group">Delete</button>
                            </td>
                        </tr>
                    `);
                });
            });
        },

        init: function() {
            this.loadFonts();
            this.loadGroups();
            this.loadFontOptions();

            $('#uploadForm').on('submit', this.handleUpload.bind(this));
            $('#addRow').on('click', this.addFontRow.bind(this));
            $('#groupForm').on('submit', this.handleGroupCreate.bind(this));
            $(document).on('click', '.delete-font', this.handleFontDelete.bind(this));
            $(document).on('click', '.delete-group', this.handleGroupDelete.bind(this));
            $(document).on('click', '.remove-row', this.handleRemoveRow.bind(this));
        },

        handleUpload: function(e) {
            e.preventDefault();

            const fileInput = $('#fontFile')[0];
            if (!fileInput.files.length) {
                alert('Please select a TTF file');
                return;
            }

            const file = fileInput.files[0];
            if (!file.name.toLowerCase().endsWith('.ttf')) {
                alert('Only TTF files are allowed');
                return;
            }

            const fontName = file.name.replace(/\.ttf$/i, '');

            const formData = new FormData();
            formData.append('name', fontName);
            formData.append('font', file);

            $.ajax({
                url: 'ajax/upload_font.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: (response) => {
                    if (response.success) {
                        alert('Font uploaded successfully!');
                        this.loadFonts();
                        $('#uploadForm')[0].reset();
                    } else {
                        alert('Error: ' + response.error);
                    }
                },
                error: () => {
                    alert('Upload failed. Please try again.');
                }
            });
        },

        addFontRow: function() {
            const fontOptionsHTML = $('.font-select:first').find('option')
                .not(':first')
                .get()
                .map(option => option.outerHTML)
                .join('');

            const newRow = `
                            <div class="font-row mb-3 row align-items-center">
                                <div class="col-md-4 mb-2 mb-md-0">
                                    <input type="text" class="form-control font-title" 
                                           placeholder="Font Name" required>
                                </div>
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <select class="form-control font-select" required>
                                            <option value="">Select a Font</option>
                                            ${fontOptionsHTML}
                                        </select>
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-danger remove-row" type="button">✗</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
            $('#groupContainer').append(newRow);
        },

        handleGroupCreate: function(e) {
            e.preventDefault();

            const groupName = $('#groupName').val();
            const fonts = [];

            $('.font-row').each(function() {
                const fontName = $(this).find('.font-title').val().trim();
                const fontId = $(this).find('.font-select').val();

                if (!fontName) {
                    alert('Please enter a name for each font');
                    return false;
                }

                if (!fontId) {
                    alert('Please select a font for each row');
                    return false;
                }

                fonts.push({
                    name: fontName,
                    id: fontId
                });
            });

            if (fonts.length < 2) {
                alert('Please select at least two fonts');
                return;
            }

            $.ajax({
                url: 'ajax/create_group.php',
                type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({
                    name: groupName,
                    fonts: fonts
                }),
                success: (response) => {
                    if (response.success) {
                        alert('Group created successfully!');
                        this.resetGroupForm();
                        this.loadGroups();
                    } else {
                        alert('Error: ' + response.error);
                    }
                }
            });
        },

        handleGroupDelete: function(e) {
            if (!confirm('Are you sure you want to delete this group?')) return;

            const groupId = $(e.target).closest('tr').data('id');

            $.post('ajax/delete_group.php', { id: groupId }, (response) => {
                if (response.success) {
                    this.loadGroups();
                } else {
                    alert('Error: ' + response.error);
                }
            });
        },
        handleFontDelete: function(e) {
            if (!confirm('Are you sure you want to delete this font?')) return;

            const fontId = $(e.target).data('id');

            $.post('ajax/delete_font.php', { id: fontId }, (response) => {
                if (response.success) {
                    this.loadFonts();
                    this.loadGroups();
                } else {
                    alert('Error: ' + response.error);
                }
            });
        },
        handleRemoveRow: function(e) {
            if ($('.font-row').length > 1) {
                $(e.target).closest('.font-row').remove();
            } else {
                alert('At least one font selection is required');
            }
        },
        resetGroupForm: function() {
            $('#groupForm')[0].reset();
            $('#groupContainer').html(`
                                <div class="font-row mb-3 row align-items-center">
                                    <div class="col-md-4 mb-2 mb-md-0">
                                        <input type="text" class="form-control font-title" 
                                               placeholder="Enter font name" required>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="input-group">
                                            <select class="form-control font-select" required>
                                                <option value="">Select a Font</option>
                                            </select>
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-danger remove-row" type="button">✗</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `);
            this.loadFontOptions();
        },
        loadFontOptions: function() {
            $.get('ajax/get_fonts.php', function(fonts) {
                $('.font-select').each(function() {
                    $(this).find('option:not(:first)').remove();
                });

                fonts.forEach(font => {
                    $('.font-select').append(
                        $('<option>', {
                            value: font.id,
                            text: font.name
                        })
                    );
                });
            }).fail(function() {
                alert('Failed to load fonts');
            });
        },
    };

    fontManager.init();

    $(document).on('click', '.remove-row', function() {
        if ($('.form-row').length > 1) {
            $(this).closest('.form-row').remove();
        } else {
            alert('At least one font selection is required');
        }
    });

});