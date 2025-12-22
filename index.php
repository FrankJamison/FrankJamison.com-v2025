<?php
ob_start();

// Include Functions
require_once("./includes/functions.inc.php");

// Form display variable
$output_form = true;

// Form Error Text
$errorText = '';

// Email Send To Variables
$emailTo = 'frank@frankjamison.com';
$emailSubject = 'FrankJamison.com Contact Form Submission';
$emailMessage = '';

// Form Input Variables
$firstName = '';
$lastName = '';
$emailAddress = '';
$comment = '';
$specialInstructions = '';

// Validated Input Variables
$validFirstName = '';
$validLastName = '';
$validEmailAddress = '';
$validComment = '';
$validSpecialInstructions = '';

//RegEx Patterns
$regExFirstName = '/^[a-zA-Z]{2,15}$/';
$regExLastName = '/^[a-zA-Z]{2,15}$/';
$regExEmailAddress = '/^([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5})$/';
$regExComment = '/^.{2,}$/';
$regExSpecialInstructions = '/^[a-zA-Z]{2,15}$/';

if (isset($_POST['submit'])) { // data posted

	// Get Form Input
	$firstName = trim($_POST['firstName']);
	$lastName = trim($_POST['lastName']);
	$emailAddress = trim($_POST['emailAddress']);
	$comment = trim($_POST['comment']);
	$specialInstructions = trim($_POST['specialInstructions']);

	// Check for Empty Fields
	if (
		empty($_POST['firstName']) ||
		empty($_POST['lastName']) ||
		empty($_POST['emailAddress']) ||
		empty('comment')
	) {
		$errorText .= "\t\t\t\t\t<p>All fields are required.</p>\r";

		// Check for Honeypot Field
		if (!empty($_POST['specialInstruction']))
			die();

		// Display Form
		$output_form = true;

	} else { // All fields have content

		// Validate Required Input
		$validFirstName = fieldValidation($regExFirstName, $firstName);
		$validLastName = fieldValidation($regExLastName, $lastName);
		$validEmailAddress = fieldValidation($regExEmailAddress, $emailAddress);
		$validComment = fieldValidation($regExComment, $comment);

		// Check for false values
		if (
			$validFirstName == false ||
			$validLastName == false ||
			$validEmailAddress == false ||
			$validComment == false
		) {

			// Display Error Text
			if ($validFirstName == false) {
				$errorText .= "\t\t\t\t\t<p>Please enter a <em>First Name</em> between 2 and 15 characters.</p>\r";
			}

			if ($validLastName == false) {
				$errorText .= "\t\t\t\t\t<p>Please enter a <em>Last Name</em> between 2 and 15 characters.</p>\r";
			}

			if ($validEmailAddress == false) {
				$errorText .= "\t\t\t\t\t<p>Please enter a valid <em>Email Address</em>.</p>\r";
			}

			if ($validComment == false) {
				$errorText .= "\t\t\t\t\t<p>Please enter at least two characters in the <em>Comment</em> field.</p>\r";
			}

			// Display Form
			$output_form = true;
		} else { // All input is valid

			$emailMessage = "Form details below.\n\n";
			$emailMessage .= "First Name: " . clean_string($validFirstName) . "\n";
			$emailMessage .= "Last Name: " . clean_string($validLastName) . "\n";
			$emailMessage .= "Email: " . clean_string($validEmailAddress) . "\n";
			$emailMessage .= "Comment: " . clean_string($comment) . "\n";

			// create email headers
			$headers = 'From: ' . $emailAddress . "\r\n" .
				'Reply-To: ' . $emailAddress . "\r\n" .
				'X-Mailer: PHP/' . phpversion();

			@mail($emailTo, $emailSubject, $emailMessage, $headers);

			// Do not display form
			$output_form = false;
		}
	}
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Frank Jamison | Portfolio</title>
	<meta name="description" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="robots" content="all,follow">

	<!-- Bootstrap and Font Awesome css-->
	<link rel="stylesheet" type="text/css"
		href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">

	<!-- Google fonts - Montserrat for headings, Cardo for copy-->
	<link rel="stylesheet" type="text/css"
		href="https://fonts.googleapis.com/css?family=Montserrat:400,700|Cardo:400,400italic,700">

	<!-- theme stylesheet-->
	<link rel="stylesheet" type="text/css" href="css/style.default.css" id="theme-stylesheet">

	<!-- ekko lightbox-->
	<link rel="stylesheet" type="text/css" href="css/ekko-lightbox.css">

	<!-- Custom stylesheet - for your changes-->
	<link rel="stylesheet" type="text/css" href="css/custom.css">

	<!-- Favicon-->
	<link rel="shortcut icon" href="favicon.png">

	<!-- Tweaks for older IEs--><!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
			<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script><![endif]-->

</head>

<body data-spy="scroll" data-target="#navigation" data-offset="120">

	<!-- introduction-->
	<section id="intro" style="background-image: url('img/streambw.jpg');" class="intro">
		<div class="overlay"></div>
		<div class="content">
			<div class="container clearfix">
				<div class="row">
					<div class="col-md-8 col-md-offset-2 col-sm-12">
						<p class="italic">Oh, hello there, nice to meet you!</p>
						<h1>I am Frank Jamison...<br>
							<h3>Math Educator • STEM Enthusiast • Student Advocate</h3>
						</h1>
						<p class="italic">Shaping tomorrow’s thinkers—one equation, one learner, one breakthrough at a
							time.</p>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- Introduction End -->

	<!-- Navigation Bar -->
	<header class="header">
		<div class="sticky-wrapper">
			<div role="navigation" class="navbar navbar-default">
				<div class="container">
					<div class="navbar-header">
						<button type="button" title="Website Navigation" data-toggle="collapse"
							data-target=".navbar-collapse" class="navbar-btn btn-sm navbar-toggle"
							alt="Frank Jamison logo">
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>
						<a href="#intro" class="navbar-brand scroll-to"><img src="img/logo-frank-jamison-40.png"
								alt="Frank Jamison Logo"></a>
					</div>
					<div id="navigation" class="collapse navbar-collapse navbar-right">
						<ul class="nav navbar-nav">
							<li class="active"><a href="#intro">Home</a></li>
							<li><a href="#about">About </a></li>
							<li><a href="#services">Skills</a></li>
							<li><a href="#portfolio">Portfolio</a></li>
							<li><a href="#goals">Goals</a></li>
							<li><a href="#contact">Contact</a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</header>
	<!-- End Navigation Bar -->

	<!-- About-->
	<section id="about" class="text">
		<div class="container">
			<div class="row">
				<div class="col-md-6">
					<h2 class="heading">About me</h2>
					<!--<p class="lead">My Background</p>-->
					<p>Hi there—I'm Frank Jamison, a dedicated math educator, tutor, and lifelong learner based in
						Hemet, California. With a strong foundation in both teaching and technology, I bring a
						multidisciplinary perspective to education that blends analytical thinking with creative
						problem-solving.</p>
					<p>My academic journey has been expansive and intentional. It began at Mt. San Jacinto College,
						where I earned associate degrees in Math/Science, Humanities, Liberal Arts, and
						Social/Behavioral Science. I went on to complete a Bachelor of Science in Computer Science at
						National University, followed by a Master of Science in Information Technology—with a
						concentration in Web Design—through Southern New Hampshire University. To further deepen my
						technical skills, I earned a Graduate Certificate in Full Stack+ Web Development from Regis
						University and a Certificate in Web Development from UC Davis.</p>
					<p>Currently, I'm pursuing a Master of Education in Inspired Teaching and Learning at National
						University, along with a Preliminary Single Subject Teaching Credential in Mathematics. These
						programs are shaping the next phase of my professional path, equipping me with the tools and
						strategies to build inclusive, engaging, and concept-rich learning environments for today's
						students.</p>
					<p>I'm also working as a long-term substitute teacher for San Jacinto Unified School District, where
						I provide consistent academic instruction and classroom support while completing my credential
						program. In addition, I serve as a substitute teacher for Hemet Unified, stepping into a variety
						of roles to support schools across the district.</p>
					<p>Previously, I worked as an Instructional Laboratory Technician I in the Physics Department at Mt.
						San Jacinto College, where I supported STEM instruction, managed lab operations, and served on
						both the EEO Advisory Committee and the Classified Senate as Treasurer.</p>
					<p>Outside of education, I'm a passionate philatelist, specializing in U.S. stamp grading and
						valuation. I enjoy exploring the intricate stories told through postal history and the quiet
						thrill of cataloging rare and classic issues. When I'm not grading stamps or lesson planning,
						you’ll likely find me deep in a Dungeons & Dragons campaign—because sometimes the best way to
						understand probability is to roll a d20.</p>
					<p>Thanks for stopping by. Feel free to explore my portfolio to see how my experience in education
						and technology comes together with my lifelong love of learning—both in and out of the
						classroom.</p>
				</div>
				<div class="col-md-5 col-md-offset-1">
					<p><img src="img/frankcolor.jpg" alt="Frank Jamison" class="img-responsive img-circle"></p>
				</div>
			</div>
		</div>
	</section>
	<!-- About end-->

	<!-- Services-->
	<section id="services" style="background-color: #eee">
		<div class="container">
			<div class="row services">
				<div class="col-md-12">
					<h2 class="heading">Skills</h2>
					<div class="row">
						<div class="col-sm-4">
							<div class="box">
								<div class="icon"><i class="fa fa-desktop"></i></div>
								<h4>Mathematics Instruction & Student Engagement</h4>
								<p>I develop and deliver math lessons that build conceptual understanding, real-world
									connections, and problem-solving confidence. My focus is on making mathematics
									approachable, meaningful, and just challenging enough to spark growth.</p>
							</div>
						</div>
						<div class="col-sm-4">
							<div class="box">
								<div class="icon"><i class="fa fa-print"></i></div>
								<h4>Support for Students with Learning Differences</h4>
								<p>Through one-on-one tutoring and classroom teaching, I support students with mild to
									moderate disabilities, including ADHD and autism. I use clear routines, scaffolded
									instruction, and individualized strategies to help students build skills and
									independence.</p>
							</div>
						</div>
						<div class="col-sm-4">
							<div class="box">
								<div class="icon"><i class="fa fa-globe"></i></div>
								<h4>Classroom Mgmt & Relationship Building</h4>
								<p>Whether stepping into a new classroom as a substitute or leading instruction
									long-term, I create learning environments grounded in mutual respect, consistency,
									and high expectations. I prioritize connection and communication as tools for both
									behavior management and student success.</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- Services end-->

	<!-- Portfolio / gallery-->
	<section id="portfolio" class="gallery">
		<div class="container clearfix">
			<div class="row">
				<div class="col-md-12">
					<div class="row">
						<div class="col-md-12 col-lg-8">
							<h2 class="heading">Websites</h2>
							<p>Here is a small sampling of some of the websites I run... </p>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-4">
							<div class="box">
								<a href="https://jamisonstamps.com/" title="JamisonStamps.com" target="_blank"><img
										src="img/Portfolio-JamisonStamps.com.png" alt="https://jamisonstamps.com/"
										class="img-responsive"></a>
								<div class="projectTitle">
									<h5 class="project">JamisonStamps.com<br>Business Website for<br>my stamp dealing
										business</h5>
								</div>
							</div>
						</div>
						<div class="col-sm-4">
							<div class="box">
								<a href="https://passionateteachingjourney.com/" title="PassionateTeachingJourney.com"
									target="_blank"><img src="img/Portfolio-PassionateTeachingJourney.com.png"
										alt="https://passionateteachingjourney.com/" class="img-responsive"></a>
								<div class="projectTitle">
									<h5 class="project">PassionateTeachingJourney.com<br>Personal Blog
										Showcasing<br>Educational Accomplishments</h5>
								</div>
							</div>
						</div>
						<div class="col-sm-4">
							<div class="box">
								<a href="http://DnD5eTools.com" title="DnD 5e Tools Website" target="_blank"><img
										src="img/Portfolio-DnD5eTools.com.png" alt="DnD5eTools.com Website"
										class="img-responsive"></a>
								<div class="projectTitle">
									<h5 class="project">DnD5eTools.com<br>Online Tools For<br>Dungeons &amp; Dragons 5th
										Edition</h5>
									<!-- <a href="portfolio/source-code/DnD5eTools.com.zip" title="Download Source Code">
											<img src="img/download.jpg" alt="Download Source Code" class="download">
										</a> -->
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- Portfolio / gallery end-->

	<!-- Goals for 2025 page-->
	<section id="goals" style="background-color: #333;" class="text-page section-inverse">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<h2 class="heading">Goals for 2025 and Beyond</h2>
					<div class="row">
						<div class="col-sm-6">

							<head>The journey of education continues to unfold like a map drawn in real time-each
								landmark a lesson, each path a purpose. My mission remains rooted in growth, fueled by
								curiosity, and driven by a commitment to the success of every student I encounter. These
								are the goals I'm actively pursuing:</p>
								<p><strong>Master's in Education: Inspired Teaching and Learning</strong><br>Currently
									completing my M.Ed. at National University, this program has shaped and sharpened my
									philosophy as an educator. It's more than coursework-it's a transformation. Through
									evidence-based strategies and a student-centered approach, I'm learning to create
									classrooms that spark curiosity, nurture individuality, and promote deep, meaningful
									learning. With each assignment and practicum, I refine my ability to connect with
									students and design instruction that sticks-with heart, humor, and high standards.
								</p>
								<p><strong>California Single Subject Teaching Credential in
										Mathematics</strong><br>Mathematics isn't just about numbers-it's a language, a
									lens, and sometimes, a lifeline. I'm in the final stages of earning my California
									Single Subject Teaching Credential in Mathematics. My goal is to reshape the
									narrative around math, transforming it from intimidating to illuminating. By
									blending conceptual clarity, real-world application, and a touch of creativity, I
									aim to help students not just "get it," but enjoy it. With this credential, I'll be
									fully equipped to lead secondary math classrooms where confidence grows and
									problem-solving thrives.</p>
								<p><strong>Classroom Experience: Building Foundations Daily</strong><br>I'm currently
									working as a substitute teacher for both Hemet Unified and San Jacinto Unified
									School Districts. These roles have allowed me to step into diverse classrooms, adapt
									quickly, and support students across a wide range of subjects and grade levels. In
									Fall 2025, I'll be stepping into a long-term substitute teaching position with
									SJUSD-a pivotal opportunity to bring consistency, creativity, and compassion to a
									single classroom over an extended period. It's a chance to put theory into practice
									and grow deeper roots in the educational community I'm proud to serve.</p>
								<p><strong>Specialized Tutoring: Empowering Every Learner</strong><br>In addition to my
									classroom work, I provide individualized academic support to students with mild to
									moderate disabilities through TutorMe Education. This role allows me to tailor
									instruction to meet unique learning needs, focusing on executive functioning,
									reading comprehension, and math skills. Working one-on-one with students has
									deepened my understanding of differentiated instruction and strengthened my
									commitment to inclusive, equitable education for all learners.</p>
								<p>As I move toward becoming a fully credentialed educator, my commitment remains the
									same: to be a mentor, motivator, and model for what learning can look like when it's
									done with passion and purpose. The destination is always the same-empowered
									students-but the journey? That's where the magic happens. Let's keep moving forward.
								</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- End Goals Page -->

	<!-- contact-->
	<section id="contact" style="background-color: #333;" class="text-page contact-form">
		<div class="container">
			<div class="row">

				<div class="col-md-12">

					<h2 class="heading">Contact</h2>
					<?php
					if ($output_form) {
						?>
						<div class="row">
							<div class="col-md-6">

								<?= $errorText ?>

								<form action="<?= $_SERVER['PHP_SELF'] . '#contact' ?>" method="post"
									enctype="multipart/form-data">

									<div class="controls">
										<div class="row">
											<div class="col-sm-6">
												<div class="form-group">
													<label for="firstName">Your first name *</label>
													<input type="text" name="firstName" id="firstName"
														placeholder="Enter your first name" required="required"
														class="form-control">
												</div>
											</div>
											<div class="col-sm-6">
												<div class="form-group">
													<label for="lastName">Your last name *</label>
													<input type="text" name="lastName" id="lastName"
														placeholder="Enter your  last name" required="required"
														class="form-control">
												</div>
											</div>
										</div>
										<div class="form-group">
											<label for="emailAddress">Your email address *</label>
											<input type="email" name="emailAddress" id="emailAddress"
												placeholder="Enter your  email address" required="required"
												class="form-control">
										</div>
										<div class="form-group">
											<label for="comment">Your message for me *</label>
											<textarea rows="4" name="comment" id="comment" placeholder="Enter your message"
												required="required" class="form-control"></textarea>
										</div>
										<div class="text-center">
											<input type="submit" name="submit" value="Send message"
												class="btn btn-primary btn-block">
										</div>
										<div class="form-group special-instructions">
											<label for="specialInstructions" class="special-instructions">Your special
												instructions *</label>
											<input type="text" name="specialInstructions" id="specialInstructions"
												placeholder="Enter your special instructions" class="form-control">
										</div>
									</div>
								</form>
							</div>
							<div class="col-md-6">
								<p>If you like my portfolio and wish to contact me about an employment opportunity, please
									fill out the contact form and I will get back to you as soon as possible.</p>
								<p class="social">
									<!--<a href="#" title="" class="facebook"><i class="fa fa-facebook"></i></a><a href="#" title="" class="twitter"><i class="fa fa-twitter"></i></a><a href="#" title="" class="gplus"><i class="fa fa-google-plus"></i></a><a href="#" title="" class="instagram"><i class="fa fa-instagram"></i></a><a href="#" title="" class="email"><i class="fa fa-envelope"></i></a>-->
								</p>
							</div>
						</div>

						<?php
					} else {
						?>

						<h5>Thank you for submitting my contact form! I will get back to you within 24 hours.</h5>

						<?php
						header("refresh:5; url=http://frankjamison.com/index.php#contact");
					}
					?>

				</div>
			</div>
		</div>
	</section>
	<!--<div id="map"></div>-->
	<footer style="background-color: #111;" class="section-inverse">
		<div class="container">
			<div class="row copyright">
				<div class="col-md-6">
					<p>&copy;
						<script>document.write(new Date().getFullYear())</script> Frank Jamison
					</p>
				</div>
			</div>
		</div>
	</footer>
	<!-- Javascript files-->
	<!-- jQuery-->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<!--<script>window.jQuery || document.write('<script src="js/jquery.min.js"><\/script>')</script>-->
	<!-- Bootstrap CDN-->
	<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
	<!-- jQuery Cookie - For Demo Purpose-->
	<!--<script src="js/jquery.cookie.js"></script>-->
	<!-- Lightbox-->
	<script src="js/ekko-lightbox.js"> </script>
	<!-- Sticky + Scroll To scripts for navbar-->
	<script src="js/jquery.sticky.js"></script>
	<script src="js/jquery.scrollTo.min.js"></script>
	<!-- google maps-->
	<!--<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCYmNwdFLJ4ZadhEI0Evi9hYF69l9NTLZc"></script>-->
	<!-- to use it on your site, generate your own API key for Google Maps and paste it above -->
	<!--<script src="js/gmaps.js"></script>-->
	<!-- main script-->
	<script src="js/front.js"></script>

</body>

</html>