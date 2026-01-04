<?php
include('includes/dbconnection.php');
session_start();
error_reporting(0);

?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Bus transport  Management System</title>

<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
<!-- Custom Theme files -->
<link href="css receipt/bootstrap.css" type="text/css" rel="stylesheet" media="all">
<link href="css receipt/style.css" type="text/css" rel="stylesheet" media="all">  
<link href="css receipt/font-awesome.css" rel="stylesheet">	
	<!-- font-awesome icons -->  
<!-- //Custom Theme files -->  

  <!-- Customized Bootstrap Stylesheet -->
  <link href="css/bootstrap.min.css" rel="stylesheet">

<!-- Template Stylesheet -->
<link href="css/style.css" rel="stylesheet">


        <!-- Google Web Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&family=Yantramanav:wght@400;500;700&display=swap" rel="stylesheet"> 

        <!-- Icon Font Stylesheet -->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

        <!-- Libraries Stylesheet -->
        <link href="lib/animate/animate.min.css" rel="stylesheet">
        <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
        <link href="lib/lightbox/css/lightbox.min.css" rel="stylesheet">
</head>
<body>  
	         
             <!-- Topbar Start -->
        <div class="container-fluid  px-5 d-none d-lg-block" style="background-color: darkorange;">
            <div class="row gx-0 align-items-center" style="height: 45px;">
                <div class="col-lg-8 text-center text-lg-start mb-lg-0">
                    <div class="d-flex flex-wrap">
                        <a href="https://www.bing.com/maps?mepi=127%7E%7EUnknown%7EAddress_Link&ty=18&q=Dnyanshree+Institute+of+Engineering+and+Technology&ss=ypid.YN4070x12580472163235478366&ppois=17.67749786376953_73.98927307128906_Dnyanshree+Institute+of+Engineering+and+Technology_YN4070x12580472163235478366%7E&cp=17.665364%7E73.963405&v=2&sV=1&FORM=MPSRPL&lvl=12.6" class="text-light me-4"><i class="fas fa-map-marker-alt text-primary me-2"></i>Find A Location</a>
                        <a href="+086000 09009" class="text-light me-4"><i class="fas fa-phone-alt text-primary me-2"></i>+086000 09009</a>
                        <a href="rwmctsatara@dnyanshree.edu.in" class="text-light me-0"><i class="fas fa-envelope text-primary me-2"></i>rwmctsatara@dnyanshree.edu.in</a>
                    </div>
                </div>
                <div class="col-lg-4 text-center text-lg-end">
                    <div class="d-flex justify-content-end">
                        <div class="border-end border-start py-1">
                            <a href="https://www.facebook.com/DNYANSHREE.Institute.OfficalPage/" class="btn text-primary"><i class="fab fa-facebook-f"></i></a>
                        </div>
                        <div class="border-end py-1">
                            <a href="https://x.com/dnyanshree?lang=en&mx=2" class="btn text-primary"><i class="fab fa-twitter"></i></a>
                        </div>
                        <div class="border-end py-1">
                            <a href="https://www.instagram.com/dnyanshree_institute?igsh=MTJ0bnhiY21lcDR1cg==" class="btn text-primary"><i class="fab fa-instagram"></i></a>
                        </div>
                        <div class="border-end py-1">
                            <a href="https://www.linkedin.com/school/dnyanshree-college-of-engineering-and-technology/" class="btn text-primary"><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Topbar End -->
           <!-- Navbar & Hero Start -->
        <div class="container-fluid position-relative p-0">
            <nav class="navbar navbar-expand-lg navbar-light bg-white px-4 px-lg-5 py-3 py-lg-0">
                <a href="#" class="navbar-brand p-0">
                    <h1 class="text-primary m-0">                        
                        <img src="img/logo.jpg" class="logo"> DIET, Satara
                      </h1>
                      
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                    <span class="fa fa-bars"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <div class="navbar-nav ms-auto py-0">
                        <a href="index.html" class="nav-item nav-link active">Home</a>
                        <a href="about.html" class="nav-item nav-link">About</a>
                        <a href="contact.php" class="nav-item nav-link">Contact Us</a>
                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Pages</a>
                            <div class="dropdown-menu m-0">
                                <a href="download-pass.php" class="dropdown-item"> profile </a>
                                <a href="pass.php" class="dropdown-item">View pass </a>
                                <a href="route.html" class="dropdown-item">Bus Route</a>
                                <a href="AboutTeam.html" class="dropdown-item">Our Team</a>
                             
                            </div>
                        </div>
                        
                    </div>
                    <a href="admin/index.php" class="btn btn-primary rounded-pill text-white py-2 px-4 flex-wrap flex-sm-shrink-0">Admin</a>
                </div>
            </nav>
        </div>
        <!-- Navbar & Hero End -->

           <!-- Carousel Start -->
        <div class="header-carousel owl-carousel">
            <div class="header-carousel-item">
                <img src="img/bus.jpg" class="img-fluid w-100" alt="Image">
                <div class="carousel-caption">
                    <div class="carousel-caption-content p-3" style="max-width: 900px;">
                        <h4 class="text-secondary text-uppercase sub-title fw-bold mb-4 wow fadeInUp" data-wow-delay="0.1s" style="letter-spacing: 3px;">Let's DIET</h4>
                        <h1 class="display-1 text-capitalize text-white mb-4 wow fadeInUp" data-wow-delay="0.3s">"On Time, Every Time Get to Class Without the Wait."</h1>
                        <p class="fs-5 wow fadeInUp" data-wow-delay="0.5s">Our college bus transport system provides reliable, on-time services designed to make your campus commute easier and more efficient.</p>
                        <div class="pt-2">
                            <a class="btn btn-primary rounded-pill text-white py-3 px-5 m-2 wow fadeInLeft" data-wow-delay="0.1s" href="#">Join Now</a>
                            <a class="btn btn-secondary rounded-pill text-white py-3 px-5 m-2 wow fadeInRight" data-wow-delay="0.3s" href="#">Read More</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="header-carousel-item">
                <img src="img/bus1.jpg" class="img-fluid w-100" alt="Image">
                <div class="carousel-caption">
                    <div class="carousel-caption-content p-3" style="max-width: 900px;">
                        <h4 class="text-secondary text-uppercase sub-title fw-bold mb-4" style="letter-spacing: 3px;">Let's DIET</h4>
                        <h1 class="display-1 text-capitalize text-white mb-4"> "On Time, Every Time Get to Class Without the Wait."</h1>
                        <p class="fs-5">Our college bus transport system provides reliable, on-time services designed to make your campus commute easier and more efficient.</p>
                        <div class="pt-2">
                            <a class="btn btn-primary rounded-pill text-white py-3 px-5 m-2 wow fadeInLeft" data-wow-delay="0.1s" href="#">Join Now</a>
                            <a class="btn btn-secondary rounded-pill text-white py-3 px-5 m-2 wow fadeInRight" data-wow-delay="0.3s" href="#">Read More</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="header-carousel-item">
                <img src="img/bus.jpg" class="img-fluid w-100" alt="Image">
                <div class="carousel-caption">
                    <div class="carousel-caption-content p-3" style="max-width: 900px;">
                        <h4 class="text-secondary text-uppercase sub-title fw-bold mb-4" style="letter-spacing: 3px;">Let's DIET</h4>
                        <h1 class="display-1 text-capitalize text-white mb-4">"On Time, Every Time Get to Class Without the Wait."</h1>
                        <p class="fs-5">Our college bus transport system provides reliable, on-time services designed to make your campus commute easier and more efficient.</p>
                        <div class="pt-2">
                            <a class="btn btn-primary rounded-pill text-white py-3 px-5 m-2 wow fadeInLeft" data-wow-delay="0.1s" href="#">Join Now</a>
                            <a class="btn btn-secondary rounded-pill text-white py-3 px-5 m-2 wow fadeInRight" data-wow-delay="0.3s" href="#">Read More</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Carousel End -->
	<!-- contact -->
	<div class="contact agileits">
		<div class="container">  
			<div class="agileits-title">
				<h3>View Pass</h3>
			</div>  
			<div class="contact-agileinfo">
				<div class="col-md-7 contact-form wthree">
					<form action="#" method="post">
						<input id="searchdata" type="text" name="searchdata" placeholder="Search by Pass Number" required="true">
					 <button style="padding-top: 14px;" type="submit" class="btn btn-primary" name="search" id="submit">Search</button>
					</form>
				</div>
				
				<div class="clearfix"> </div>	
				<div class="table-responsive">
                                 <?php
if(isset($_POST['search']))
{ 

$sdata=$_POST['searchdata'];
  ?>
  <h4 style="padding-bottom: 20px;">Result against "<?php echo $sdata;?>" keyword </h4>
                                <table border="2" class="table table-bordered" style="font-size: 18px;">
                                    <thead>
                                        <tr>
                                            <th>S.NO</th>
                                           <th>Pass Number</th>
                                            <th>Full Name</th>
                                            <th>Contact Number</th>
                                            <th>PRN number</th>
                                            <th>Creation Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
$sql="SELECT * from tblpass1 where PassNumber like '$sdata%'";
$query = $dbh -> prepare($sql);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);

$cnt=1;
if($query->rowCount() > 0)
{
foreach($results as $row)
{               ?>
                                                           <tr>
                  <td><?php echo htmlentities($cnt);?></td>
                  <td><?php  echo htmlentities($row->PassNumber);?></td>
                  <td><?php  echo htmlentities($row->FullName);?></td>
                  <td><?php  echo htmlentities($row->ContactNumber);?></td>
                  <td><?php  echo htmlentities($row->prn_number);?></td>
                  <td><?php  echo htmlentities($row->FromDate);?></td>
                  <td>
    <a href="view-pass.php?prn_number=<?php echo htmlentities($row->prn_number); ?>" class="btn btn-primary">View</a>
</td>

                </tr>
               <?php 
$cnt=$cnt+1;
} } else { ?>
  <tr>
    <td colspan="8"> No record found against this search</td>

  </tr>
   
<?php } }?> 
                                       
                                        
                                    </tbody>
                                </table>
                            </div>
			</div>
		</div>
	</div>
	<!-- //contact -->  
	 
	
<?php include_once('includes/footer.php');?>   
 
	<!-- js --> 
	<script src="js/jquery-2.2.3.min.js"></script> 
	<script src="js/SmoothScroll.min.js"></script>
	<script src="js/jarallax.js"></script> 
	<script type="text/javascript">
		/* init Jarallax */
		$('.jarallax').jarallax({
			speed: 0.5,
			imgWidth: 1366,
			imgHeight: 768
		})
	</script>  
	<!-- //js -->  
	<!-- start-smooth-scrolling --> 
	<script type="text/javascript" src="js/move-top.js"></script>
	<script type="text/javascript" src="js/easing.js"></script>	
	<script type="text/javascript">
			jQuery(document).ready(function($) {
				$(".scroll").click(function(event){		
					event.preventDefault();
			
			$('html,body').animate({scrollTop:$(this.hash).offset().top},1000);
				});
			});
	</script>
	<!-- //end-smooth-scrolling -->	 
	<!-- smooth-scrolling-of-move-up -->
	<script type="text/javascript">
		$(document).ready(function() {
			/*
			var defaults = {
				containerID: 'toTop', // fading element id
				containerHoverID: 'toTopHover', // fading element hover id
				scrollSpeed: 1200,
				easingType: 'linear' 
			};
			*/
			
			$().UItoTop({ easingType: 'easeOutQuart' });
			
		});
         
		
	</script>
	<!-- //smooth-scrolling-of-move-up -->  
	<!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/bootstrap.js"></script> <br><br><br> <br><br><br><br><br><br><br><br><br><br>

    <footer class="footer" style="background-color: black; color: white; padding: 10px 0;">
    <div class="container text-center">
        <p>&copy; 2024 DIET Bus. © All Rights Reserved.</p>
        <!-- <p> <a href="AboutTeam.html" style="color: white; text-decoration: none;">Our Terms</a></p> -->
    </div>
</footer>

</body>
</html>