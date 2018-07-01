<?php
/**
 * Default Search Form
 * @author sbennett
 *
 */
class Form_DefaultSearch extends Zend_Form 
{
	protected $_searchFields;
	protected $_searchFormats;
	protected $_searchColumns;
	
	public function __construct($searchFormats, $searchColumns, $searchFields) {
		$this->setSearchFields($searchFields);
		$this->setSearchFormats($searchFormats);
		$this->setSearchColumns($searchColumns);
		Form_DefaultSearch::init();
	}
	
	/**
	 * Setup the form fields.
	 * @param mixed $searchFormats
	 * @param mixed $searchColumns
	 * @param mixed $searchFields
	 */
	public function init()
	{
		$this->type_id = new App_Form_Element_Hidden('type_id');
		
		$this->addSearchField('searchFieldTemplate0', 'searchFieldTemplate');
		$this->getElement('searchFieldTemplate0')->setAttrib('class', 'searchTemplate');
		$this->addSearchValue('searchValueTemplate0', 'searchValueTemplate');
		$this->getElement('searchValueTemplate0')->setAttrib('class', 'searchTemplate');

		$this->format = new App_Form_Element_SearchSelect(
				'format',
				array(
						'label' => 'Format:',
						'multiOptions' => $this->_searchFormats
				)
		);

		$this->maxRecs = new App_Form_Element_SearchSelect(
				'maxRecs',
				array(
						'label' => 'Records Per Page:',
						'multiOptions' => array(
								'5' => '5',
								'10' => '10',
								'15' => '15',
								'25' => '25',
								'50' => '50',
								'75' => '75',
								'100' => '100',
						)
				)
		);
		$this->maxRecs->setValue('25');

		for ($i=0;$i<=5;$i++) {
			$field = 'formatColumn'.$i;
			$this->$field = new App_Form_Element_SearchSelect(
					$field,
					array(
							'label' => "Column " . ($i+1) . ":",
							'multiOptions' => array(
									'' => '-- Select Column --'
							)
					)
			);
			$this->$field->addMultiOptions($this->_searchColumns);
		}

		$this->setDecorators(array(array('ViewScript',
				array('viewScript' =>
						'search/default-search-form.phtml'))));
	}
	
	/**
	 * Add active option
	 */
	public function addActiveOption() {
		$this->searchStatus = new App_Form_Element_SearchSelect('searchStatus', array('Label' => 'Status'));
		$this->searchStatus->addMultiOptions(array('Active' => 'Active', 'Inactive' => 'Inactive', 'All' => 'Both'));
	}

	/**
	 * Add a new search field
	 * @param string $fieldName
	 * @param string $belongsTo
	 */
	public function addSearchField($fieldName, $belongsTo)
	{
		$this->$fieldName = new App_Form_Element_SearchSelect($fieldName);
		$this->$fieldName->addMultiOptions(array(
				'' => '-- Select Search Field --'
		));
		$this->$fieldName->addMultiOptions(
			$this->_searchFields
		)->setBelongsTo($belongsTo);

	}
	
	/**
	 * Set the column values for format
	 * @param mixed $searchFormatColumns
	 */
	public function defaultFormatColumns($searchFormatColumns) {
		if (!empty($searchFormatColumns)) {
			for ($i=0;$i<count($searchFormatColumns);$i++) {
				$field = 'formatColumn'.$i;
				$this->$field->setValue($searchFormatColumns[$field]);
			}
		}
	}

	/**
	 * Set the value for Search Field
	 * @param string $fieldName
	 * @param string $belongsTo
	 */
	public function addSearchValue($fieldName, $belongsTo)
	{
		$this->$fieldName = new App_Form_Element_SearchText($fieldName);
		$this->$fieldName->setBelongsTo($belongsTo);
	}
	
	/**
	 * Setter for Search Fields
	 * @param mixed $searchFields
	 */
	public function setSearchFields($searchFields) {
		$this->_searchFields = $searchFields;
	}
	
	/**
	 * Setter for Search Formats
	 * @param mixed $searchFormats
	 */
	public function setSearchFormats($searchFormats) {
		$this->_searchFormats = $searchFormats;
	}
	
	/**
	 * Setter for Search Columns
	 * @param mixed $searchColumns
	 */
	public function setSearchColumns($searchColumns) {
		$this->_searchColumns = $searchColumns;
	}
}