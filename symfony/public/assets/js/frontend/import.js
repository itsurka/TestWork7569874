console.log('import.js')

require('../../css/frontend.css')
require('../../bootstrap-4.5.2/dist/css/bootstrap.css')
// require('../../lib/filepond/filepond.css')
// FilePond = require('../../lib/filepond/filepond.min')
// FilePondPluginImagePreview = require('../../lib/filepond/filepond-plugin-image-preview.min')
// require('../../lib/filepond/jquery-filepond/filepond.jquery')
require('../../lib/dropzone/min/dropzone.min.css')
Dropzone = require('../../lib/dropzone/min/dropzone.min.js')
Dropzone.autoDiscover = false;
require('./main')
