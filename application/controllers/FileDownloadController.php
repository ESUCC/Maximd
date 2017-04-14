<?php
class FileDownloadController extends Zend_Controller_Action
{

    public function pdfAction()
    {
        /**
         * TODO: CHECK ACCESS
         */
        // disable layout and view
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        $config = Zend_Registry::get('config');
        $path = realpath($config->archivePath);
        $folder1 = $this->getRequest()->getParam('folder1');
        $folder2 = $this->getRequest()->getParam('folder2');
        $file = $this->getRequest()->getParam('file');
        $prefix1 = substr($folder1, 0, 4);
        $prefix2 = substr($folder1, 4);
        $fullPath = $path . '/' . $prefix1 . '/' . $prefix2 . '/' . $folder1 . '/' . $folder2 . '/' . $file;

        if(!file_exists($fullPath)) {
            throw new Exception('File not found.');
        }

        $fd = fopen($fullPath, "r");

        if ($fd) {
            $fsize = filesize($fullPath);
            $path_parts = pathinfo($fullPath);
            $ext = strtolower($path_parts["extension"]);

            header("Content-type: application/pdf");
            header("Content-Disposition: attachment; filename=" . basename($fullPath));
            header("Content-length: $fsize");
            header("Cache-control: private");

            while (! feof($fd)) {
                $buffer = fread($fd, 2048);
                echo $buffer;
            }
            fclose($fd);
        }
    }
}

?>