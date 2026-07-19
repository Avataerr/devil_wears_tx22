<?php
$page_title = "About";
require_once __DIR__ . "/header.php";
?>

<div class="row justify-content-center">
    <div class="col-lg-10">

        <div class="hero-panel">
            <div class="hero-kicker">About the Project</div>

            <h1 class="hero-title">Devil Wears TX22</h1>

            <p class="hero-text">
                This website is a student-made bag shop system for our final project. It includes a seller/admin side, a buyer side, cart and checkout flow, and simple inventory and audit reports.
            </p>
        </div>

        <div class="row g-3 mt-1">

            <div class="col-md-7">
                <div class="page-card p-4 h-100">

                    <div class="section-title">Company Info</div>

                    <p class="muted">
                        Founded on the belief that fashion is not just utility, but an art form that dictates the cultural zeitgeist. We craft exceptional luxury bags for those who understand that a single accessory can define an entire era. Devil Wears TX22 is a demo online store for bags. It was designed to demonstrate basic e-commerce features using PHP and MySQL.
                    </p>

                    <hr>

                    <div class="section-title">Mission</div>

                    <p class="muted">
                        Our mission is to create timeless, structural masterpieces that transcend seasonal whims. We provide the pieces that everyone covets, but only the discerning possess.
                    </p>

                    <hr>

                    <div class="section-title">Vision</div>

                    <p class="muted mb-0">
                        To remain the ultimate benchmark of luxury, forcing the rest of the industry to move at our pace.
                    </p>

                </div>
            </div>

            <div class="col-md-5">
                <div class="page-card p-4 h-100">

                    <div class="section-title">Group Members</div>

                    <div class="row text-center g-4 mt-3">

                        <div class="col-12">
                            <img
                                src="uploads/members/maverrick.jpg"
                                class="member-photo"
                                alt="Maverrick James Watanabe">

                            <h6 class="member-name">
                                Maverrick James Watanabe
                            </h6>
                        </div>

                        <div class="col-12">
                            <img
                                src="uploads/members/simoune.jpg"
                                class="member-photo"
                                alt="Simoune Nicole Toquero">

                            <h6 class="member-name">
                                Simoune Nicole Toquero
                            </h6>
                        </div>

                    </div>

                </div>
            </div>

        </div>

    </div>
</div>

<?php require_once __DIR__ . "/footer.php"; ?>