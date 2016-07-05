Forms Workflow Notes

Workorder
CurrentApprover
Workflow
ApproveState
ApproverKey

Workflow
Name - Description/ID of the workflow
Approvers - Array of Approver

Approver
Name - Persons full name
Email
Current - Boolean designates current approver when in an array

ApproveState
PendingApproval
ApproveInProgress
ApproveClosed
RejectClosed

ApproverKey
Generated based on GUID for each active approver. Used in URL

Email - Link Generation, Sending
Generate view only link. Use view only key generated for link
Generate a unique Approver link only valid for active approver.
Could be extended to support other notifications

ApproverHelper Class
used to work with arrays of Approver
getFirst, getNext, getPrevious, isFinal, etc…




User saves new form process

Submit workorder form
Generate approver key for the email.
Set workflow from the forms template
Set first approver in the workflow as the current approver
Set ApproveState to PendingApproval
Save new Form/Workorder to DB
Send approver email to current approver
Send view only email to user
User shown view only version of form
