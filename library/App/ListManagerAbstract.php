<?php

class App_ListManagerAbstract
{
    public $form;
    public $message;
    public $data;

    public $childModelId;
    public $childModelName;
    public $childKeyName;
    public $childModelObj;
    public $childFindFunctionName = 'find';
    public $childRecord;
    public $childLabel;

    public $parentModelId;
    public $parentKeyName;
    public $parentModelName;
    public $parentLabel;
    public $parentModelObj;
    public $parentRecord;

    protected $debug = false;
    public $parentFindFunctionName = 'find';

    function viewEditAddSave($data, $isPost, $isXmlHttpRequest, $viewOnly = false, $add = false)
    {

        $this->data = $data;
        $this->isPost = $isPost;
        $this->isXmlHttpRequest = $isXmlHttpRequest;

        /**
         * try extracting the parent and child IDs from the post
         */
        $this->parentModelId = isset($this->data[$this->parentKeyName])?$this->data[$this->parentKeyName]:null;
        $this->childModelId = isset($this->data[$this->childKeyName])?$this->data[$this->childKeyName]:null;

        /**
         * prepare models
         */
        $this->childModelObj = new $this->childModelName();
        $this->parentModelObj = new $this->parentModelName();

        /**
         * fetch the child record
         */
        if(!is_null($this->childModelId) && ''!=$this->childModelId) {
            $this->childRecord = $this->childModelObj->{$this->childFindFunctionName}($this->childModelId)->current();
        }

        /**
         *
         * if child exists and we have no parent id,
         * try to get it from the child
         */

        if (!($add) && is_null($this->parentModelId)) {
            if (!$this->childRecord) {
                throw new Exception($this->childLabel.' not found (child).');
            }
            $this->parentModelId = $this->childRecord->{$this->parentKeyName};
        }

        /**
         * fetch the parent record
         */
        $this->parentRecord = $this->parentModelObj->{$this->parentFindFunctionName}($this->parentModelId)->current();
        if (is_null($this->parentRecord)) {
            throw new Exception($this->parentLabel.' not found');
        }

        /**
         * build the zend form
         */
        $this->form = new $this->formName();


        if ($add) {
            $this->preAdd();
        } elseif ($isPost && isset($this->data['submit'])) {
            $this->prePost();
        } else {
            $this->preRequest();
        }

        if ($viewOnly) {
            $this->form->removeElement('submit');
            foreach ($this->form->getElements() as $n => $e) {
                if ('cancel' != $this->form->getElement($n)->getName()) {
                    $this->form->getElement($n)->setAttrib('disabled', true);
                }
            }
            $this->form->getElement('cancel')->setLabel('Done');
        }

        /**
         * post
         */
        if ($isPost) {
            if (isset($this->data['submit'])) {
                if ($this->form->isValid($this->data)) {
                    // save
                    $data = $this->form->getValues();
                    unset($data[$this->childKeyName]);// don't save primary key

                    if ($add) {
                        $data[$this->parentKeyName] = $this->parentModelId;
                        $this->childModelId = $this->childModelObj->insert($data);
                    } else {
                        $where = $this->childModelObj->getAdapter()->quoteInto($this->childKeyName.'= ?', $this->childModelId);
                        $this->childModelObj->update($data, $where);
                    }
                    if ($isXmlHttpRequest) {
                        // ajax posted save
                        $this->message = $this->childLabel.' record saved successfully.';
                        $this->returnData = $this->childModelObj->{$this->childFindFunctionName}($this->childModelId)->toArray();
                        return true;
                    } else {
                        // page submit posted save
                        $this->message = $this->childLabel.' record saved successfully.';
                        return true;
                    }
                } else {
                    // form not valid
                    return false;
                }
            }

            // disabled elements do not post values
            // restore display only values
            // loop through form elements and update if disabled
            if (!$viewOnly && !$add) {
                foreach ($this->form->getElements() as $n => $e) {
                    if ('disabled' == $e->getAttrib('disabled') || $e->getAttrib('disabled')) {
                        $this->form->getElement($n)->setValue($this->childRecord->$n);
                    }
                }
            }
        } // end post
        return true;
    }

    public function preRequest() {
        $this->form->populate($this->childRecord->toArray());
    }

    public function prePost() {
        $this->form->populate($this->childRecord->toArray());
    }

    public function preAdd() {

        /**
         * set student, used for add student
         */
        $this->form->getElement($this->parentKeyName)->setValue($this->parentModelId);

        /**
         * convert save button to create button on form
         */
        $this->form->submit->setLabel('Create');
        $this->form->submit->setAttrib('id', 'create');

    }
}