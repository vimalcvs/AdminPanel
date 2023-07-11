<?php
	include_once('functions.php');
?>

<?php
	$function = new functions;
	$sql_query = "SELECT *, p.view_count AS total_count, p.active AS channel_status FROM tbl_channel p, tbl_category c WHERE p.category_id = c.cid GROUP BY id ORDER BY total_count DESC";
    $results = mysqli_query($connect, $sql_query);
	$recordCount = $results->num_rows;
 ?>

<?php

  //Total category count
  $sql_category = "SELECT COUNT(*) as num FROM tbl_category";
  $total_category = mysqli_query($connect, $sql_category);
  $total_category = mysqli_fetch_array($total_category);
  $total_category = $total_category['num'];

  //Total Chapter List count
  $sql_query = "SELECT COUNT(*) as num FROM tbl_channel";
  $total_channels = mysqli_query($connect, $sql_query);
  $total_channels = mysqli_fetch_array($total_channels);
  $total_channels = $total_channels['num'];

  //Total Users
  $sql_query = "SELECT COUNT(*) as num FROM tbl_user";
  $total_users = mysqli_query($connect, $sql_query);
  $total_users = mysqli_fetch_array($total_users);
  $total_users = $total_users['num'];


?>
<!--breadcrumbs start-->
<div id="breadcrumbs-wrapper" class=" grey lighten-3">
    <div class="container">
    <div class="row">
        <div class="col s12 m12 l12">
        <h5 class="breadcrumbs-title">Dashboard</h5>
        <ol class="breadcrumb">
            <li><a href="dashboard.php" class="deep-orange-text">Dashboard</a>
            </li>
            <li><a class="active">Home</a>
        </ol>
        </div>
    </div>
    </div>
</div>
<!--breadcrumbs end-->

<!--start container-->
<div class="container">
    <div class="section">
        <!--card stats start-->
        <div id="card-stats" class="seaction">
            <div class="row">
                <div class="col s12 m6 l3">
                    <a href="category.php">
                        <div class="card hoverable">
                            <div class="card-content deep-orange-g white-text">
                            <br>
								<i class="mdi-action-view-list small"></i>
                                <p class="card-stats-title">CATEGORY LIST</p>
                                <h4 class="card-stats-number"><?php echo $total_category;?></h4>
                            <br>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col s12 m6 l3">
                    <a href="channel.php">
                        <div class="card hoverable">
                            <div class="card-content deep-orange-g white-text">
                            <br>
								<i class="mdi-hardware-desktop-mac small"></i>
                                <p class="card-stats-title">CHAPTER LIST</p>
                                <h4 class="card-stats-number"><?php echo $total_channels;?></h4>
                            <br>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col s12 m6 l3">
                    <a href="users.php">
                        <div class="card hoverable">
                            <div class="card-content deep-orange-g white-text">
                            <br/>
								<i class="mdi-social-person small"></i>
                                <p class="card-stats-title">USERS</p>
                                <h4 class="card-stats-number"><?php echo $total_users;?></h4>
                            <br/>
                            </div>
                        </div>
                    </a>
                </div>

              
            </div>
            <!-- row completed -->
        </div>
        <!--card stats end-->
    </div>
    
    <!-- Popular Channels (by Views) -->
	<!-- START CONTENT -->
    <section id="content">
        <!--start container-->
        
          		<div class="row">
		        	<div class="col s12 m12 l12">
						<div class="card-panel">
                            <h5 class="breadcrumbs-title">Popular Chapter (by Views)</h5><br/>
							<table id="table_channel_popular" class="responsive-table display" cellspacing="0">		         
								<thead>
									<tr>
										<th class="hide-column">ID</th>
										<th>No.</th>
										<th>Image</th>
										<th>Name</th>
										<th>Category</th>
                               
										<th>View</th>
										<th>Status</th>
									</tr>
								</thead>

								<tbody>
									<?php
										$i = 1;
										while($data = mysqli_fetch_array($results)) {
									?>
                                          <tr>
                                            <td class="hide-column"><?php echo $data['id'];?></td>
                                            <td> <?php  echo $i; $i++; ?> </td>
                                                <td>
                                            <?php $channelImgPath = $data['channel_image']; ?>
                                     <img class="materialboxed circle z-depth-2" data-caption="<?php echo $data['channel_name'];?>"
                                   src="<?php if($channelImgPath == '') { echo DEFAULT_IMG; } else { echo UPLOAD_CHANNEL . $channelImgPath; }?>"
                                      height="54px" width="54px" style="object-fit: cover;"/>
                                        </td>

                                            <td><?php echo $data['channel_name'];?></td>
                                            <td><?php echo $data['category_name'];?></td>
                                          
                                            <td><?php echo $data['total_count'];?></td>
                                            <td>
                                                <?php if($data['channel_status']==1) {?>
                                                    <span class="task-cat green"><?php echo ACTIVE;?></span>
                                                <?php } else { ?>
                                                    <span class="task-cat red"><?php echo INACTIVE;?></span>
                                                <?php }  ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                            </div>
                        </div>
                    </div>
                </div>
           

    </section>
    <!-- Popular Channels (by Views)-->

</div> 
