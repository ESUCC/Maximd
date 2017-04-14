<?php

class DojoForm extends Zend_Dojo_Form
{
public function init()
{

$this->setAction('your/action/page/path');


$file=$this->CreateElement('file','file');
$file->setLabel('File')
->setRequired(false)
->setAttrib('tabindex','15');

$this->addElement($file); 




$this->setMethod('post');
$this->addElement(

'DateTextBox',
'creationdate',

array(
'label'          => 'Date:',
'required'       => true,
'invalidMessage' => 'Invalid date specified.',
'formatLength'   => 'long',

)
);
$this->addElement(

'ComboBox',
'box',
array(

'label' => 'Gender',
'checkedValue'=>'0',
'unCheckedValue'=>'1',
'checked' => true,
'multiOptions' => array(
'0' => 'Male',
'1' => 'Female'

)
));
$this->addElement(

'NumberSpinner',
'spinner',
array(

'label' => 'Age:',
'value' => 2,
'min'    => -10,
'max'    => 10,
'places' => 2),
array(
)

);
$this->addElement(
'HorizontalSlider',
'horizontal',

array(
'label'                     => 'HorizontalSlider',
'value'                     => 5,
'minimum'                   => -10,
'maximum'                   => 10,
'discreteValues'            => 11,
'intermediateChanges'       => true,
'showButtons'               => true,
'topDecorationDijit'        => 'HorizontalRuleLabels',
'topDecorationContainer'    => 'topContainer',
'topDecorationLabels'       => array(

' ',
'20%',
'40%',
'60%',
'80%',
' ',
),

'topDecorationParams'      => array(

'container' => array(

'style' => 'height:1.2em; font-size=75%;color:gray;',

),

'list' => array(
'style' => 'height:1em; font-size=75%;color:gray;',
),

),
'bottomDecorationDijit'     => 'HorizontalRule',
'bottomDecorationContainer' => 'bottomContainer',
'bottomDecorationLabels'    => array(

'0%',
'50%',
'100%',

),
'bottomDecorationParams'   => array(

'list' => array(
'style' => 'height:1em; font-size=75%;color:gray;',
),
),

)
);

}
}