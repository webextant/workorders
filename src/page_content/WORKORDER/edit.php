<?php
/************************************************************************************************
Edit a previously submitted workorder
Author: Raymond Brady
Date Created: 7/12/2016
************************************************************************************************/
    require_once('./resources/library/workorder.php');

    $woId = $header_GET_array[0];
    $approveKey = $header_GET_array[1];
    $woDbAdapter = new WorkorderDataAdapter($dsn, $user_name, $pass_word);
    $wo = $woDbAdapter->Select($woId);
    $woViewModel = new WorkorderViewModel($wo, $approveKey);
?>
<section>
    <div class="row">
        <div class="col-lg-3"></div>
        <div class="col-lg-6">
            <h1 class="page-header">
                <?=$wo->formName?>
            </h1>
            <ol class="breadcrumb">
                <li class="active">
                    <i class="fa fa-fw fa-file"></i>Workorder #<?=$wo->id?>
                </li>
                <li><?=$wo->createdAt?></li>
                <li><?=$wo->createdBy?></li>
            </ol>
        </div>
        <div class="col-lg-3"></div>
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-3"></div>
        <div class="col-lg-6">
            <div class="<?=$woViewModel->stateColorClass?>"><?=$woViewModel->approveState?> (<?=$wo->currentApprover?>)</div>
            <form action="./?I=<?=pg_encrypt('WORKORDER-edit|'.$wo->id."|".$wo->approverKey,$pg_encrypt_key,'encode')?>" method="post" enctype="multipart/form-data">
                <input type="hidden" id="post_type" name="post_type" value="<?php echo pg_encrypt("qryWORKORDER-edit_workorder_qry",$pg_encrypt_key,"encode") ?>" />
                <input type="hidden" id="form-xml-schema" name="form-xml-schema" value="<?=htmlspecialchars($wo->formXml)?>" />
                <input type="hidden" id="form-name" name="form-name" value="<?=$wo->formName?>" />
                <input type="hidden" id="form-description" name="form-description" value="<?=$wo->description?>" />
                <input type="hidden" id="form-id" name="form-id" value="<?=$wo->formId?>" />
                <input type="hidden" id="workorder-id" name="workorder-id" value="<?=$wo->id?>" />
                <?php foreach ($woViewModel->formData as $fieldkey => $value) { $fieldInfo = $woViewModel->GetFormXmlFieldInfo($fieldkey, $value); ?>
                    <div class="form-group">
                        <label><?=$fieldInfo['label']?></label>
                        <?=$fieldInfo['form_html']?>
                    </div>
                <?php } ?>
               <button type="submit" class="btn btn-success">Save</button> <a class="btn btn-primary" href="./workorderview.php?id=<?=$wo->id?>&key=<?=$wo->approverKey?>" >Approve / Deny</a>
            </form>

        </div>
        <div class="col-lg-3"></div>
    </div>
</section>