<?php
/**
 */
class Zend_View_Helper_JobQueue extends Zend_View_Helper_Abstract
{
    /**
     * 
     * @return string
     */
    public function jobQueue()
    {
        
        /*
         * build the script call and button to be
        */
        $this->view->placeholder('jobQueue')->captureStart();
        ?>
		<script type="text/javascript">
			var oTable;
			$(document).ready(function() {
				console.debug('jobQueue');
				try{
					// datatable - get list of queues
					oTable = $('#jobqueuedatagrid').dataTable({
						"bJQueryUI":true,
						"sPaginationType":"full_numbers",
				        "bProcessing": true,
				        "bServerSide": true,
				        "sAjaxSource": '/archive/get-job-queue',
				        "fnServerData": function( sUrl, aoData, fnCallback ) {
				            $.ajax( {
				                "url": sUrl,
				                "data": aoData,
				                "success": fnCallback,
				                "dataType": "json",
				                "cache": false
				            } );
				        }
					});
				} catch(err) {
					console.debug('JAVASCRIPT ERROR: There has been an error trying to initialize the JobQueue datatable.');
				}
			});
		</script>
        
        <table id="jobqueuedatagrid" class="display">
		<thead>
			<tr>
				<th>Queue Name</th>
				<th>Status</th>
			</tr>
		</thead>
		<tbody>
		</tbody>
		</table>
    	<?php
    	$this->view->placeholder('jobQueue')->captureEnd();
        
    	return $this->view->placeholder('jobQueue');
    }
    /**
     * Sets the view field
     * @param $view Zend_View_Interface
     */
    public function setView (Zend_View_Interface $view)
    {
    	$this->view = $view;
    }
    
}
