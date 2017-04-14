<?php
/**
 * Helper for displaying a subform 
 * 
 * @uses      Zend_View_Helper_Abstract
 * @package   SRS
 * @author    Jesse LaVere <mdanahy@esucc.org> 
 * @version   $Id: $
 */
class Zend_View_Helper_FileUploader extends Zend_View_Helper_Abstract
{
    /**
     * @var string
     */
    protected $_retString;

    /**
     * build bar of links to form options
     * 
     * @return string
     */
    public function fileUploader()
    {
    	
    	$this->_retString =<<<EOJ
<link rel="stylesheet" href="/js/jquery-ui/css/custom-theme/jquery-ui-1.8.16.custom.css" id="theme">
<link rel="stylesheet" href="/js/jquery_addons/jquery.fileupload-ui.css">
    	
<div id="fileupload">
    <div id="fileUploadPsudoForm">
        <div class="fileupload-buttonbar">
            <label class="fileinput-button">
                <span>Add files...</span>
                <input type="file" name="files[]" multiple>
            </label>
            <button type="submit" class="start">Start upload</button>
            <button type="reset" class="cancel">Cancel upload</button>
            <button type="button" class="delete">Delete files</button>
        </div>
    </div>
    <div class="fileupload-content">
        <table class="files"></table>
        <div class="fileupload-progressbar"></div>
    </div>
</div>
<script id="template-upload" type="text/x-jquery-tmpl">
    <tr class="template-upload{{if error}} ui-state-error{{/if}}">
        <td class="preview"></td>
        <td class="name">{{if name}}\${name}{{else}}Untitled{{/if}}</td>
        <td class="size">\${sizef}</td>
        {{if error}}
            <td class="error" colspan="2">Error:
                {{if error === 'maxFileSize'}}File is too big
                {{else error === 'minFileSize'}}File is too small
                {{else error === 'acceptFileTypes'}}Filetype not allowed
                {{else error === 'maxNumberOfFiles'}}Max number of files exceeded
                {{else}}\${error}
                {{/if}}
            </td>
        {{else}}
            <td class="progress"><div></div></td>
            <td class="start"><button>Start</button></td>
        {{/if}}
        <td class="cancel"><button>Cancel</button></td>
    </tr>
</script>
<script id="template-download" type="text/x-jquery-tmpl">
    <tr class="template-download{{if error}} ui-state-error{{/if}}">
        {{if error}}
            <td></td>
            <td class="name">\${name}</td>
            <td class="size">\${sizef}</td>
            <td class="error" colspan="2">Error:
                {{if error === 1}}File exceeds upload_max_filesize (php.ini directive)
                {{else error === 2}}File exceeds MAX_FILE_SIZE (HTML form directive)
                {{else error === 3}}File was only partially uploaded
                {{else error === 4}}No File was uploaded
                {{else error === 5}}Missing a temporary folder
                {{else error === 6}}Failed to write file to disk
                {{else error === 7}}File upload stopped by extension
                {{else error === 'maxFileSize'}}File is too big
                {{else error === 'minFileSize'}}File is too small
                {{else error === 'acceptFileTypes'}}Filetype not allowed
                {{else error === 'maxNumberOfFiles'}}Max number of files exceeded
                {{else error === 'uploadedBytes'}}Uploaded bytes exceed file size
                {{else error === 'emptyResult'}}Empty file upload result
                {{else}}\${error}
                {{/if}}
            </td>
        {{else}}
            <td class="preview">
                {{if thumbnail_url}}
                    <a href="\${url}" target="_blank"><img src="\${thumbnail_url}"></a>
                {{/if}}
            </td>
            <td class="name">
                <a href="\${url}"{{if thumbnail_url}} target="_blank"{{/if}}>\${name}</a>
            </td>
            <td class="size">\${sizef}</td>
            <td colspan="2"></td>
        {{/if}}
        <td class="delete">
            <button data-type="\${delete_type}" data-url="\${delete_url}">Delete</button>
        </td>
    </tr>
</script>
<script src="/js/jquery_addons/jquery.ui.widget.js"></script>
<script src="/js/jquery_addons/jquery.tmpl.min.js"></script>
<script src="/js/jquery_addons/jquery.iframe-transport.js"></script>
<script src="/js/jquery_addons/jquery.fileupload.js"></script>
<script src="/js/jquery_addons/jquery.fileupload-ui.js"></script>
<script src="/js/jquery_addons/jquery-init-fileupload.js"></script>
    	    	
EOJ;
    	return $this->_retString;
    }


}
