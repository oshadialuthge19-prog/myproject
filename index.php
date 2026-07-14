<?php

include "Includes/db.php";

$mentorQuery = $conn->prepare("
    SELECT usersId, usersName
    FROM users
    WHERE role = 'mentor'
    ORDER BY usersName ASC
");

$mentorQuery->execute();

$mentors = $mentorQuery->get_result();

?>


<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Smart Mentoring System</title>

    <!-- Bootstrap CSS -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <!-- Boxicons -->
    <link
        href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css"
        rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="index.css">
    <link rel="stylesheet" href="Includes/footer.css">
<link
rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>

<body>

<!-- HEADER -->
<?php include "Includes/header.php"; ?>


<!-- =========================
     HERO SECTION
========================= -->

<section class="hero" id="home">

    <div class="hero_container">

        <h1 class="hero_heading">

            Connect Students with
            <span>Right</span> Mentors

        </h1>

        <p class="hero_description">

            Find the perfect mentor to guide your
            academic and professional journey.

        </p>

        <button class="main_btn">

            <a href="#about">
                Explore
            </a>

        </button>

    </div>

</section>


<!-- =========================
     ABOUT SECTION
========================= -->

<section class="main" id="about">

    <div class="container">

        <div class="row align-items-center g-5">

            <!-- Image Side -->

            <div class="col-lg-6 text-center">

                <div class="main_img-card">

                    <i class='bx bx-group'></i>

                </div>

            </div>


            <!-- Content Side -->

            <div class="col-lg-6 main_content">

                <h1>
                    What do we do?
                </h1>

                <h2>
                    Guiding You to Success!
                </h2>

                <p>

                    Helping students connect with experienced
                    mentors to achieve academic, personal,
                    and career success.

                </p>

            </div>

        </div>

    </div>

</section>


<!-- =========================
     MENTORS SECTION
========================= -->

<section class="mentor-section" id="mentors">

    <div class="container">

        <div class="mentor-heading reveal">

            <span class="section-label">
                OUR MENTORS
            </span>

            <h1>
                Meet Your <span>Mentors</span>
            </h1>

            <p>
                Connect with experienced mentors who are ready
                to guide and support your academic journey.
            </p>

        </div>


        <div class="row justify-content-center g-4">

            <?php if($mentors->num_rows > 0){ ?>

                <?php
                $delay = 0;

                while($mentor = $mentors->fetch_assoc()){
                ?>

                <div class="col-md-6 col-lg-4 mentor-reveal"
                     style="--delay: <?php echo $delay; ?>ms;">

                    <div class="mentor-card">

                        <div class="mentor-card-glow"></div>


                        <div class="mentor-avatar">

                            <i class='bx bx-user'></i>

                        </div>


                        <div class="mentor-info">

                            <span class="mentor-status">

                                <span class="status-dot"></span>

                                Available Mentor

                            </span>


                            <h2>

                                <?php
                                echo htmlspecialchars(
                                    $mentor['usersName']
                                );
                                ?>

                            </h2>


                            <p class="mentor-role">

                                Academic Mentor

                            </p>


                            <p class="mentor-description">

                                Ready to provide academic guidance,
                                mentoring support and help students
                                progress throughout their university
                                journey.

                            </p>


                            <a href="login.php"
                               class="mentor-button">

                                Connect with Mentor

                                <i class='bx bx-right-arrow-alt'></i>

                            </a>

                        </div>

                    </div>

                </div>

                <?php

                $delay += 150;

                }

                ?>

            <?php }else{ ?>

                <div class="col-12">

                    <div class="no-mentors">

                        <i class='bx bx-user-x'></i>

                        <h3>No mentors available</h3>

                        <p>
                            Registered mentors will appear here.
                        </p>

                    </div>

                </div>

            <?php } ?>

        </div>

    </div>

</section>


<!-- FOOTER -->

<?php include "Includes/footer.php"; ?>


<!-- Bootstrap JavaScript -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


<script>

const mentorCards =
document.querySelectorAll(".mentor-reveal");

const mentorObserver =
new IntersectionObserver((entries) => {

    entries.forEach(entry => {

        if(entry.isIntersecting){

            entry.target.classList.add("active");

            mentorObserver.unobserve(entry.target);

        }

    });

}, {
    threshold: 0.15
});


mentorCards.forEach(card => {

    mentorObserver.observe(card);

});

</script>
</body>

</html>
