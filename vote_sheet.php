
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <meta name="viewport" content="width=1124">
  <title>Voting System</title>
</head>

<?php include('db_connect.php');?>
<?php

	$voting = $conn->query("SELECT * FROM voting_list where  is_default = 1 ");
	foreach ($voting->fetch_array() as $key => $value) {
		$$key = $value;
	}

	$vchk = $conn->query("SELECT distinct(voting_id) from votes where user_id = ".$_SESSION['login_id']."")->num_rows;
	if($vchk > 0){
		header('Location:voting.php?page=view_vote');
	}

	$vote = $conn->query("SELECT * FROM voting_list where id=".$id);
	foreach ($vote->fetch_array() as $key => $value) {
		$$key= $value;
	}
	$opts = $conn->query("SELECT * FROM voting_opt where voting_id=".$id);
	$opt_arr = array();
	$set_arr = array();

	while($row=$opts->fetch_assoc()){
		$opt_arr[$row['category_id']][] = $row;
		$set_arr[$row['category_id']] = array('id'=>'','max_selection'=>1);

	}

	$settings = $conn->query("SELECT * FROM voting_cat_settings where voting_id=".$id);
	while($row=$settings->fetch_assoc()){
		$set_arr[$row['category_id']] = $row;
	}

?>
<style>
	.candidate {
	    margin: auto;
	    width: 20%;
	    padding: 15px;
	    cursor: pointer;
	    border-radius: 3px;
	    margin-bottom: 1em
	}
	.candidate:hover {
	    background-color: white;
	    box-shadow: 2.5px 3px #00000063;
	}
	.candidate img {
	    height: 14vh;
	    width: 23vw;
		margin-left:20px;
	    margin: auto;
	}
	span.rem_btn {
	    position: absolute;
	    right: 0;
	    top: -1em;
	    z-index: 10;
	    display: none
	}
	span.rem_btn.active{
		display: block
	}
	
	.text-center{
		font-size: 16px;
	}
	

	@media screen and (max-width: 800px;) {
		.candidate {
	    margin: 10% auto;
	    width: 13px;
	    padding: 15px;
	    cursor: pointer;
	    border-radius: 3px;
	    margin-bottom: 1em;
		font-size: 6px;
	}
	.candidate img {
	    height: 14vh;
	    width: 28vw;
	    margin: 10% auto;
	}

	.text-center{
		font-size: 16px;
	}
	
	}
	
</style>
<div class="container-fluid">
	<div class="col-lg-12">
		<div class="card">
			<div class="card-body">
				<form action="" id="manage-vote">
					<input style= "font-size:20px;" type="hidden" name="voting_id" value="<?php echo $id ?>">
				<div class="col-lg-12">
					<div class="text-center" style= "font-size:20px;">
						<h3><b><?php echo $title ?></b></h3>
						<large><b><?php echo $description; ?></b></large>	
					</div>
					
					<?php 
					$cats = $conn->query("SELECT * FROM category_list where id in (SELECT category_id from voting_opt where voting_id = '".$id."' )");
					while($row = $cats->fetch_assoc()):
					?>
						<hr>
						<div class="row mb-4">
							<div class="col-md-12">
									<div class="text-center" style= "font-size:20px;">
										<h3 style= "font-size:20px;"><b><?php echo $row['category'] ?></b></h3>
									<large>Max Selection : <b><?php echo $set_arr[$row['id']]['max_selection']; ?></b></large>

									</div>
							</div>
						</div>
						<div class="row mt-3">
						<?php foreach ($opt_arr[$row['id']] as $candidate) {
						?>
							<div class="candidate" style="position: relative;" data-cid = '<?php echo $row['id'] ?>'  data-max="<?php echo $set_arr[$row['id']]['max_selection'] ?>" data-name="<?php echo $row['category'] ?>">
									<input type="checkbox" name="opt_id[<?php echo $row['id'] ?>][]" value="<?php echo $candidate['id'] ?>" style="display: none">
								<span class="rem_btn">
									<label for="" class="btn btn-primary"><span class="fa fa-check"></span></label>
								</span>
								<div class="item"  data-id="<?php echo $candidate['id'] ?>">
								<div style="display: flex; margin:13% auto">
									<img src="assets/img/<?php echo $candidate['image_path'] ?>" alt="">
								</div>
								<br>
							<large>	<div class="text-center"  style= "display: flex; font-size: 26px">  </large>
									<large class="text-center"  style= "display: flex; font-size: 26px"><b><?php echo ucwords($candidate['opt_txt']) ?></b></large>

								</div>
								</div>
							</div>
						<?php } ?>
						</div>
					<?php endwhile; ?>
				</div>
				<hr>
				<button class="btn-block btn-primary" style= "font-size:20px;">Sumbit</button>
				</form>
			</div>
		</div>
	</div>
</div>
<script>
	$('.candidate').click(function(){
		var chk = $(this).find('input[type="checkbox"]').prop("checked");
		
		if(chk == true){
			$(this).find('input[type="checkbox"]').prop("checked",false)
		}else{
			var arr_chk = $("input[name='opt_id["+$(this).attr('data-cid')+"][]']:checked").length
			if($(this).attr('data-max') == 1){
			$("input[name='opt_id["+$(this).attr('data-cid')+"][]']").prop("checked",false)
			$(this).find('input[type="checkbox"]').prop("checked",true)
			}else{
			if(arr_chk >= $(this).attr('data-max')){
					alert_toast("Choose only "+$(this).attr('data-max')+" for "+$(this).attr('data-name')+" category","warning")
					return false;
				}
			}
			$(this).find('input[type="checkbox"]').prop("checked",true)
		}
		$('.candidate').each(function(){
			if($(this).find('input[type="checkbox"]').prop("checked") == true){
				$(this).find('.rem_btn').addClass('active')
			}else{
				$(this).find('.rem_btn').removeClass('active')
			}
		})
		
	})
	$('#manage-vote').submit(function(e){
		e.preventDefault()
		start_load();
		$.ajax({
			url:'ajax.php?action=submit_vote',
			method:'POST',
			data:$(this).serialize(),
			success:function(resp){
				if(resp == 1){
					alert_toast("Vote success fully submitted");
					setTimeout(function(){
						location.reload()
					},1500)
				}
			}
		})
	})
</script>