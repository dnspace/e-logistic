<div class="row">
    <div class="col-md-12">
        <div class="card-box">
            <h4 class="m-t-0 header-title"><?php echo $contentTitle;?></h4>
            <div class="btn-group">
                <button type="button" onclick="location.href='javascript:history.back()'" class="btn btn-sm btn-light waves-effect">
                    <i class="mdi mdi-keyboard-backspace font-18 vertical-middle"></i> Back
                </button>
            </div>
            
            <p class="text-success text-center">
                <?php
                $error = $this->session->flashdata('error');
                if($error)
                {
                ?>
                <div class="alert alert-danger alert-dismissable" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <?php echo $error; ?>                    
                </div>
                <?php
                }
                $success = $this->session->flashdata('success');
                if($success)
                {
                ?>
                <div class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <?php echo $success; ?>                    
                </div>
                <?php } ?>
            </p>
        
            <div class="card-body">
                <form class="form-horizontal" action="<?php echo base_url('front/cpartsub/create');?>" method="POST" role="form">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
                    <div class="form-group row">
                        <label for="fpartnum" class="col-2 col-form-label">Part Number</label>
                        <div class="col-3">
                            <input type="text" name="fpartnum" id="fpartnum" required placeholder="Part Number" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="fpartsub" class="col-2 col-form-label">Part Number Subtitute</label>
                        <div class="col-10">
                            <select name="fpartsub[]" id="fpartsub" class="selectpicker" multiple data-live-search="true" 
                                    data-selected-text-format="count > 5" title="Please choose.." data-style="btn-light">
                                <?php
                                    foreach($list_data_part as $p){
                                        echo '<option value="'.$p["partno"].'">'.$p["partno"].'</option>';
                                    }
                                ?>
                            </select>
                            <span class="help-block"><small>List Part Number for Subtitution</small></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="btn" class="col-2 col-form-label">&nbsp;</label>
                        <div class="col-6">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        
    });
</script>