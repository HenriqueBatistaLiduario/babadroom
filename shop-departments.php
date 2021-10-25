  <?php include 'header.php'; ?>
    <!-- Page Title (Light)-->
    <div class='bg-secondary py-4'>
      <div class='container d-lg-flex justify-content-between py-2 py-lg-3'>        
        <div class='order-lg-1 pr-lg-4 text-center text-lg-left'>
          <h1 class='h3 mb-0'>Departamentos</h1>
        </div>
      </div>
    </div>
		
    <!-- Page Content-->
    <div class='container pb-4 pb-sm-5'>
     
      <div class='row pt-5'>
			
			  <?php 

					$GRPDEPTselect = mysqli_query($con,"SELECT GRPCOD,GRPNAME,GRPPATHIMG FROM groups WHERE GRPAPPLY = 'DEPT' AND GRPSTATUS = 1") or print (mysqli_error());
					$GRPDEPTtotal  = mysqli_num_rows($GRPDEPTselect);
					
					if($GRPDEPTtotal > 0){
						
						while($GRPDEPTrow = mysqli_fetch_array($GRPDEPTselect)){
							
							$DEPTGROUPCOUNT = 0;
							
							$GRPDEPTCOD     = $GRPDEPTrow["GRPCOD"];
							$GRPDEPTNAME    = $GRPDEPTrow["GRPNAME"];
							$GRPDEPTPATHIMG = $GRPDEPTrow["GRPPATHIMG"];
							
							echo "
							
							  <div class='col-md-4 col-sm-6 mb-3'>
									<div class='card border-0'>
										<a class='d-block overflow-hidden rounded-lg' href='shop.php?get=DEPTGROUP&dept=$GRPDEPTCOD'><img class='d-block w-100' src='$GRPDEPTPATHIMG' alt='$GRPDEPTCOD'></a>
										<div class='card-body'>
											<h2 class='h5'>$GRPDEPTNAME</h2>
											<ul class='list-unstyled font-size-sm mb-0'>";
											
											  $DEPTselect = mysqli_query($con,"SELECT * FROM departments WHERE GRPCOD = '$GRPDEPTCOD' AND DEPTSTATUS=1") or print (mysqli_error());
												$DEPTtotal  = mysqli_num_rows($DEPTselect);
												
												if($DEPTtotal > 0){
													
													while($DEPTrow = mysqli_fetch_array($DEPTselect)){
														
														$DEPTCOD  = $DEPTrow["DEPTCOD"];
														$DEPTNAME = $DEPTrow["DEPTNAME"];
														
														$PRDCOUNTselect = mysqli_query($con,"SELECT PRDID FROM products WHERE DEPTCOD = '$DEPTCOD' AND PRDSTATUS = 1");
														$PRDCOUNT = mysqli_num_rows($PRDCOUNTselect);
														
														$DEPTGROUPCOUNT += $PRDCOUNT;
														
														echo "
														<li class='d-flex align-items-center justify-content-between'>
															<a class='nav-link-style' href='shop.php?get=DEPT&dept=$DEPTCOD'><i class='czi-arrow-right-circle mr-2'></i>$DEPTNAME</a>
															<span class='font-size-ms text-muted'>$PRDCOUNT</span>
														</li>";																
														
													}
												}
									
												echo "									
												
												<li>...</li>
												<li>
													<hr>
												</li>
												<li class='d-flex align-items-center justify-content-between'><a class='nav-link-style' href='shop.php?get=DEPTGROUP&dept=$GRPDEPTCOD'>
												<i class='czi-arrow-right-circle mr-2'></i>Ver todos...</a><span class='font-size-ms text-muted'>$DEPTGROUPCOUNT</span></li>
											</ul>
										</div>
									</div>
								</div>";													
						
						}
					}

				?>
				
      </div>
    </div>
    <!-- Footer-->
    <?php include 'footer.php'; ?>