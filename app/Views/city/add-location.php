<div class="page-header">	<h2>City Form</h2></div><?php echo form_open_multipart(admin_url('city/add_location/'. $location -> id), array('class' => 'form-horizontal')); ?><div class="box box-p">    <div class="tab-pane active" id="description_tab">        <div class="form-group">            <label class="col-sm-2">City</label>            <div class="col-sm-3" id="citydd">                <?php                $city_arr = array();                foreach($cities as $c){                    $city_arr[$c -> id] = $c -> city_name;                }                echo form_dropdown('form[city_id]', $city_arr, set_value('form[city_id]', $location -> city_id), 'class="form-control input-sm"');                ?>            </div>        </div>        <div class="form-group">            <label class="col-sm-2">Location</label>            <div class="col-sm-4">                <input type="text" name="form[location]" value="<?php  echo set_value('form[location]', $location -> location); ?>" class="form-control input-sm" />            </div>        </div>        <div class="form-group">            <label class="col-sm-2">&nbsp;</label>            <div class="col-sm-4">                <button type="submit" class="btn btn-primary btn-sm">Save</button>            </div>        </div>    </div></div></form>