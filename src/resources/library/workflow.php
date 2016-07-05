<?php 
/** classes for working with workorder workflows
* @author Raymond Brady
* @license MIT
*/


/** A workflow defines an ordered list of approvers
* @param {name} string - name which describes the workflow
* @param {approvers} array of Approver - ordered list of approvers
*/
class Workflow {
    function __construct ($name, $approvers, $initCurrent = false)
    {
        $this->name = $name;
        if ($initCurrent == true) {
            // Init the first approver as current
            foreach ($approvers as $key => $value) {
                if ($key == 0) {
                    $value->current = true;
                } else {
                    $value->current = false;
                }
            }
        }
        $this->approvers = $approvers;
    }
    
    public $name;
    public $approvers;
    
    public function asJSON()
    {
        return json_encode(array(
            'name' => $this->name,
            'approvers' => $this->approvers
        ));
    }
}

?>