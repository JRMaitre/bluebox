<div id="mediacollection_update_header" class="update mediacollection module_header">

    <h2><?php echo $title; ?></h2>

</div>

<div id="mediacollection_update_form" class="txt-left form mediacollection updates">

    <?php echo form::open(); ?>

    <?php echo form::open_section('Record'); ?>

	<div class="field">
            <?php echo form::label('mediafile[name]', 'File Name'); ?>
            <?php echo form::input('mediafile[name]'); ?>
	</div>
        <div class="field">
            <?php echo form::label('mediafile[description]', 'Description:'); ?>
            <?php echo form::textarea('mediafile[description]'); ?>
        </div>
	<div class="field">
            <?php echo form::label('mediafile[phoneRecord]', 'Select a device'); ?>
            <?php echo form::dropdown('mediafile[phoneRecord]', $devices); ?></span>
	    <input id="btnCall" type="button" value="Call"/>
	</div>
	<div class="field" id="error" style='display:none; font-weight: bold;'>
	    <span id="errorSpan" style="color:#FF0000">An error occured while creating/recording the file</span><br/>
	</div>
	<div class="field" id="success" style='display:none; font-weight: bold;'>
	    <span id="successSpan" style="color:#00C000">Great success</span><br/>
	</div>
	<div class="field" id="loadingText" style='display:none; font-weight:bold;'>
	    <span id="loadingSpan" style="color:#0000FF"><?php echo html::image('assets/img/loading.gif','Calling...'); ?> Calling... </span>
	</div>
	<div class="field" id="playback" style='display:none;'>
		<?php echo form::label('listen', 'Listen your file: ');?>
	</div>
	<?php echo form::hidden('mediafile[file]');?>
    <?php echo form::close_section(); ?>

    <?php echo form::close(TRUE); ?>
    
</div>
<script type="text/javascript">
$(function(){
	$('#btnCall').click(function(){
	  var number=$('#mediafile_phoneRecord').val();
	  var filename=$('#mediafile_name').val();
	  filename = filename.replace(/&/g,"");
	  filename = filename.replace(/ /g,"_");
	  $('#mediafile_name').val(filename);
	  $('#loadingText').show();
	  $('#error').hide();	 
	  $('#success').hide();	 
	  $('#playback').hide();	 
	  $('#btnCall').attr("disabled", "true");
	  $('#mediafile_file_hidden').val(filename+'.wav');
   	  $.ajax({
                  url: 'index.php/mediafile/call?number_id=' + number + '&file_name=' + filename,
	  	  type: 'GET',
		  success: function(data){
	  		$('#loadingText').hide();	  
			if(data.indexOf('This file already exists, please choose another one')!=-1)
			{
				$('#errorSpan').text("Error: This file : " +filename+"  already exists, please choose another name.");
				$('#error').show();
			}
			else if(data.indexOf('Error: file not created, please try again.')!=-1)
			{
				$('#errorSpan').text("Error: file not created, please try again.");
				$('#error').show();
			}
			else if(data.indexOf("regex not matched")!=-1)
			{
				$('#errorSpan').text("Error: filename invalid, filename must only contain alphanumeric characters.");
				$('#error').show();
			}
			else
			{
				$('#playback').html("<audio id=\"srcpl\" src=\"<?php echo Kohana::config('core.site_domain'); ?>uploads/<?php echo users::getAttr('account_id'); ?>/"+filename+".wav\" controls=\"controls\">Your browser does not support the audio element. </audio>");
				$('#successSpan').text("Successful recording.");
				$('#success').show();
				$('#playback').show(); 
			}
	  		$('#btnCall').removeAttr('disabled');
		  }
	   });
	 });
});

</script>
