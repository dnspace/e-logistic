<form action="<?php echo base_url('oversee/print-replenish-plan');?>" method="POST" class="form-horizontal" role="form">
<div class="row">
    <div class="col-md-12">
        <div class="card-box">
            <h4 class="header-title m-b-20"><?php echo $contentTitle;?></h4><hr>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card bg-light">
                            <div class="card-header bg-primary text-white">
                                <strong class="card-title">Report Replenish Plan</strong>
                            </div>
                            <div class="card-body">
                                <div class="form-row">
                                    <div class="mt-12 d-none d-xl-block">
                                        <h5 class="text-left">How It Works ?</h5>
                                        <ul class="pl-3">
                                            <li class="text-dark mb-1">
                                                Please select start date (left side) and end date (right side) along with their time to generate your data
                                            </li>
                                            <li class="text-dark mb-1">
                                                If you need to generate daily data, please set end date to (start date + 1 day). for example; 01/08/2018 14:00 to 02/08/2018 00:00
                                            </li>
                                            <li class="text-dark mb-1">
                                                For FSL Selection, you can select All checklist or just check some FSL.
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="input-group col-sm-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"> <i class="fa fa-calendar"></i> </span>
                                         </div>
                                        <input type="text" name="fdate1" id="fdate1" class="form-control" required="required">
                                    </div>
                                    <div class="input-group col-sm-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"> <i class="fa fa-calendar"></i> </span>
                                         </div>
                                        <input type="text" name="fdate2" id="fdate2" class="form-control" required="required">
                                    </div>
                                    <div class="input-group col-sm-12">
                                        <div id="fsearch_notes"></div>
                                    </div>
                                </div>
                                <div class="mt-4"></div>
                                <div class="form-row">
                                    <div class="col-sm-12">
                                        <div class="checkbox checkbox-info checkbox-circle">
                                            <input name="fcode_all" id="fcode_all" type="checkbox" value="*">
                                            <label for="fcode_all">
                                                Check All / Uncheck All
                                            </label>
                                        </div>
                                        <div class="input-group col-sm-12">
                                            <?php
                                                $col1 = 0;
                                                $col2 = 0;
                                                $col3 = 0;
                                                $col4 = 0;
                                                $arr_col1 = array();
                                                $arr_col2 = array();
                                                $arr_col3 = array();
                                                $arr_col4 = array();
                                                $t_list = count($list_wr);
                                                $t_divide = (int) ceil($t_list/4);
                                                
                                                $arr_col1 = array_slice($list_wr, 0, (int) ceil($t_list/4));
                                                $arr_col2 = array_slice($list_wr, count($arr_col1), (int) ceil($t_list/4));
                                                $arr_col3 = array_slice($list_wr, (count($arr_col1) + count($arr_col2)), (int) ceil($t_list/4));
                                                $arr_col4 = array_slice($list_wr, (count($arr_col1) + count($arr_col2) + count($arr_col3)), (int) ceil($t_list/4));
                                                
                                            ?>
                                            <div class="col-sm-3">
                                            <?php
                                                foreach($arr_col1 as $c1){
                                                    $code = filter_var($c1["code"], FILTER_SANITIZE_STRING);
                                                    $name = filter_var($c1["name"], FILTER_SANITIZE_STRING);
                                                    
                                                    echo '<div class="col-sm-12">';
                                                        echo '<div class="checkbox checkbox-custom">';
                                                            echo '<input type="checkbox" id="checkbox'.$col1.'" name="fcode[]" value="'.$code.'" />'
                                                                    . '<label for="checkbox'.$col1.'">'.$name.'</label>';
                                                        echo '</div>';
                                                    echo '</div>';
                                                    $col1++;
                                                }
                                            ?>
                                            </div>
                                            <div class="col-sm-3">
                                            <?php
                                                foreach($arr_col2 as $c2){
                                                    $code = filter_var($c2["code"], FILTER_SANITIZE_STRING);
                                                    $name = filter_var($c2["name"], FILTER_SANITIZE_STRING);
                                                    $col2 = $col1 + $col2;
                                                    echo '<div class="col-sm-12">';
                                                        echo '<div class="checkbox checkbox-custom">';
                                                            echo '<input type="checkbox" id="checkbox'.$col2.'" name="fcode[]" value="'.$code.'" />'
                                                                    . '<label for="checkbox'.$col2.'">'.$name.'</label>';
                                                        echo '</div>';
                                                    echo '</div>';
                                                    $col2++;
                                                }
                                            ?>
                                            </div>
                                            <div class="col-sm-3">
                                            <?php
                                                foreach($arr_col3 as $c3){
                                                    $code = filter_var($c3["code"], FILTER_SANITIZE_STRING);
                                                    $name = filter_var($c3["name"], FILTER_SANITIZE_STRING);
                                                    $col3 = $col2 + $col3;
                                                    
                                                    echo '<div class="col-sm-12">';
                                                        echo '<div class="checkbox checkbox-custom">';
                                                            echo '<input type="checkbox" id="checkbox'.$col3.'" name="fcode[]" value="'.$code.'" />'
                                                                    . '<label for="checkbox'.$col3.'">'.$name.'</label>';
                                                        echo '</div>';
                                                    echo '</div>';
                                                    $col3++;
                                                }
                                            ?>
                                            </div>
                                            <div class="col-sm-3">
                                            <?php
                                                foreach($arr_col4 as $c4){
                                                    $code = filter_var($c4["code"], FILTER_SANITIZE_STRING);
                                                    $name = filter_var($c4["name"], FILTER_SANITIZE_STRING);
                                                    $col4 = $col3 + $col4;
                                                    
                                                    echo '<div class="col-sm-12">';
                                                        echo '<div class="checkbox checkbox-custom">';
                                                            echo '<input type="checkbox" id="checkbox'.$col4.'" name="fcode[]" value="'.$code.'" />'
                                                                    . '<label for="checkbox'.$col4.'">'.$name.'</label>';
                                                        echo '</div>';
                                                    echo '</div>';
                                                    $col4++;
                                                }
                                            ?>
                                            </div>
                                        </div>
                                        <!--</select>-->
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" id="btn_generate_plan" class="btn btn-success waves-effect waves-light">
                                    Generate
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</form>

<script type="text/javascript">
    $(document).ready(function() {
        var today = new Date();
        var tomorrow = new Date().setDate(new Date().getDate() + 1);
        jQuery.datetimepicker.setLocale('id');
        jQuery('#fdate1').datetimepicker({
            i18n:{
                id:{
                    months:[
                     'Januari','Februari','Maret','April',
                     'Mei','Juni','Juli','Agustus',
                     'September','Oktober','November','Desember',
                    ],
                    dayOfWeek:[
                     "Minggu", "Senin", "Selasa", "Rabu", 
                     "Kamis", "Jumat", "Sabtu",
                    ]
                }
            },
            timepicker:true,
            mask:true,
            format: 'Y/m/d H:i',
            formatTime: 'H:i',
            formatDate: 'Y-m-d',
            startDate:  false, // new Date(), '1986/12/08', '-1970/01/05','-1970/01/05',
            step: 60,
            monthChangeSpinner: true,
            
            defaultTime: '00:00', // use formatTime format (ex. '10:00' for formatTime: 'H:i')
            defaultDate: today, // use formatDate format (ex new Date() or '1986/12/08' or '-1970/01/05' or '-1970/01/05')
            minDate: '2018/06/01',
            maxDate: false,
            
            withoutCopyright: true,
        });
        
        jQuery('#fdate2').datetimepicker({
            i18n:{
                id:{
                    months:[
                     'Januari','Februari','Maret','April',
                     'Mei','Juni','Juli','Agustus',
                     'September','Oktober','November','Desember',
                    ],
                    dayOfWeek:[
                     "Minggu", "Senin", "Selasa", "Rabu", 
                     "Kamis", "Jumat", "Sabtu",
                    ]
                }
            },
            timepicker:true,
            mask:true,
            format: 'Y/m/d H:i',
            formatTime: 'H:i',
            formatDate: 'Y-m-d',
            startDate:  false, // new Date(), '1986/12/08', '-1970/01/05','-1970/01/05',
            step: 60,
            monthChangeSpinner: true,
            
            defaultTime: '00:00', // use formatTime format (ex. '10:00' for formatTime: 'H:i')
            defaultDate: tomorrow, // use formatDate format (ex new Date() or '1986/12/08' or '-1970/01/05' or '-1970/01/05')
            
            withoutCopyright: true,
        });
        
        $('#fcode_all').on("click", function(e){
            $('input:checkbox').not(this).prop('checked', this.checked);
        });
        $("#btn_generate_plan").on("click", function(e){
            var checked = $("input[type=checkbox]:checked").length;
            if(!checked) {
                alert("You must check at least one FSL.");
                return false;
            }else{
                return true;
            }
        });
    });
</script>